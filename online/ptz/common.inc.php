<?php
/**
 * @file online/ptz/common.inc.php
 * @brief common firstly php-includes for all PTZ handler
 */

session_start();
if (isset($_SESSION['is_admin_mode'])) {
    unset($_SESSION['is_admin_mode']);
}

$NO_OB_END_FLUSH = true; // for setcookie()
if (empty($pageTitle)) {
    $pageTitle = 'PTZ';
}
$body_style = 'overflow: hidden;  overflow-y: hidden !important; padding: 0; margin: 0; width: 100%; height: 100%;';
/*
$css_links = array(
    'lib/js/third-party/jqModal.css',
    'online/online.css'
 );
*/
$USE_JQUERY = true;
$link_javascripts = array(
    'lib/js/third-party/jquery.mousewheel.min.js',
    'lib/js/third-party/json2.js'
);

$body_addons = 'scroll="no"';
$ie6_quirks_mode = true;
$IE_COMPAT='10';
if (empty($lang_file)) {
    $lang_file = '_ptz.php';
}

require_once('../../lib/config.inc.php');

$GCP_cams_list=$_REQUEST['cam_nr'];
$GCP_query_param_list = array(
    'text_left',
    'InetCam_IP',
    'InetCam_http_port',
    'InetCam_USER',
    'InetCam_PASSWD',
);
require('../../lib/get_cams_params.inc.php');
$cam_http_params = array_merge($GCP_def_pars, $GCP_cams_params[$cam_nr]);

if (!empty($_REQUEST['get']))
{
	if (!isset($ptzi)) {
		include_once("ptzi.inc.php");
		$ptzi=new PTZi();
	};
	$ptzi->camurl="http://{$cam_http_params['InetCam_USER']}:{$cam_http_params['InetCam_PASSWD']}@{$cam_http_params['InetCam_IP']}:{$cam_http_params['InetCam_http_port']}";
	$result=array();
	$g=explode(',',$_REQUEST['get']);

	if (in_array('ptzf_bounds',$g)) $g=array_merge($g,array('pan_bounds','tilt_bounds','zoom_bounds','focus_bounds'));
	if (preg_grep('/_bounds$/',$g)) $r=$ptzi->get_bounds();
	if (in_array('pan_bounds',$g)) cpkeys($result,$r,'pan_start','pan_end');
	if (in_array('tilt_bounds',$g)) cpkeys($result,$r,'tilt_start','tilt_end');
	if (in_array('zoom_bounds',$g)) cpkeys($result,$r,'zoom_start','zoom_end');
	if (in_array('focus_bounds',$g)) cpkeys($result,$r,'focus_start','zoom_end');

	if (in_array('ptzf',$g)) $g=array_merge($g,array('pan','tilt','zoom','focus'));
	if (array_intersect(array('pan','tilt','zoom','focus'),$g)) $r=$ptzi->get_pos();
	if (in_array('pan',$g)) cpkeys($result,$r,'pan','pan');
	if (in_array('tilt',$g)) cpkeys($result,$r,'tilt','tilt');
	if (in_array('zoom',$g)) cpkeys($result,$r,'zoom','zoom');
	if (in_array('focus',$g)) cpkeys($result,$r,'focus','zoom');

	print json_encode($result);
	exit;
};
function is_capable($cap)
{
	global $ptz_caps;
	return (in_array($cap,$ptz_caps) and !isset($ptz_caps[$cap])) or !empty($ptz_caps[$cap]);
}
function cpkeys(&$dest,$src)
{

	for ($i=2;$i<func_num_args();$i++)
		if (isset($src[func_get_arg($i)])) $dest[func_get_arg($i)]=$src[func_get_arg($i)];
}
?>
<script type="text/javascript">
var $win;
var $cam_nr;

$(function() {
	
	$cam_nr=<?php echo $_REQUEST['cam_nr']?>;
	$win=window.ptztemp_win;
	delete window.ptztemp_win;
	$('button img',$win).height('7px');
	$('button',$win).css('padding','0px');
	//$('button',$win).css('border','0px');
	$('td',$win).css('padding','0px');
	$('button',$win).click(function(e){
		e.stopPropagation();
	});
	$.get('ptz/<?php print basename($_SERVER['SCRIPT_NAME']) ?>',{cam_nr:$cam_nr,get:'ptzf_bounds,ptzf'},function(data){
		$('.ptz-slider.pan',$win).slider({ min:data.pan_start, max:data.pan_end, value:data.pan});
		$('.ptz-slider.tilt',$win).slider({ min:data.tilt_start, max:data.tilt_end, value:data.tilt, orientation: 'vertical'});
		$('.ptz-slider.zoom',$win).slider({ min:data.zoom_start, max:data.zoom_end, value:data.zoom});
		$('.ptz-slider.focus',$win).slider({ min:data.focus_start, max:data.focus_end, value:data.focus});
		$('.ptz-slider',$win).slider({
			change: function (event, ui) {
				var v=$(this).slider('value');
				window.document.title=v;
			},

		});
		//$('.ptz-slider.pan',$win).slider({ range:min});
	},'json');
});

</script>

<div class="ptz_area_right">
<table height="100%">
<tr>
<?php if (is_capable('tilt')) {?>
<td height="10px"><button><img src="ptz/u.png"></img></button></td>
<?php };?>
<?php if (is_capable('home')) {?>
<td rowspan="2" style="vertical-align:top">
<div class=".ptz-home">
<button>HOME</button>
<div style="display:inline-block"> <button><img src="ptz/sh.png"></button> <button><img src="ptz/rh.png"></button> </div>
</div><!-- ptz-home -->
</td>
<?php };?>
</tr>
<?php if (is_capable('tilt')) {?>
<tr>
<td height="10px"><button><img src="ptz/uu.png"></img></button>
</tr>
<tr>
<td height="100%" style="text-align:center"> <div class="ptz-slider tilt" style="height:90%"/></td>
</tr>
<tr>
</tr>
<tr>
<td height="10px"><button><img src="ptz/dd.png"></button>
</tr>
<tr>
<td height="10px"><button><img src="ptz/d.png"></button>
</tr>
<?php };?>
</table>
</div><!-- ptz_area_right -->

<div class="ptz_area_bottom">

<table width="100%" height="100%">
<tr height="100%">
<td height="100%">
<table width="100%" height="100%">
<?php if (is_capable('pan')) {?>
<tr>
<td width="10px"><button><img src="ptz/l.png"></button></td>
<td width="10px"><button><img src="ptz/ll.png"></button></td>
<td><div class="ptz-slider pan" style="margin-left:10px;margin-right:10px"/></td>
<td width="20px"><button><img src="ptz/rr.png"></button></td>
<td width="20px"><button><img src="ptz/r.png"></button></td>
</tr>
<?php };?>
<?php if (is_capable('zoom')) {?>
<tr>
<td width="10px"><button><img src="ptz/l.png"></button></td>
<td width="10px"><button><img src="ptz/ll.png"></button></td>
<td><div class="ptz-slider zoom" style="margin-left:10px;margin-right:10px"/></td>
<td width="20px"><button><img src="ptz/rr.png"></button></td>
<td width="20px"><button><img src="ptz/r.png"></button></td>
</tr>
<?php };?>
<?php if (is_capable('focus')) {?>
<tr>
<td width="10px"><button><img src="ptz/l.png"></button></td>
<td width="10px"><button><img src="ptz/ll.png"></button></td>
<td><div class="ptz-slider focus" style="margin-left:10px;margin-right:10px"/></td>
<td width="20px"><button><img src="ptz/rr.png"></button></td>
<td width="20px"><button><img src="ptz/r.png"></button></td>
</tr>
<?php };?>
</table>
</td>
<?php if (is_capable('stop')) {?>
<td width="20%" style="text-align:center">
<div class=".ptz-home"><button>STOP</button></div>
</td>
<?php };?>
</tr>
</table>
</div><!-- ptz_area_bottom -->
