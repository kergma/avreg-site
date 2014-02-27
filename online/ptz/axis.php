<?php
/**
 * @file online/ptz/acti.php
 * @brief ACTi camera PTZ handler
 */

$pageTitle = 'AXIS PTZ';
$ptz_caps=array('pan','tilt','zoom','home','stop');

function get_cam_bounds($camurl)
{
	return array('pan_start'=>1,'pan_end'=>300,'tilt_start'=>0,'tilt_end'=>100,'zoom_start'=>1,'zoom_end'=>20);

}
function get_cam_pos($camurl)
{
	return array('pan'=>rand(0,300),'tilt'=>rand(0,100),'zoom'=>rand(0,20));

}
