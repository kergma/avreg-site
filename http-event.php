<?php

/* need for auth */
require_once('lib/config.inc.php');

/**
 * Send http headers
 */
// Don't use cache (required for Opera)
$now = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: ' . $now);
header('Last-Modified: ' . $now);
header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0
// Define the charset to be used
header('Content-Type: text/html; charset=ISO-8859-1');

echo "<html><body>\r\n";

/* lookup camera's number from database */
$GCP_query_param_list = array(/*'work',*/'cam_type','text_left','InetCam_IP');
require ('lib/get_cams_params.inc.php');
if ( isset($GCP_cams_params) && is_array($GCP_cams_params) ) {
  reset($GCP_cams_params);
  while (list($_cam_nr, $CAM_PARAMS) = each($GCP_cams_params)) {
    if ( 0 === strcmp($CAM_PARAMS['InetCam_IP'], $_SERVER["REMOTE_ADDR"]) ) {
       /* define CAM_NR var */
      $CAM_NR  = $_cam_nr;
      break;
    }
  }
}

$cmd_params='';
if ( isset($_REQUEST) ) {
  reset($_REQUEST);
  while (list($key, $val) = each($_REQUEST)) {
    $_key = addslashes($key);
    $_val = addslashes($val);
    if (FALSE !== strpos($_key,' '))
      $_key = '\''.$_key.'\'';
    if (FALSE !== strpos($_val,' '))
      $_val = '\''.$_val.'\'';
    if ( !empty($cmd_params) )
      $cmd_params .= ' ';
    $cmd_params .= $_key.'='.$_val;
  }
}


if ( isset($CAM_NR) )
  print_syslog(LOG_NOTICE,
     sprintf('cam[%u]: received http notify: %s',
       $CAM_NR, $cmd_params));
else
  print_syslog(LOG_ERR,
     sprintf('received foreign http notify: %s',
       $CAM_NR, $cmd_params));

/* include user scripts */
if (!empty($conf['on-http-events']))
  @include ($conf['on-http-events']);

echo "<h1>Received!</h1>\r\n</body></html>\r\n";
?>
