<?php
/**
 * @file online/ptz/acti.php
 * @brief ACTi camera PTZ handler
 */

$pageTitle = 'ACTi PTZ';
require_once('../../lib/config.inc.php');
#require('common.inc.php');
$GCP_cams_list=$_REQUEST['cam_nr'];
$GCP_query_param_list = array(
    'text_left',
    'InetCam_IP',
    'InetCam_http_port',
    'InetCam_USER',
    'InetCam_PASSWD'
);
require('../../lib/get_cams_params.inc.php');
$cam_http_params = array_merge($GCP_def_pars, $GCP_cams_params[$cam_nr]);
if (!empty($_REQUEST['get']))
{
	$result=array();
	$g=explode(',',$_REQUEST['get']);

	if (in_array('ptzf_bounds',$g)) $g=array_merge($g,array('pan_bounds','tilt_bounds','zoom_bounds','focus_bounds'));
	if (in_array('pan_bounds',$g)) $result=array_merge($result,array('pan_start'=>0,'pan_end'=>100));
	if (in_array('tilt_bounds',$g)) $result=array_merge($result,array('tilt_start'=>0,'tilt_end'=>100));
	if (in_array('zoom_bounds',$g)) $result=array_merge($result,array('zoom_start'=>0,'zoom_end'=>100));
	if (in_array('focus_bounds',$g)) $result=array_merge($result,array('focus_start'=>1,'focus_end'=>10));

	if (in_array('ptzf',$g)) $g=array_merge($g,array('pan','tilt','zoom','focus'));
	if (in_array('pan',$g)) $result=array_merge($result,array('pan'=>rand(0,100)));
	if (in_array('tilt',$g)) $result=array_merge($result,array('tilt'=>rand(0,100)));
	if (in_array('zoom',$g)) $result=array_merge($result,array('zoom'=>rand(0,100)));
	if (in_array('focus',$g)) $result=array_merge($result,array('focus'=>rand(1,10)));

	print json_encode($result);
	exit;
};
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
	$.get('ptz/acti.php',{cam_nr:$cam_nr,get:'ptzf_bounds,ptzf'},function(data){
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
<td height="10px"><button><img src="ptz/u.png"></img></button>
<td rowspan="2">
<div class=".ptz-home">
<button>HOME</button>
<div> <button><img src="ptz/sh.png"></button> <button><img src="ptz/rh.png"></button> </div>
</div><!-- ptz-home -->
</td>
</tr>
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
</table>
</div><!-- ptz_area_right -->

<div class="ptz_area_bottom">

<table width="100%" height="100%">
<tr height="100%">
<td height="100%">
<table width="100%" height="100%">
<tr>
<td width="10px"><button><img src="ptz/l.png"></button></td>
<td width="10px"><button><img src="ptz/ll.png"></button></td>
<td><div class="ptz-slider pan" style="margin-left:10px;margin-right:10px"/></td>
<td width="20px"><button><img src="ptz/rr.png"></button></td>
<td width="20px"><button><img src="ptz/r.png"></button></td>
</tr>
<tr>
<td width="10px"><button><img src="ptz/l.png"></button></td>
<td width="10px"><button><img src="ptz/ll.png"></button></td>
<td><div class="ptz-slider zoom" style="margin-left:10px;margin-right:10px"/></td>
<td width="20px"><button><img src="ptz/rr.png"></button></td>
<td width="20px"><button><img src="ptz/r.png"></button></td>
</tr>
<tr>
<td width="10px"><button><img src="ptz/l.png"></button></td>
<td width="10px"><button><img src="ptz/ll.png"></button></td>
<td><div class="ptz-slider focus" style="margin-left:10px;margin-right:10px"/></td>
<td width="20px"><button><img src="ptz/rr.png"></button></td>
<td width="20px"><button><img src="ptz/r.png"></button></td>
</tr>
</table>
</td>
<td width="20%" style="text-align:center">
<div class=".ptz-home"><button>STOP</button></div>
</td>
</tr>
</table>
</div><!-- ptz_area_bottom -->
