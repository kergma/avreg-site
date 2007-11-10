<?php
$pageTitle = 'PrName';
require ('./head.inc.php');

error_reporting(E_ALL);
$evt_shell='/usr/local/sbin/http-event.sh';

/* переводим QUERY string в параметры */
if ( !isset($_REQUEST) || !is_array($_REQUEST) ) 
   die("script need params");
   
reset($_REQUEST);
$cmd_params='';
while (list($key, $val) = each($_REQUEST)) {
    $_key = addslashes($key);
    $_val = addslashes($val);
    if (FALSE !== strpos($_key,' '))
      $_key = '\''.$_key.'\'';
    if (FALSE !== strpos($_val,' '))
      $_val = '\''.$_val.'\'';
    $cmd_params .= ' '.$_key.'='.$_val;
}
print_syslog(LOG_INFO, 'http-event with params'. $cmd_params);
$retval = -1;
system('/usr/bin/nohup ' .  $conf['sudo'] .' '. $evt_shell.$cmd_params .'>/dev/null 2>/dev/null &', $retval);

if ($retval !== 0) {
  print_syslog(LOG_ERR, $evt_shell . ' failed with code ' . $retval);
  die();
}

require ('./foot.inc.php');
?>
