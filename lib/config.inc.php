<?php

$PrNameEng = 'AVReg';
require ('/etc/avreg/site-defaults.php');
$wwwdir = $conf['site-dir'] . '/';

if ($conf['debug']) {
ini_set ('display_errors', '0' );
ini_set ('log_errors', '1');
ini_set ('html_errors', '0');
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | 
               E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING | 
               E_USER_NOTICE);
/*
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_CORE_ERROR | E_CORE_WARNING | 
               E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING |
               E_USER_NOTICE | E_STRICT | E_RECOVERABLE_ERRROR );
*/
}

/*
if (empty($conf['prefix']))
   $wwwdir = $_SERVER['DOCUMENT_ROOT'] . '/';
else
   $wwwdir = $_SERVER['DOCUMENT_ROOT'] . $conf['prefix'] . '/';
*/
require($wwwdir . 'lib/grab_globals.lib.php');

$BOOL_VAL = 1;
$INT_VAL  = 2;
$INTPROC_VAL  = 3; // int or %
$CHECK_VAL = 4;
$STRING_VAL = 5;
$STRING200_VAL = 6;
$PASSWORD_VAL = 7;

function tohtml($var)
{
      print '<div class="dump"><pre class="dump">'."\n";
      var_dump($var);
      print '</pre></div>'."\n";
}

/* $params строковй массив, список параметров, которые нужно читать из файла */
function confparse($_conf, $section=NULL, $path='/etc/avreg/avreg.conf', $params=NULL)
{
   $confile = fopen($path, 'r');
   if (false === $confile)
      return false;
   $skip_section = false;
   $linenr=0;
   $ret_array = array();
   $res = true;

   while (!feof($confile)) {
      $line = trim(fgets($confile, 1024));
      $linenr++;
      if (empty($line)) 
         continue;

      if ( preg_match('/^\s*[;#]/', $line) )
         continue; /* skip comments */

      if ( preg_match('/^([^\s=]+)[\s=]*\{$/', $line, $matches)) {
         # begin section
         if ( empty($section) || 0 !== strcasecmp ($matches[1],$section) ) {
            $skip_section = true;
         }
         continue;
      }

      if ( preg_match('/.*\}$/',$line) ) {
         $skip_section = false;
         continue;
      }

      if ($skip_section)
         continue;

      if ( 1 !== preg_match("/^[\s]*([^\s#;=]+)[\s=]+([\"']?)(.*?)(?<!\\\)([\"']?)\s*$/Su",
                  $line, $matches)) {
         $res = false;
         break;
      }
      // var_dump($matches);

      $start_quote = &$matches[2];
      $end_quote = &$matches[4];
      if ( $start_quote !== $end_quote ) {
         $res = false; break;
      }

      $param = &$matches[1];
      $value = stripslashes($matches[3]);

      if (is_array($params))
          if (FALSE === array_search($param, $params))
            continue;

      // нашли параметр
      // echo ("file $path:$linenr => $param = \"$value\"\n");
      if ( 0 === strcasecmp($param, 'include') ) {
         // вложенный файл 
         $res = confparse($_conf, $section, $value);
         if (!$res) {
            echo "ERROR INCLUDE FILE \"$value\" from $path:$linenr\n";
            $res=false;  break;
         } else {
            $ret_array = array_merge($ret_array, $res);
         }
      } else {
         /* обычное параметр = значение */
         /* проверяем парамет - а мож это массив */
         if ( 1 === preg_match("/^([^\[]+)\[([\"']?)([^\]]*?)([\"']?)\]$/Su",$param,$match2) ) {
            /* наш параметр -- массив */
            $param = $match2[1];
            $key = $match2[3];
            $vt = gettype($_conf[$param]);
            if ( 0 !== strcasecmp($vt, 'array')) {
               $res=false;  break;
            }
            $ret_array[$param][$key] = $value;
         } else {
            /* простое параметр, не массив */
            /* пробуем установить тип значения с учётом дефолтного $conf[param] */
            $vt = gettype($_conf[$param]);
            if ( $vt !== 'NULL' && !settype($value, $vt) ) {
               $res = false; break;
            }
            $ret_array[$param] = $value;
         }
      }
   } // while eof

   fclose($confile);

   if ( $res ) {
      return $ret_array;
   } else {
      // invalid pair param = value
      echo ("INVALID LINE in file $path:$linenr => [ $line ]\n");
      return false;
   }
}

$res=confparse($conf, 'avreg-site');
if (!$res) {
   die();
} else
   $conf = array_merge($conf, $res);


unset($AVREG_PROFILE);
if ( preg_match('@^/([^/]+).*@', $_SERVER['REQUEST_URI'], $matches) ) {
if ( strcasecmp($matches[1],'avreg') != 0 ) {
   $res = confparse('avreg-site', $conf['profiles-dir'].'/'.$matches[1]);
   if (!$res)
      die("<br /><br />Error: not found active profile ".$conf['profiles-dir'].'/'.$matches[1]);
   $AVREG_PROFILE = $matches[1];
// tohtml($res);
   if (is_array($res)) {
         $conf = array_merge($conf, $res);
         $conf['prefix'] = '/'.$AVREG_PROFILE;
         $conf['daemon-name'] .= '-'.$AVREG_PROFILE;
   }
}
}

$sip = $_SERVER['SERVER_ADDR'];
if ( $_SERVER['SERVER_ADDR'] === $_SERVER['SERVER_NAME'] )
  $named = $_SERVER['SERVER_ADDR'];
else
  $named = $_SERVER['SERVER_NAME'];
$localip = ip2long($sip);

$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$MSIE = $GECKO  = $PRESTO = $WEBKIT = false;

if ( false !== strpos($ua, 'msie') )
   $MSIE  = true;
else if ( false !== strpos($ua, 'gecko') )
   $GECKO = true;
else if ( false !== strpos($ua, 'presto') )
   $PRESTO = true;
else if ( false !== strpos($ua, 'webkit') || false !== strpos($ua, 'khtml') )
   $WEBKIT = true;


$login_user = 'unknown';
$login_user_name = 'unknown';
$remote_addr = 'unknown';
$login_host = 'unknown';
$users = array();
$user_info = NULL;

$link=NULL;

$user_status = 555;
$install_status = 1;
$admin_status = 2;
$arch_status = 3;
$operator_status = 4;

$install_user = FALSE;
$admin_user = FALSE;
$arch_user = FALSE;
$operator_user = FALSE;

$chset = 'UTF-8';
$lang = 'russian';
$locale_str='ru_RU.'.$chset;
setlocale(LC_ALL, $locale_str);
// @apache_setenv('LANG','ru_RU.'.$chset);
mb_internal_encoding( 'UTF-8' );
$lang_dir =  $wwwdir . 'lang/' . $lang . '/'  . strtolower($chset) . '/';
$lang_module_name   = $lang_dir . 'common.inc.php';
$pt = substr($_SERVER['PHP_SELF'],strlen($conf['prefix']));
$params_module_name = $lang_dir . 'params.inc.php';
require ($lang_module_name);

if (isset($lang_file)) {
   $lang_module_name2  = $lang_dir . $lang_file;
} else
   $lang_module_name2  = $lang_dir . str_replace ('/', '_', $pt);

if (file_exists ($lang_module_name2))
   require ($lang_module_name2);

if ( $_SERVER['REMOTE_ADDR'] === '::1' )
   $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

if ( $_SERVER['REMOTE_ADDR'] === '127.0.0.1' ) 
   $remote_addr = 'localhost';
else
   $remote_addr = &$_SERVER['REMOTE_ADDR'];

if (file_exists('/etc/linuxdvr-release')) {
$LDVR_VER=@file('/etc/linuxdvr-release');
} else
$LDVR_VER=false;


$font_family = 'sans-serif';
$font_size = 'small';
$ContentColor = '#F5F5F5';
$inactive_h_color = 'darkGray';
$warn_color = '#003366';
$error_color = '#CC3333';
$rowHiLight = '#FFFFCC';
$header_color = '#D0DCE0';
$TextHiLight = '#009900';

$NotSetParColor = '#585858';
$ParDefColor = '#6633FF';
$ParSetColor = 'Red';

$MAX_CAM = 1000;
$cfg['LeftWidth'] = '170';
$left_bgcolor = '#D0DCE0';
$tabletag = '<table cellspacing="0" border="1" cellpadding="2" align="center">';

$patternIP='[1-9]\d{1,2}\.\d{1,3}\.\d{1,3}\.\d{1,3}';
$patternAllowedIP='/^('.$patternIP.'|any|localhost|*)$/';
$patternUser='/^[A-Za-z0-9_\-]{4,16}$/';
$patternPasswd='/^[A-Za-z0-9_\-]{0,16}$/';

$WellKnownAspects = array(
  array( 4, 3),
  array(10, 8),
  array(11, 9),
  array( 3, 2),
  array(16, 9),
  array(16,10),
  array( 5, 4)
);

require($wwwdir.'/lib/my_conn.inc.php');
$query = 'SELECT HOST, USER, PASSWD, STATUS, ALLOW_CAMS,
 FORCED_SAVING_LIMIT,
 SESSIONS_PER_CAM, LIMIT_FPS, NONMOTION_FPS, LIMIT_KBPS,
 SESSION_TIME, SESSION_VOLUME,
 LONGNAME, CHANGE_HOST, CHANGE_USER, CHANGE_TIME
 FROM USERS ORDER BY STATUS';
$result = mysql_query($query) or die('SQL query failed: `'. mysql_error() ."`\n");
while ($row = mysql_fetch_array($result, MYSQL_ASSOC) )
{
/* var_dump($row); */
$ui = array();
$ui['HOST'] = $row['HOST'];
$ui['USER'] = $row['USER'];
$ui['PASSWD'] = $row['PASSWD'];
$ui['STATUS'] = (int)$row['STATUS'];
$ui['ALLOW_CAMS'] = $row['ALLOW_CAMS'];
$ui['FORCED_SAVING_LIMIT'] = is_null($row['FORCED_SAVING_LIMIT'])?NULL:(int)$row['FORCED_SAVING_LIMIT'];
$ui['SESSIONS_PER_CAM'] = is_null($row['SESSIONS_PER_CAM'])?NULL:(int)$row['SESSIONS_PER_CAM'];
$ui['LIMIT_FPS'] = is_null($row['LIMIT_FPS'])?NULL:(int)$row['LIMIT_FPS'];
$ui['NONMOTION_FPS'] = is_null($row['NONMOTION_FPS'])?NULL:(float)$row['NONMOTION_FPS'];
$ui['LIMIT_KBPS'] = is_null($row['LIMIT_KBPS'])?NULL:(int)$row['LIMIT_KBPS'];
$ui['SESSION_TIME'] = is_null($row['SESSION_TIME'])?NULL:(int)$row['SESSION_TIME'];
$ui['SESSION_VOLUME'] = is_null($row['SESSION_VOLUME'])?NULL:(int)$row['SESSION_VOLUME'];
$ui['LONGNAME'] = $row['LONGNAME'];
$ui['CHANGE_HOST'] = $row['CHANGE_HOST'];
$ui['CHANGE_USER'] = $row['CHANGE_USER'];
$ui['CHANGE_TIME'] = $row['CHANGE_TIME'];
$users[] = $ui;
}
mysql_free_result($result); unset($result);

function get_user_info($ipacl, $name)
{
   if (!isset($ipacl) || !isset($name))
      return false;
   if (empty($ipacl) || empty($name))
      return false;

   foreach ($GLOBALS['users'] as $ui) {
      if ( 0 === strcasecmp($ui['HOST'], $ipacl) && 
         0 === strcmp($ui['USER'], $name) )
         return $ui;
   }
   return false;
}

function avreg_find_user($addr, $mask, $name)
{
   if (!isset($addr) || !isset($mask) || !isset($name))
      return false;
   if (is_null($addr) || is_null($mask) || is_null($name) )
      return false;

   $found = false;
   foreach ($GLOBALS['users'] as $ui) {
      if ( 0 !== strcmp($ui['USER'], $name) )
         continue;

      $ipacl = avreg_inet_network($ui['HOST']);
      if ( $ipacl === FALSE )
         continue; // FIXME - may be error/warning?

      $found = avreg_ipv4_cmp( $addr, $mask,
                              $ipacl['addr'],  $ipacl['mask']);
/*
      syslog(LOG_ERR,sprintf("equal = %d, 0x%X/0x%X 0x%X/0x%X", 
                              $found,
                              $addr, $mask,
                              $ipacl['addr'],  $ipacl['mask']));
*/
      if ($found !== FALSE) {
         break;
      }
}

if ( $found && $GLOBALS['conf']['debug'] )
   syslog(LOG_ERR, sprintf('ACL %s %s@%s %s/%s',
            $found?"success":"failed",
            $name, long2ip($addr),
            $ipacl['addr_a'], $ipacl['mask_a']));

   return ($found)?$ui:FALSE;
}

function parse_dev_acl($dev_acl_str=NULL)
{
   if (empty($dev_acl_str))
      return TRUE; /* any */
   $chunks = explode (',', $dev_acl_str);
   $ret = array();
   foreach ($chunks as &$sub) {
      if ( preg_match('/^\s*(\d+)\s*$/', $sub, $matches) ) {
         $ret[] = (Int)$matches[1];
      } elseif ( preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $sub, $matches) ) {
         $_start = (Int)$matches[1];
         $_stop  = (Int)$matches[2];
         if ( $_start >= $_stop )
            return FALSE;
         $ret = array_merge($ret, range($_start,$_stop));
      } else
         return FALSE;
   }

   if ( FALSE === sort($ret, SORT_NUMERIC) )
      return FALSE;
   return array_unique($ret);
}

function proc_info($proc_name, $__pid=NULL)
{
   $_cmd = $GLOBALS['conf']['ps'].' -o %cpu,%mem,vsz,rss --no-headers ';
   if ($__pid)
      $_cmd .= '-p '.(int)$__pid;
   else
      $_cmd .= '-C '.$proc_name;
   $_cmd .= ' 2>/dev/null';

   exec($_cmd, $lines, $retval);

   if ( $retval !== 0 )
      return false;

   $pids=count($lines);
   $cpu=0;
   for ($i=0; $i< $pids; $i++)
   {
      $kwds = preg_split('/[\s]+/', trim($lines[$i]));
      // var_dump($kwds); echo "<br/>";
      $cpu = $cpu + (float)$kwds[0];
      if ($i===0) {
         $mem = $kwds[1];
         $vsz = $kwds[2];
         $rss = $kwds[3];
      }
   }
   return array((float)$cpu,(float)$mem,(integer)$vsz,(integer)$rss);
}

function filesizeHuman($fsize_KB) {
      if ($fsize_KB >= 1048576 )
               return sprintf("%0.1f %s", $fsize_KB/1048576,$GLOBALS['byteUnits'][3]);
      else if ($fsize_KB >= 1024 )
               return sprintf("%0.1f %s", $fsize_KB/1024,$GLOBALS['byteUnits'][2]);
      else if ($fsize_KB >= 1)
               return sprintf("%d %s", $fsize_KB,$GLOBALS['byteUnits'][1]);
   else 
               return sprintf("%d %s", $fsize_KB*1024,$GLOBALS['byteUnits'][0]);
}

function DeltaTimeHuman($sec) {
settype($sec,'int');
if ($sec >= 86400)
   return sprintf('%.1f дня', $sec / 86400);
else if ($sec >= 3600 )
   return sprintf('%.1f час', $sec / 3600);
else if ($sec >= 60 )
   return sprintf('%.1f мин', $sec / 60);
else
   return sprintf('%d сек', $sec);
}

function getCamName ($_text_left)
{
      if ( empty ($_text_left) )
      return $GLOBALS['strNotTextLeft'];
   else
      return $_text_left;
}
/*
function PrettyCamName($cam_desc=null)
{
	if (empty($cam_desc) || !is_array($cam_desc))
		return '';
}
*/

function print_go_back() {
   print '<br><center><a href="javascript:window.history.back();" title="'.$GLOBALS['strBack'].'">'.
      '<img src="'.$GLOBALS['conf']['prefix'].'/img/undo_dark.gif" alt="'.$GLOBALS['strBack'].
      '" width="24" hspace="24" border="0"></a></center>'."\n";
}


function sql_format_str_val($val) {
   return empty($val)?'NULL':"'".addslashes($val)."'";
}

function sql_format_int_val($val) {
   return empty($val)?'NULL':"'".(Int)$val."'";
}
function sql_format_float_val($val) {
   return empty($val)?'NULL':"'".str_replace(',', '.', $val)."'";
}

function getCamsArray($_sip,$first_defs=FALSE)
{
      /* Performing new SQL query */
      $query = 'SELECT c1.CAM_NR, c1.VALUE as work , c2.VALUE as text_left, '.
      'c1.CHANGE_HOST, c1.CHANGE_USER, c1.CHANGE_TIME '.
      'FROM CAMERAS c1 LEFT OUTER JOIN CAMERAS c2 '.
      'ON ( c1.CAM_NR = c2.CAM_NR AND c1.BIND_MAC=c2.BIND_MAC AND c2.PARAM = \'text_left\' ) '.
      'WHERE c1.BIND_MAC=\'local\' AND c1.CAM_NR>0 AND c1.PARAM = \'work\' '.
      'ORDER BY c1.CAM_NR';
      // print '<p>'.$query.'</p>'."\n";
      $result = mysql_query($query) or die("Query failed");
      $num_rows = mysql_num_rows($result);
      if ( $num_rows == 0 )
   {
               mysql_free_result($result);
      unset ($result);
               return NULL;
   }
   $arr=array();
   if ($first_defs)
      $arr[0]=$GLOBALS['r_cam_defs3'];
      while ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
   {
               $_cam_name = getCamName($row['text_left']);
      $_cam_nr = $row['CAM_NR'];
      settype($_cam_nr,'int');
      $arr[$_cam_nr] = $_cam_name;
   }
      mysql_free_result($result);
      unset ($result);
   return $arr;  
}

function MYDIE($errstr='internal error', $file='', $line='')
{
      if (empty($file))
               $file = basename($_SERVER['SCRIPT_FILENAME']);
      print '<div><font color="'.$GLOBALS['error_color'].'">'."\n";
      printf ('Error in %s:%d<br>',$file, $line);
      print $errstr;
      print '</font></div>'."\n";
   if ( !isset($GLOBALS['NOBODY']) ) 
         print '</body>'."\n";
   print '</html>'."\n";
      exit(1);
}

function print_syslog($priority, $message)
{
      if (!isset($GLOBALS['syslog_opened'])) {
            $GLOBALS['syslog_opened'] = 1;
            define_syslog_variables();
            openlog('avreg-site', 0, LOG_DAEMON);
      }
      if ( $GLOBALS['login_user'] === 'unknown' ) {
            if ( isset($_SERVER['PHP_AUTH_USER']) )
               $luser = $_SERVER['PHP_AUTH_USER'];
            elseif (isset($_SERVER['REMOTE_USER']) )
               $luser = $_SERVER['REMOTE_USER'];
            else $luser = 'unknown';
      } else
               $luser = $GLOBALS['login_user'];
      if ( $GLOBALS['remote_addr'] === 'unknown' )
               $raddr = $_SERVER['REMOTE_ADDR'];
      else
               $raddr = $GLOBALS['remote_addr'];
      syslog($priority, sprintf ('%s@%s: %s', $luser, $raddr, $message));
}

function DENY($good_status=NULL, $http_status=403)
{
   $user_status=$GLOBALS['user_status'];
   if (!is_null($good_status)) {
      if (settype($good_status,'int')) {
         if ($user_status <= $good_status)
            return;
      }
   }

   $aname = &$GLOBALS['conf']['admin-name'];
   $amail = &$GLOBALS['conf']['admin-mail'];
   $atel   = &$GLOBALS['conf']['admin-tel'];
   if ( array_key_exists($user_status,$GLOBALS['grp_ar'])) {
      $deny_reason=sprintf($GLOBALS['access_denided'],
               $GLOBALS['grp_ar'][$user_status]['grname']);
      $action = &$GLOBALS['fmtServerAdmin'];
   } else {
      $deny_reason = sprintf($GLOBALS['fmtAccessDenied'],
                     htmlentities(isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : "unknown user", ENT_QUOTES, $GLOBALS['chset']),
                     $_SERVER['REMOTE_ADDR']);
      $action = &$GLOBALS['fmtTryOnceMore'];
   }

  if (!empty($aname) || !empty($amail) || !empty($atel)) {
    $deny_reason .= '<br /><br />'."\n";
    $deny_reason .= sprintf($action,
           '&#034;'.htmlentities($aname, ENT_QUOTES, $GLOBALS['chset']).'&#034; &#060;'.htmlentities($amail, ENT_QUOTES, $GLOBALS['chset']).'&#062; , tel: '.htmlentities($atel, ENT_QUOTES, $GLOBALS['chset']));
  }

   if (!headers_sent()) {
      switch ($http_status) {
         case 401:
            header('WWW-Authenticate: Basic realm="AVReg server"', true, 401);
            exit();

         case 403:
         default:
            header('Content-Type: text/html; charset=' . $chset);
            header('Connection: close', true, 403);
      }
   }

   print_syslog(LOG_CRIT, 'access denided: '. basename($_SERVER['SCRIPT_FILENAME']));
   print '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'."\n";
   print '<html><head>'."\n";
   print '<link rel="SHORTCUT ICON" href="'.$conf['prefix'].'/favicon.ico">'."\n";
   print '<title>403 error::Access Denided</title>'."\n";
   print '<meta http-equiv="Content-Type" content="text/html; charset='.$GLOBALS['chset'].'">'."\n";
   print "</head><body>\n";
   print '<img src="'.$GLOBALS['conf']['prefix'].'/img/password.gif" width="48" height="48" border="0"><p><font color="red" size=+1>'.$deny_reason.'</font></p>'."\n";
   print "</body></html>\r\n";
   exit();
}


function checkIntRange ($int, $min, $max)
{
if ( $int == NULL or $h < $min or $h > $max) {
   return FALSE;
} else {
   return TRUE;
}
}

function getMonth ($m)
{
if (empty ($m) or (($m < 0) or ($m > 12))) {
   $retval = $GLOBALS['charNull'];
} else {
   $retval = $GLOBALS['month_array'][$m-1];
}
return $retval;
}

function getDay ($d)
{
if (empty ($d) or (($d < 1) or ($d > 31))) {
   $retval = $GLOBALS['charNull'];
} else {
   $retval = sprintf ('%02u' ,$d);
}
return $retval;
}

function getHour ($h)
{
if ( $h == NULL or $h < 0 or $h > 23) {
   $retval = $GLOBALS['charNull'];
} else {
   $retval = sprintf ('%02u' ,$h);
}
return $retval;
}

function getMinute ($_min)
{
if ( $_min == NULL or $_min < 0 or $_min > 59 ) {
   $retval = $GLOBALS['charNull'];
} else {
   $retval = sprintf ('%02u' ,$_min);
}
return  $retval;
}

function getWeekday ($_weekday)
{
$retval = '';
if ($_weekday == '') {
   $retval = $GLOBALS['charNull'];
} else {
   $pieces = explode(",", $_weekday);
   foreach ($pieces as $_day) {
      $rrr[] = $GLOBALS['day_of_week'][$_day];
   }
   $retval = implode (",",$rrr);
}
return $retval;
}

function getSelectHtml($_name, $value_array, $_multiple=FALSE , $_size = 1, $start_val=1, $selected='', $first_empty=TRUE, $onch=FALSE, $TITLE=NULL)
{
      settype($selected,'string');
      if ($_multiple) {$m = 'multiple="multiple"';} else {$m = '';}
      if ($onch===TRUE)
      $onch = 'onchange="this.form.submit()"';
   else if (!empty($onch))
      $onch = 'onchange="'.$onch.'"';
   else
      $onch='';
   if (!empty($TITLE))
      $_title='title="'.$TITLE.'"';
   else
      $_title='';
   $a = sprintf('<select name="%s" id="%s" %s size="%d" %s %s>'."\n",
                  $_name,
                  $_name,
                  $m,
                  $_size,
                  $_title,
                  $onch);

      if ($first_empty) $a .= '<option></option>'."\n";
      $_cnt = $start_val;
      foreach ($value_array as $_element)
      {
               if ( $selected != '' )
               {
                        if ($_multiple)
                        {
                              $_y = FALSE;
                              $ar = explode(',', $selected);
                              foreach ($ar as $sss)
                              {
                                       if ($_cnt == $sss)
                                       {
                                                $_y = TRUE;
                                                break;
                                       }
                              }
                              if ($_y)
                                       $a .= '<option value="'.$_cnt.'" selected>'.$_element.'</option>'."\n";
                              else
                                       $a .= '<option value="'.$_cnt.'">'.$_element.'</option>'."\n";
                        } else {
                        /* not multiple */
                              if ($_element == $selected)
                                       $a .= '<option value="'.$_cnt.'" selected>'.$_element.'</option>'."\n";
                              else
                                       $a .= '<option value="'.$_cnt.'">'.$_element.'</option>'."\n";
                        }
               } else {  // not selected
                        $a .= '<option value="'.$_cnt.'">'.$_element.'</option>'."\n";
               }
               $_cnt++;
      }
      $a .= '</select>'."\n";
      return $a;
}

function getSelectHtmlByName($_name, $value_array, $_multiple=FALSE ,
                                                               $_size = 1, $start_val=0, $selected='',
                                                               $first_empty=TRUE, $onch=FALSE, $text_prefix = '',$TITLE=NULL)
{
      if ($_multiple) {$m = 'multiple="multiple"';} else {$m = '';}
   if ($onch===TRUE)
      $onch = 'onchange="this.form.submit()"';
   else if (!empty($onch))
      $onch = 'onchange="'.$onch.'"';
   else
      $onch='';
   if (!empty($TITLE))
      $_title='title="'.$TITLE.'"';
   else
      $_title='';
   $a = sprintf('<select name="%s" id="%s" %s size="%d" %s %s>'."\n",
                  $_name,
                  $_name,
                  $m,
                  $_size,
                  $_title,
                  $onch);

      if ($first_empty) $a .= '<option></option>'."\n";
      $_cnt = $start_val;
      foreach ($value_array as $_element)
      {
               if ( $selected != '' )
               {
                        if ($_multiple)
                        {
                              $_y = FALSE;
                              $ar = explode(',', $selected);
                              foreach ($ar as $sss)
                              {
                                       if ($_cnt == $sss)
                                       {
                                                $_y = TRUE;
                                                break;
                                       }
                              }
                              if ($_y)
                                       $a .= '<option value="'.$_element.'" selected>'.$text_prefix.$_element.'</option>'."\n";
                              else
                                       $a .= '<option value="'.$_element.'">'.$text_prefix.$_element.'</option>'."\n";
                        } else {
                        /* not multiple */
                              if ($_element == $selected)
                                       $a .= '<option value="'.$_element.'" selected>'.$text_prefix.$_element.'</option>'."\n";
                              else
                                       $a .= '<option value="'.$_element.'">'.$text_prefix.$_element.'</option>'."\n";
                        }
               } else {  // not selected
                        $a .= '<option value="'.$_element.'">'.$text_prefix.$_element.'</option>'."\n";
               }
               $_cnt++;
      }
      $a .= '</select>'."\n";
      return $a;
}

function getSelectByAssocAr($_name, $assoc_array, $_multiple=FALSE ,
                            $_size = 1, $start_val=NULL, $selected=NULL,
                            $first_empty=TRUE, $onch=FALSE,
                            $text_prefix = NULL,$TITLE=NULL, 
									 $reverse=false)
{
  $array_cnt = count($assoc_array);
  if ( $array_cnt == 0 ) return '';
  if ($_multiple) {$m = 'multiple="multiple"';} else {$m = '';}
  if ($onch===TRUE)
    $onch = 'onchange="this.form.submit()"';
  else if (!empty($onch))
    $onch = 'onchange="'.$onch.'"';
  else
    $onch='';
  if (!empty($TITLE))
     $_title='title="'.$TITLE.'"';
  else
     $_title='';
  $a = sprintf('<select name="%s" id="%s" %s size="%d" %s %s>'."\n",
                  $_name,
                  $_name,
                  $m,
                  $_size,
                  $_title,
                  $onch);

  if ($first_empty) $a .= '<option></option>'."\n";

	foreach ($assoc_array as $k => $v)
	{
		settype($key,'string');
		if ( $reverse ) {
			$key = &$v;
			$value = &$k;
		} else {
			$key = &$k;
			$value = &$v;			
		}

     if ( $selected != '' )
     {
       if ($_multiple)
       {
          $_y = FALSE;
          $ar = explode(',', $selected);
          foreach ($ar as $sss)
          {
             if ($key == $sss)
             {
                $_y = TRUE;
                break;
             }
          }
          if ($_y)
             $a .= '<option value="'.$key.'" selected>'.$text_prefix.$value.'</option>'."\n";
          else
             $a .= '<option value="'.$key.'">'.$text_prefix.$value.'</option>'."\n";
          } else {
             /* not multiple */
             if ($key == $selected)
                $a .= '<option value="'.$key.'" selected>'.$text_prefix.$value.'</option>'."\n";
             else
                $a .= '<option value="'.$key.'">'.$text_prefix.$value.'</option>'."\n";
             }
           } else {  // not selected
              $a .= '<option value="'.$key.'">'.$text_prefix.$value.'</option>'."\n";
           }
  }

  $a .= '</select>'."\n";
  return $a;
}


function getBinString($bin_str)
{
   if ( strlen ($bin_str) == 0 ) return $bin_str;
   $ret = str_replace("\x00", '\0', $bin_str);
   $ret = str_replace("\x08", '\b', $ret);
   $ret = str_replace("\x0a", '\n', $ret);
   $ret = str_replace("\x0d", '\r', $ret);
   $ret = str_replace("\x1a", '\Z', $ret);
   return $ret;
}

function check_passwd($saved_pw, $pw)
{
   if ( $saved_pw == '' && $pw == '')
      return;

   $z = crypt($saved_pw, $pw);
   if ( $z !== $pw )
      DENY(null,401);
}

if ( isset($_SERVER['PHP_AUTH_USER']))
{
   require_once($wwwdir . 'lib/utils-inet.php');
   if (!isset($_SERVER['REMOTE_USER']))
   $_SERVER['REMOTE_USER'] = $_SERVER['PHP_AUTH_USER'];

$user_info = avreg_find_user(ip2long($_SERVER['REMOTE_ADDR']), -1, $_SERVER['PHP_AUTH_USER']);
if ($user_info !== FALSE)
   check_passwd($_SERVER['PHP_AUTH_PW'], $user_info['PASSWD']);
else
   DENY(null,403);

$login_user = $_SERVER['REMOTE_USER'];
$user_status = $user_info['STATUS'];
$login_user_name = $row['LONGNAME'];
$login_host = &$remote_addr;
$allow_cams = parse_dev_acl($user_info['ALLOW_CAMS']);
if ( is_array($allow_cams) && count($allow_cams) > 0 )
   $GCP_cams_list = @implode(',', $allow_cams);
else
   $GCP_cams_list = NULL;
if ( $user_status <= $install_status ) $install_user = true;
if ( $user_status <= $admin_status ) $admin_user = true;
if ( $user_status <= $arch_status ) $arch_user = true;
if ( $user_status <= $operator_status ) $operator_user = true;
} else {
DENY(null,401);
}

?>
