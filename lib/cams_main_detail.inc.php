<?php

$cam_has_video = false;
$cam_has_audio = false;
if ( $cam_detail['cam_type'] === 'netcam' ) {
	if ( $cam_detail['V.http_get'] ) {
		$cam_has_video = true;
	}
	if ( $cam_detail['A.http_get'] ) {
		$cam_has_audio = true;
	}
} else {
	$cam_has_video = true;
}

// print icons
if ( $cam_detail['work'] > 0 )
{
	print '<td>';
	if ($cam_has_video)
		print '<img src="'.$conf['prefix'].'/img/camera-red.gif" alt="'
                .$flags[1].
                '" width="22" height="22" border="0">' . "\n";
	else
		print '<span style="margin-left: 22px"></span>' . "\n";
	if ($cam_has_audio)
		print '<img src="'.$conf['prefix'].'/img/audio.on.gif" alt="'
				 .$flags[1].
				'" width="20" height="22" border="0">' . "\n";
	print '</td>';
	print '<td align="center" valign="center" nowrap><b><font color="Red">'.
               $__cam_nr. '</font></b></td>' . "\n";
	if ($__cam_nr > 0 )
		print '<td valign="center"><b>'.$cam_name.'</b></td>' . "\n";
	else
		print '<td><b>'.$r_cam_defs2.'</b></td>'."\n";
} else {
	print '<td>';
	if ($cam_has_video)
		print '<img src="'.$conf['prefix'].'/img/camera.gif" alt="'
					.$flags[0].
					'" width="22" height="22" border="0">' . "\n";
	else
		print '<span style="margin-left: 22px"></span>' . "\n";
	if ($cam_has_audio)
		print '<img src="'.$conf['prefix'].'/img/audio.gif" alt="'
						.$flags[0].
						'" width="20" height="22" border="0">' . "\n";
	print '</td>';
	print '<td align="center" valign="center" nowrap><b>'. $__cam_nr.'</b></td>' . "\n";
	if ($__cam_nr > 0 )
		print '<td valign="center">'.$cam_name.'</td>' . "\n";
	else
		print '<td>'.$r_cam_defs2.'</td>'."\n";
}

if ( $cam_detail['cam_type'] === 'netcam' ) {
   $proto_scheme='http://';
   if (!is_null($cam_detail['Aviosys9100_chan']))
		print '<td valign="center"  nowrap>'.$proto_scheme.
          (is_null($cam_detail['InetCam_IP'])?
             'not_defined':$cam_detail['InetCam_IP']).
             '&nbsp; chan '.$cam_detail['Aviosys9100_chan'].'</td>' . "\n";
   else
		print '<td valign="center"  nowrap>'.$proto_scheme.
			(is_null($cam_detail['InetCam_IP'])?'not_defined':$cam_detail['InetCam_IP']).
            '</td>' . "\n";
} else {
	if ($cam_has_video) {
		print '<td valign="center"  nowrap>v4l://';
		if ( $cam_detail['v4l_dev'] )
			echo '/dev/video',$cam_detail['v4l_dev'];
		else
			echo '/dev/no_device';
		if ( $cam_detail['input'] )
			echo ':',$cam_detail['input'];
		echo '</td>' . "\n";
	}
}

if ($cam_has_video)
	print '<td align="center" valign="center">'.$cam_detail['geometry'].
                   ' ('.
                   ((empty($cam_detail['color']) || $cam_detail['color']>0)?'color':'grey').
                   ')</td>' . "\n";
else
	print '<td>&nbsp;</td>' . "\n";	
?>
