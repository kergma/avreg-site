<?php

if ( $cam_detail['work'] > 0 )
{
   print '<td><img src="'.$conf['prefix'].'/img/camera-red.gif" alt="'
                .$flags[1].
                '" width="22" height="22" border="0"></td>' . "\n";
   print '<td align="center" valign="center" nowrap><b><font color="Red">'.
               $__cam_nr. '</font></b></td>' . "\n";
   print '<td valign="center"><b>'.$cam_name.'</b></td>' . "\n";
} else {
   print '<td><img src="'.$conf['prefix'].'/img/camera.gif" alt="'.
            $flags[0].'" width="22" height="22" border="0"></td>' . "\n";
   print '<td align="center" valign="center" nowrap><b>'. $__cam_nr.'</b></td>' . "\n";
   print '<td valign="center">'. $cam_name.'</td>' . "\n";
}
  
if ( $cam_detail['cam_type'] === 'netcam' ) {
   if (!is_null($cam_detail['Aviosys9100_chan']))
       print '<td valign="center"  nowrap>'.
          (is_null($cam_detail['InetCam_IP'])?
             'http://not_defined':$cam_detail['InetCam_IP']).
             '&nbsp; chan '.$cam_detail['Aviosys9100_chan'].'</td>' . "\n";
   else
       print '<td valign="center"  nowrap>'.
  (is_null($cam_detail['InetCam_IP'])?'http://not_defined':$cam_detail['InetCam_IP']).
            '</td>' . "\n";
} else
   print '<td valign="center"  nowrap>/dev/video'. 
     (is_null($cam_detail['v4l_dev'])?'X':$cam_detail['v4l_dev']).
          ' input '.$cam_detail['input'].'</td>' . "\n";
                 
print '<td align="center" valign="center">'.$cam_detail['geometry'].
                   ' ('.
                   (($cam_detail['color']>0)?'color':'grey').
                   ')</td>' . "\n";
?>
