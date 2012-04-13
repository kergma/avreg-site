<?php
/* phpinfo(); */

echo '<h2 align="center">'.$r_webcam_list.'</h2>' ."\n";
print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
print '<p align="center">'.$strWcListShow.getSelectHtml('wclist_show', $WcListShow, FALSE, 1, 0, $WcListShow[$wclist_show], FALSE, TRUE)."</p>\n";
if (isset($mon_type))
   print '<input type="hidden" name="mon_type" value="'.$mon_type.'">'."\n";
print '</form>'."\n";

$tot_act_cams_ar=array();
if ($wclist_show>0)
{
   print '<div align="center">'. "\n";
   /* Printing results in HTML */
   print '<table cellspacing="0" border="1" cellpadding="3">'. "\n";
}

$GCP_query_param_list=array('work','allow_networks', 'text_left','geometry','Hx2','cam_type','InetCam_IP','V.http_get','v4l_dev');
require ('../lib/get_cams_params.inc.php');

$local_cam_nr = -1;
$cams_count = 0;
unset($cams_array);
$cams_array = &$GCP_cams_params;
// echo '<pre style="text-align:left;">'."\n";
// print_r($cams_array);
// echo '</pre>'."\n";

if ( $GCP_cams_nr > 0 )
{
   if ($wclist_show>0)
   {
      print '<tr style="background-color:'.$rowHiLight.';">'."\n";
      print '<th>&nbsp;</th>'."\n";
      print '<th nowrap>'.$strOrder.'</th>'."\n";
      print '<th>'.$sInformation.'</th>'."\n";
      if ($wclist_show>1)
         print '<th>'.$sUnavailableReason.'</th>'."\n";
      print '</tr>'."\n";
   }

   foreach ($GCP_cams_list as $__cam_nr)
   {
      $wc = &$cams_array[$__cam_nr];
      $cam_name = getCamName($wc['text_left']);
      $is_netcam = ( $wc['cam_type'] == 'netcam' );

      // $cam_nr, $geo, $Hx2, $cam_name, $_named
      if ( $wc['work'] && $wc['allow_networks'] &&
         (($is_netcam && ($wc['InetCam_IP'] && $wc['V.http_get'])) || (!$is_netcam && isset($wc['v4l_dev']))) )
      {
         if ($wclist_show>0) {
            print "<tr>\n";
            print '<td align="center"><img src="'.$conf['prefix'].'/img/cam_on_35x32.gif" alt="'.$flags[1].'" width="35" height="32" border="0"></td>' . "\n";
            print '<td align="center" valign="middle" nowrap><b>'.$__cam_nr . '</b></td>' . "\n";
            print '<td>'.$cam_name.' ('.$wc['geometry'].')</td>' . "\n";
            if ($wclist_show>1)
               print '<td>&nbsp;</td></tr>' . "\n";
         }
         array_push($tot_act_cams_ar, $__cam_nr);
      } else {
         if ($wclist_show>1) {
            print "<tr>\n";
            // print '<td align="center" valign="center"><input type="checkbox" disabled name="cams[]" value="'.$webcam_def.'">&nbsp;</td>' . "\n";
            print '<td align="center"><img src="'.$conf['prefix'].'/img/cam_off_35x32.gif" alt="'.$flags[1].'" width="35" height="32" border="0"></td>' . "\n";
            print '<td align="center" valign="middle" nowrap><b>'. $__cam_nr . '</b></td>' . "\n";
            print '<td>'. $cam_name .'</td>' . "\n";
            $off_reason = '';
            if ($wc['work']==0)
               $off_reason .= 'work="'.$flags[0].'";&nbsp;&nbsp;';
            if ($wc['allow_networks']==0)
               $off_reason .= 'allow_networks="'.$flags[0].'";&nbsp;&nbsp;';
            if ( $is_netcam ) {
               if (empty($wc['InetCam_IP']))
                  $off_reason .= 'InetCam_IP is empty;&nbsp;&nbsp;';
               if (empty($wc['V.http_get']))
                  $off_reason .= 'V.http_get is empty;&nbsp;&nbsp;';
            } else {
               if (!isset($wc['v4l_dev']))
                  $off_reason .= 'v4l_dev is empty;&nbsp;&nbsp;';
            }
            print '<td>'. $off_reason .'</td></tr>' . "\n";
         }
      }
   }
}

if ($wclist_show>0)
   print "</table>\n";
require ('../lib/my_close.inc.php');

$tot_wc_nr = count($tot_act_cams_ar);
if ( $tot_wc_nr )
   echo '<p align="center">'.sprintf($fmtActiveWEBCAMS, $tot_wc_nr).'</p>' ."\n";
else
   echo '<p align="center" class="HiLiteErr">'.$srtNoActiveWEBCAMS.'</p>' ."\n";
print '</div>'."\n";

/*
print '<br><pre style="text-align:left;">'."\n";
var_dump($tot_act_cams_ar);
print '</pre>'."\n";
*/
?>
