<?php

ob_start();

require('../lib/config.inc.php');
DENY($arch_status);

session_start();

function input_data_invalid($err_code)
{
   $backurl = 'http://' . $GLOBALS['_SERVER']['HTTP_HOST'] . $GLOBALS['conf']['prefix'] . '/offline/playlist.php';
   header("Location: $backurl");
   $_SESSION['error'] = $err_code;
   ob_end_flush();
   exit;
}

/* validate input form's data */
$expire=time()+5184000;
$_pg = dirname($_SERVER['SCRIPT_NAME']) . '/' ;

if (!is_array($cams) || (count($cams) === 0) )
  input_data_invalid('cams');
foreach ($cams as &$value) {
  if ( !settype($value,'int') )
    input_data_invalid('cams');
}
setcookie('avreg_cams[]', implode('-',$cams), $expire,$_pg);

$ftypes = array(23,32); // FIXME video, audio

$int_vals=array('timemode',
'year1', 'month1', 'day1', 'hour1', 'minute1',
'year2', 'month2', 'day2', 'hour2', 'minute2');
foreach ($int_vals as &$value) {
  if (!isset($GLOBALS[$value]))
    input_data_invalid($value);
  if (!settype($GLOBALS[$value],'int'))
    input_data_invalid($value);
  $_SESSION[$value] = $GLOBALS[$value];
}

setcookie('avreg_timemode',$_POST['timemode'],$expire,$_pg);
if ( $timemode === 2 ) {
  /* интервалы */
  setcookie('avreg_timemode','2',$expire,$_pg);
  if (!is_array($dayofweek) || (count($dayofweek) === 0) )
    input_data_invalid('dayofweek');
  foreach ($dayofweek as &$value) {
    if (!settype($value,'int'))
      input_data_invalid('dayofweek');
  }
  $_SESSION['dayofweek'] = $dayofweek;
} elseif ( $timemode == '1' ) {
  /* диапазон */
  setcookie('avreg_timemode','1',$expire,$_pg);
} else
    input_data_invalid('timemode');

$xspf = false;
if ( empty($pl_fmt) )
    input_data_invalid('pl_fmt');
if ( 0 == strcasecmp('XSPF', $pl_fmt)) {
  $xspf = true;
  setcookie('avreg_pl_fmt', 'XSPF', $expire,$_pg);
} elseif ( 0 == strcasecmp('M3U', $pl_fmt)) {
  setcookie('avreg_pl_fmt','M3U',$expire,$_pg);
} else
  input_data_invalid('pl_fmt' . $pl_fmt);

/*
if (($_POST['cams_ordered']))
      setcookie('avreg_cams_ordered','1',$expire,$_pg);
  else
      setcookie('avreg_cams_ordered','0',$expire,$_pg);
*/

/* build SQL query */
$cams_csv = implode(',', $cams);
if ( $timemode === 1 ) {
  $timebegin = sprintf('20%02s-%02u-%02u %02u:%02u:00',
                $year_array[$year1],$month1,$day1,$hour1,$minute_array[$minute1]);
  $timeend   = sprintf('20%02s-%02u-%02u %02u:%02u:59',
                $year_array[$year2],$month2,$day2,$hour2,$minute_array[$minute2]);
  $query = 'select UNIX_TIMESTAMP(DT1) as UDT1, UNIX_TIMESTAMP(DT2) as UDT2,'.
            ' CAM_NR,EVT_ID,SER_NR,FILESZ_KB,FRAMES,U16_1,U16_2,EVT_CONT'.
            ' from EVENTS where '.
            ' ( (DT1 between \''.$timebegin.'\' and \''.$timeend.'\') or (DT2 between \''.$timebegin.'\' and \''.$timeend.'\') ) '.
            ' and (CAM_NR in ('.$cams_csv.'))'.
            ' and (EVT_ID in ('.implode(',', $ftypes).'))'.
            ' order by DT1';
} else {
  $timebegin = sprintf('20%02s-%02u-%02u 00:00:00', $year_array[$year1],$month1,$day1);
  $timeend   = sprintf('20%02s-%02u-%02u 23:59:59', $year_array[$year2],$month2,$day2);
  $min1=&$minute_array[$minute1];
  $min2=&$minute_array[$minute2];
  $time_in_day_begin = sprintf('%02u:%02u:00',$hour1,$min1);
  $time_in_day_end   = sprintf('%02u:%02u:59',$hour2,$min2);
  $query =  'select UNIX_TIMESTAMP(DT1) as UDT1, UNIX_TIMESTAMP(DT2) as UDT2,'.
            ' CAM_NR,EVT_ID,SER_NR,FILESZ_KB,FRAMES,U16_1,U16_2,EVT_CONT'.
            ' from EVENTS where '.
            ' ( (DT1 between \''.$timebegin.'\' and \''.$timeend.'\') or (DT2 between \''.$timebegin.'\' and \''.$timeend.'\') ) '.
                        ' and (CAM_NR in ('.$cams_csv.'))'.
                        ' and (EVT_ID in ('.implode(',', $ftypes).'))'.
            ' and (WEEKDAY(DT1) in ('.implode(',', $dayofweek).') or WEEKDAY(DT2) in ('.implode(',', $dayofweek).')) '.
            ' and ((TIME(DT1) between \''.$time_in_day_begin.'\' and \''.$time_in_day_end.'\') or (TIME(DT2) between \''.$time_in_day_begin.'\' and \''.$time_in_day_end.'\')) '.
            ' order by DT1';
}

/* Performing new SQL query */
require ('../lib/my_conn.inc.php');
$result = mysql_query($query) or die('SQL query failed: ' . mysql_error());
$num_rows = 0;
$res_array=array();
while ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
{
   $res_array[$num_rows] = $row;
   $num_rows++;
}

require_once ('../lib/my_close.inc.php');
if ( $conf['debug'] )
  $_SESSION['sql'] = $query;
if ( $num_rows === 0 )
  input_data_invalid('0');

/* отправляем http headers */
while (@ob_end_clean());

// Don't use cache (required for Opera)
$now_ts = time();
$now = gmdate('D, d M Y H:i:s', $now_ts) . ' GMT';
header('Expires: ' . $now);
header('Last-Modified: ' . $now);
header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

function getDayName($n) {
  return $GLOBALS['day_of_week'][$n];
}

$CRLF="\r\n";
$fname="avreg_cams-$cams_csv";
if ( $xspf ) {
  header('Content-Type: application/xspf+xml');
  header("Content-Disposition: attachment; filename=\"$fname.xspf\"");
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$CRLF";
  echo "<playlist version=\"0\" xmlns=\"http://xspf.org/ns/0/\">$CRLF";
  if ( $timemode === 2 )
     printf("\t<title>time: [%s - %s], [%s], [%s-%s]</title>$CRLF",
        substr($timebegin,0,-9), substr($timeend,0,-9),
        implode(',', array_map("getDayName", $dayofweek)),
        substr($time_in_day_begin,0,-3), substr($time_in_day_end,0,-3));
  else
     printf("\t<title>[%s - %s]</title>$CRLF",
        substr($timebegin,0,-3), substr($timeend,0,-3));
  echo "\t<creator>cams: [".$cams_csv."]</creator>$CRLF";
  echo "\t<annotation>$query</annotation>$CRLF";
  echo "\t<info>http://avreg.net</info>$CRLF";
  $main_date = gmdate("Y-m-d\TH:i:s", $now_ts);
  $tz = date("O", $timestamp);
  $tz = substr_replace ($tz, ':', 3, 0);
  echo "\t<date>$main_date$tz</date>$CRLF";
  echo "\t<trackList>$CRLF";
} else {
  header('Content-Type: audio/x-mpegurl');
  header("Content-Disposition: attachment; filename=\"$fname.m3u\"");
  echo "#EXTM3U$CRLF";
}

while (@ob_end_flush());

/* вычисляем протокол доступа к медиа-файлам 
   на основании адреса клиента и правил в конфиге */
if ( 0 === strpos($_SERVER['SERVER_ADDR'], $_SERVER['REMOTE_ADDR']) ) {
   /* считаем (?) что такое может быть только для локального клиента 
    * а вот и не так, также будет если проксик на том же сервере 
    * и клиенты через него тупо(в той же подсети) работают */
   $MediaUrlPref = 'file://' . $conf['storage-dir'];
} else {
   require_once($wwwdir . 'lib/utils-inet.php');
   $remote_ip = ip2long($_SERVER['REMOTE_ADDR']);
   /* удалённый клиент */
   $ua = strtolower($_SERVER['HTTP_USER_AGENT']);

   if ( $MSIE /* only win */ || false !== strpos($ua, 'windows') )
      $platform = 'win';
   else if ( false !== strpos($ua, 'linux') ||
      false !== strpos($ua, 'solaris') ||
      false !== strpos($ua, 'bsd') ||
      false !== strpos($ua, 'mac os x') )
      $platform = 'nix';
   else 
      $platform = 'other';

   $_done = false;
   $_ipacl_list = array_keys(&$conf["murl-pre-$platform"]);
   foreach ( $_ipacl_list as &$_ipacl_str ) {
      $_ipacl = avreg_inet_network($_ipacl_str);
      if ( $_ipacl === false ) {
         /* неправильно задан IP ACL */
         break;
      }
      if ( true === avreg_ipv4_cmp( $remote_ip, -1, $_ipacl['addr'],  $_ipacl['mask']) ) {
         $MediaUrlPref = &$conf["murl-pre-$platform"][$_ipacl_str];
         break;
      }
   }
   if ( !isset($MediaUrlPref) /* не нашли совпадения по ACL */ )
      $MediaUrlPref = 'http://' . $_SERVER['HTTP_HOST'] . $conf['prefix'] . $conf['media-alias'];
}

for ($i=1; $i<=$num_rows; $i++)
{
  $row = &$res_array[$i-1];
  $location = $MediaUrlPref . '/' .$row['EVT_CONT'];
  if ( $xspf ) {
    $title = strftime('%a, %d %b %H:%M:%S', (int)$row['UDT1']);
    $duration = ((int)$row['UDT2'] - (int)$row['UDT1'])*1000;
    echo "\t\t<track>$CRLF";
    echo "\t\t\t<location>$location</location>$CRLF";
    echo "\t\t\t<title>$title</title>$CRLF";
    printf("\t\t\t<creator>cam #%02u</creator>$CRLF",(int)$row['CAM_NR']);
    echo "\t\t\t<annotation>".filesizeHuman((int)$row['FILESZ_KB']).', '. $row['FRAMES'] ." frames</annotation>$CRLF";
    echo "\t\t\t<duration>$duration</duration>$CRLF";
    echo "\t\t\t<album>". $row['EVT_ID'] ."</album>$CRLF";
    echo "\t\t\t<trackNum>$i</trackNum>$CRLF";
    echo "\t\t</track>$CRLF";
  } else {
    $title = sprintf('cam #%02u - ', (int)$row['CAM_NR']) . date('Y-m-d H:i:s', (int)$row['UDT1']);
    $duration = (int)$row['UDT2'] - (int)$row['UDT1'];
    echo "#EXTINF:$duration,$title$CRLF";
    echo "$location$CRLF";
  }
}

if ( $xspf ) {
  echo "\t</trackList>$CRLF";
  echo "</playlist>$CRLF";
}

if (isset($_SESSION) && isset($_SESSION['error']))
  @session_unregister('error');
