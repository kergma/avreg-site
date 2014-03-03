<?php
/**
 * @file online/ptz/ptzi.inc.php
 * @brief Base class for camera PTZ interface
 */
class PTZi
{
	var $camurl;
	function get_bounds()
	{
		return array();
	}
	function get_pos()
	{
		return array();
	}
	function pan($value)
	{
	}
	function tilt($value)
	{
	}
	function zoom($value)
	{
	}
	function focus($value)
	{
	}
	function home($action)
	{
	}
	function move($direction,$mode)
	{
		if ($direction=="home") return $this->home('go');
		$pos=$this->get_pos();
		if ($direction=="left") $this->pan($pos['pan']-$this->movements[$mode]);
		if ($direction=="right") $this->pan($pos['pan']+$this->movements[$mode]);
		if ($direction=="up") $this->tilt($pos['tilt']+$this->movements[$mode]);
		if ($direction=="down") $this->tilt($pos['tilt']-$this->movements[$mode]);
		if ($direction=="wide") $this->zoom($pos['zoom']-$this->movements[$mode]);
		if ($direction=="tele") $this->zoom($pos['zoom']+$this->movements[$mode]);
		if ($direction=="close") $this->zoom($pos['focus']-$this->movements[$mode]);
		if ($direction=="far") $this->zoom($pos['focus']+$this->movements[$mode]);
	}
};

?>
