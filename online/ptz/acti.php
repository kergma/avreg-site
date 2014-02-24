<?php
/**
 * @file online/ptz/acti.php
 * @brief ACTi camera PTZ handler
 */

$pageTitle = 'ACTi PTZ';
#require('common.inc.php');
?>
<script type="text/javascript">
var $win;

$(function() {
	
	$win=window.ptztemp_win;
	delete window.ptztemp_win;
	$('.ptz-pslider',$win).slider({
		change: function (event, ui) {
			var v=$(this).slider('value');
			window.document.title=v;
		},

	});
	$('.ptz-tslider',$win).slider({
		orientation: 'vertical',
		change: function (event, ui) {
			var v=$(this).slider('value');
			window.document.title=v;
		},

	});
	$('button img',$win).height('7px');
	$('button',$win).css('padding','0px');
	//$('button',$win).css('border','0px');
	$('td',$win).css('padding','0px');
	$('button',$win).click(function(e){

		e.stopPropagation();
	});
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
<td height="100%" style="text-align:center"> <div class="ptz-tslider" style="height:90%"/></td>
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
<td><div class="ptz-pslider" style="margin-left:10px;margin-right:10px"/></td>
<td width="20px"><button><img src="ptz/rr.png"></button></td>
<td width="20px"><button><img src="ptz/r.png"></button></td>
</tr>
<tr>
<td width="10px"><button><img src="ptz/l.png"></button></td>
<td width="10px"><button><img src="ptz/ll.png"></button></td>
<td><div class="ptz-pslider" style="margin-left:10px;margin-right:10px"/></td>
<td width="20px"><button><img src="ptz/rr.png"></button></td>
<td width="20px"><button><img src="ptz/r.png"></button></td>
</tr>
<tr>
<td width="10px"><button><img src="ptz/l.png"></button></td>
<td width="10px"><button><img src="ptz/ll.png"></button></td>
<td><div class="ptz-pslider" style="margin-left:10px;margin-right:10px"/></td>
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
