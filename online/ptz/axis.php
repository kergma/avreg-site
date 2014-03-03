<?php
/**
 * @file online/ptz/axis.php
 * @brief AXIS camera PTZ handler
 */

$pageTitle = 'AXIS PTZ';
$ptz_caps=array('pan','tilt','zoom','home','stop');
$movements=array(
	'pan_step'=>10,'pan_fast'=>30,
	'tilt_step'=>5,'tilt_fast'=>15,
	'zoom_step'=>500,'zoom_fast'=>1500,
);

include "ptzi.inc.php";
class AXIS extends PTZi
{
	function get_bounds()
	{
		$re=file_get_contents("$this->camurl/axis-cgi/com/ptz.cgi?query=limits");
		preg_match_all('/([^=\s]+)=([^=\s]+)/',$re,$r);
		$r=array_combine($r[1],array_map('floatval',$r[2]));
		return array('pan_start'=>$r['MinPan'],'pan_end'=>$r['MaxPan'],'tilt_start'=>$r['MinTilt'],'tilt_end'=>$r['MaxTilt'],'zoom_start'=>$r['MinZoom'],'zoom_end'=>$r['MaxZoom']);
	}
	function get_pos()
	{
		$re=file_get_contents("$this->camurl/axis-cgi/com/ptz.cgi?query=position");
		preg_match_all('/([^=\s]+)=([^=\s]+)/',$re,$r);
		$r=array_combine($r[1],array_map('floatval',$r[2]));
		return array('pan'=>$r['pan'],'tilt'=>$r['tilt'],'zoom'=>$r['zoom']);

	}
	function pan($value)
	{
		$value=preg_replace('/,/','.',$value);
		$re=file_get_contents("$this->camurl/axis-cgi/com/ptz.cgi?pan=$value");
		print $re;
	}
	function tilt($value)
	{
		$value=preg_replace('/,/','.',$value);
		$re=file_get_contents("$this->camurl/axis-cgi/com/ptz.cgi?tilt=$value");
		print $re;
	}
	function zoom($value)
	{
		$value=preg_replace('/,/','.',$value);
		$re=file_get_contents("$this->camurl/axis-cgi/com/ptz.cgi?zoom=$value");
		print $re;
	}
};

$ptzi=new AXIS();
$ptzi->movements=$movements;
include "common.inc.php";
