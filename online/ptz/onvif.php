<?php
/**
 * @file online/ptz/onvif.php
 * @brief onvif-based PTZ handler
 */

$pageTitle = 'Onvif PTZ';
require('common.inc.php');

if (empty($cam_nr)) {
    die('cam_nr is empty');
}
$GCP_cams_list="$cam_nr";
$GCP_query_param_list = array(
    'text_left',
    'InetCam_IP',
    'InetCam_http_port',
    'InetCam_USER',
    'InetCam_PASSWD'
);
require('../../lib/get_cams_params.inc.php');
$cam_http_params = array_merge($GCP_def_pars, $GCP_cams_params[$cam_nr]);
/*
$cam_http_params example
array(5) {
  ["text_left"]=> string(30) "Камера3-эмулятор"
  ["InetCam_IP"]=> string(9) "127.0.0.1"
  ["InetCam_USER"]=> NULL
  ["InetCam_PASSWD"]=> NULL
  ["InetCam_http_port"]=> string(5) "60001"
}
*/
?>

<div class="ptz_area_right">
     <p>ptz bottom content</p>
</div>

<div class="ptz_area_bottom">
     <p>ptz bottom content</p>
</div>
