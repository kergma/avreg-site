<?php

$PrNameEng = 'AVReg';
require ('/etc/avreg/site-defaults.php');
$wwwdir = '/usr/share/avreg-site/';
// $wwwdir = '/home/nik/linuxdvr/html/html-5/';

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
require ($wwwdir . 'lib/grab_globals.lib.php');

function confparse($section=NULL, $path='/etc/avreg/avreg.conf')
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
   
      if ( preg_match('/^[;#]/', $line) )
         continue;
   
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

      if ( preg_match('/^([^\s=]+)[\s="\']*([^\s"\']*)["\']*$/',$line, $matches))
      {
         $param=$matches[1];
         $value=$matches[2];
         // нашли параметр
         // echo ("file $path:$linenr => $param = \"$value\"\n");
         if ( 0 === strcasecmp($param, 'include') ) {
           // вложенный файл 
           $res = confparse($section, $value);
           if (!$res) {
              echo "ERROR INCLUDE FILE \"$value\" from $path:$linenr\n";
              $res=false;
              break;
           } else {
             $ret_array = array_merge($ret_array, $res);
           }
         } else {
            $ret_array[$param] = $value;
         }
      } else { 
         // invalid pair
         echo ("INVALID LINE in file $path:$linenr => \"$line\"\n");
         $res=false;
         break;
      }


   } // while eof
   
   fclose($confile);
   return ($res)?$ret_array:$res;
}

$res=confparse('avreg-site');
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

// tohtml($conf);



$sip = $_SERVER['SERVER_ADDR'];
if ( $_SERVER['SERVER_ADDR'] === $_SERVER['SERVER_NAME'] )
  $named = $_SERVER['SERVER_ADDR'];
else
  $named = $_SERVER['SERVER_NAME'];

$localip = ip2long($sip);

$login_user = 'unknown';
$login_user_name = 'unknown';
$remote_addr = 'unknown';
$login_host = 'unknown';


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
$lang_module_name2  = $lang_dir . str_replace ('/', '_', $pt);

$params_module_name = $lang_dir . 'params.inc.php';

require ($lang_module_name);
if (file_exists ($lang_module_name2))
   require ($lang_module_name2);

$remote_addr = $_SERVER['REMOTE_ADDR'];
if ( $remote_addr === '127.0.0.1' )
  $remote_addr = 'localhost';

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
$patternAllowedIP='/^('.$patternIP.'|any|localhost)$/';
$patternUser='/^[A-Za-z0-9_\-]{4,16}$/';
$patternPasswd='/^[A-Za-z0-9_\-]{0,16}$/';

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

function UnixTime($mysql_time) {
   list($_year, $_month, $_day, $_hour, $_min, $_sec) = sscanf($mysql_time, "%4d%2d%2d%2d%2d%2d");
   return mktime ($_hour, $_min, $_sec, $_month, $_day, $_year);

}

function getCamName ($_text_left)
{
	if ( empty ($_text_left) )
    	return $GLOBALS['strNotTextLeft'];
    else
    	return $_text_left;
}

function print_go_back() {
print '<br><center><a href="javascript:window.history.back();" title="'.$GLOBALS['strBack'].'">'.
'<img src="'.$conf['prefix'].'/img/undo_dark.gif" alt="'.$GLOBALS['strBack'].
'" width="24" hspace="24" border="0"></a></center>'."\n";
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

function tohtml($var)
{
	print '<div class="dump"><pre class="dump">'."\n";
	var_dump($var);
	print '</pre></div>'."\n";
}

function print_syslog($priority, $message, $additional='')
{
	define_syslog_variables();
	// open syslog, include the process ID and also send
	// the log to standard error, and use a user defined
	// logging mechanism
	openlog($GLOBALS['PrNameEng'].'['.$_SERVER['SERVER_ADDR'].']', LOG_CONS, LOG_USER);
	if ( $GLOBALS['login_user'] === 'unknown' )
		$luser = isset($_SERVER['REMOTE_USER'])?$_SERVER['REMOTE_USER']:'unknown';
	else
		$luser = $GLOBALS['login_user'];
	if ( $GLOBALS['remote_addr'] === 'unknown' )
		$raddr = $_SERVER['REMOTE_ADDR'];
	else
		$raddr = $GLOBALS['remote_addr'];
	if (empty($additional))
		$str = sprintf ('%s, user `%s[%s]` from host `%s`', 
					$message,
					$luser,
					$GLOBALS['login_user_name'],
					$raddr);
	else
		$str = sprintf ('%s, user `%s[%s]` from host `%s`, [%s]',
					$message,
					$luser,
					$GLOBALS['login_user_name'],
					$raddr,
					$additional);
	syslog($priority, $str);
	closelog();
}

function DENY($good_status=NULL)
{
   $deny=TRUE;
   $user_status=$GLOBALS['user_status'];
   if ( array_key_exists($user_status,$GLOBALS['grp_ar']))
      $gr_name=$GLOBALS['grp_ar'][$user_status]['grname'];
   else
      $gr_name = 'unknown'; 
    if (!is_null($good_status))
    {
       if (settype($good_status,'int'))
       {
          if ($user_status <= $good_status)
            $deny=FALSE;
       }
    }
    
    if ($deny) 
    {
/*
if (!headers_sent()) {
	header('WWW-Authenticate: Basic realm="LinuxDVR VideoServ"', TRUE);
	header('HTTP/1.0 401 Authorization Required');
}
*/ 
       $a=sprintf($GLOBALS['access_denided'],$gr_name);
       print_syslog(LOG_CRIT, 'access denided: '.basename($_SERVER['SCRIPT_FILENAME']));
       print('<img src="'.$GLOBALS['conf']['prefix'].'/img/password.gif" width="48" height="48" border="0">'.
             '<p><font color="red" size=+1>'.$a.'</font></p>'.
             '</body></html>');
//			   $GLOBALS['access_denided'], basename($_SERVER['SCRIPT_FILENAME']) );
       exit();
    }        
}


function GetCamPar($_param, $_cam_nr=-1)
{
	$def_value = NULL;
	$value = NULL;
	$val_type =NULL;
	$ret = NULL;
	// читаемт значение по умолчанию
	$sql = sprintf('SELECT VAL_TYPE, DEF_VALUE AS VALUE FROM PARAMS WHERE PARAM=\'%s\'', $_param);
	$res = mysql_query($sql) or die('Query failed:`'.$sql.'`');
	$row = mysql_fetch_array($res, MYSQL_ASSOC);
	$def_value = $row['VALUE'];
	$val_type = $row['VAL_TYPE'];
	mysql_free_result($res);
	// читаем параметры камеры
	$sql = sprintf('SELECT VALUE FROM CAMERAS WHERE PARAM=\'%s\' AND CAM_NR=%d', $_param, $_cam_nr);
	$res = mysql_query($sql) or die('Query failed:`'.$sql.'`');
	$row = mysql_fetch_array($res, MYSQL_ASSOC);
	$value = $row['VALUE'];
	mysql_free_result($res);
	
	if ( is_null($value) )
		$ret =  $def_value;
	else
		$ret = $value;

	if ( !is_null($ret) )
	{
		switch ( $val_type )
		{
			case 'INT':
				settype($ret, 'int');
				break;
			case 'BOOL':
				settype($ret, 'bool');
				break;
			default:
				settype($ret, 'string');
		}
	}
	return  $ret;
}

function CheckParVal($_param, $_value, $_val_type, $_cam_nr=-1)
{
	$ret = TRUE;
	if ( $_value === '' || is_null($_value) )
       return $ret;
	switch ( $_val_type )
	{
			case 'INT':
			if ( !is_numeric($_value) )
			{
				$ret = FALSE;
				$str = '';
			}
			break;
			case 'BOOL':
				if ( !($_value == '0' || $_value == '1'))
				{
					$ret = FALSE;
					$str = '';
				}
				break;
	}
	if ( !$ret ) 
		print '<font color="'.$GLOBALS['error_color'].'"><b><p>'. sprintf($GLOBALS['strParInvalid'], $_value, $_param) .'</p></b></font>'."\n";

	return $ret;
}

function checkIntRange ($int, $min, $max)
{
  if ( $int == NULL or $h < $min or $h > $max) {
     return FALSE;
  } else {
     return TRUE;
  }
}

function getSQLVal ($par)
{
  $a = getParVal ($par);
  if ( $a == NULL ) {
    return 'NULL';
  } else {
    return "'$a'";
  }
}

function getParVal ($par)
{
  $a = explode('&', $_SERVER['QUERY_STRING']);
  $i = 0; $j = 0;
  $retval = FALSE;
  while ($i < count($a)) {
      $b = split('=', $a[$i]);
      if (!strcasecmp($par, htmlspecialchars(urldecode($b[0])))) {
        $rrr[] = htmlspecialchars(urldecode($b[1]));
      }
      $i++;
  }
  $retval = implode (',', (array)$rrr);
  return $retval;
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
	reset($value_array);
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
	reset($value_array);
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
								 $first_empty=TRUE, $onch=FALSE, $text_prefix = NULL,$TITLE=NULL)
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
    
    reset($assoc_array);
    for ($i=0; $i<$array_cnt; $i++)
	{
		list($key, $value) = each($assoc_array);
		settype($key,'string');
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
  /* $ret = htmlspecialchars($ret); */
  return $ret;
}

function check_passwd($saved_pw, $pw)
{
   if ( $saved_pw == '' && $pw == '')
      return;

   $z = crypt($saved_pw, $pw);
   if ( $z !== $pw ) {
      header('WWW-Authenticate: Basic realm="AVReg server"');
      header('HTTP/1.0 401 Unauthorized');
      DENY();
   }
}

if ( isset($_SERVER['PHP_AUTH_USER']))
/* isset($_SERVER['AUTH_TYPE']) && isset($_SERVER['REMOTE_USER']*/
{
  if (!isset($_SERVER['REMOTE_USER']))
    $_SERVER['REMOTE_USER'] = $_SERVER['PHP_AUTH_USER'];

  require_once($wwwdir.'/lib/my_conn.inc.php');

  if ( $_SERVER['REMOTE_ADDR'] === '127.0.0.1' )
    $query = sprintf('SELECT PASSWD, STATUS, LONGNAME '.
                     'FROM USERS '.
                     'WHERE ( HOST=\'127.0.0.1\' OR HOST=\'localhost\') '.
                     'AND USER = \'%s\'',
                     $_SERVER['REMOTE_USER']);
  else
    $query = sprintf('SELECT PASSWD, STATUS, LONGNAME '.
                     'FROM USERS '.
                     'WHERE HOST=\'%s\' AND USER = \'%s\'',
                     $_SERVER['REMOTE_ADDR'], $_SERVER['REMOTE_USER']);
  $result = mysql_query($query) or die('Query failed: `'. $query . "`\n");
  /* printf($query."\n"); */
  if ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
  {
    /* var_dump($row); */
    $login_user = $_SERVER['REMOTE_USER'];
    $user_status = $row['STATUS'];
    settype($user_status,'int');
    $login_user_name = $row['LONGNAME'];
    $login_host = &$remote_addr;
    if ( $user_status <= $install_status ) $install_user = true;
    if ( $user_status <= $admin_status ) $admin_user = true;
    if ( $user_status <= $arch_status ) $arch_user = true;
    if ( $user_status <= $operator_status ) $operator_user = true;
    check_passwd($_SERVER['PHP_AUTH_PW'], $row['PASSWD']);
  } else {
    mysql_free_result($result); $result = NULL;
    $query = sprintf('SELECT PASSWD, STATUS, LONGNAME '.
                     'FROM USERS '.
                     'WHERE HOST=\'any\' AND USER = \'%s\'',
                     $_SERVER['REMOTE_USER']);
    $result = mysql_query($query) or die('Query failed: `'. $query . "`\n");
    /* printf($query."\n"); */
    if ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
    {
      /* var_dump($row); */
      $login_user = $_SERVER['REMOTE_USER'];
      $user_status = $row['STATUS'];
      $login_user_name = $row['LONGNAME'];
      $login_host = 'any';
      check_passwd($_SERVER['PHP_AUTH_PW'], $row['PASSWD']);
      if ( $user_status < 2 ) $install_user = true;
      if ( $user_status < 3 ) $admin_user = true;
      if ( $user_status < 4 ) $arch_user = true;
      if ( $user_status < 5 ) $operator_user = true;
    } else {
      mysql_free_result($result); $result = NULL;
      mysql_close($link); $link = NULL;
      DENY();
    }
  }
} else {
  header('WWW-Authenticate: Basic realm="AVReg server"');
  header('HTTP/1.0 401 Unauthorized');
  DENY();
}

if (isset($result) ) {
	mysql_free_result($result);
	$result = NULL;
}

?>
