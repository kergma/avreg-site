<?php
require_once ('../lib/my_conn.inc.php');
/* phpinfo(); */

echo '<h2 align="center">'.$r_webcam_list.'</h2>' ."\n";
print '<form action="'.$PHP_SELF.'" method="POST">'."\n";
print '<p align="center">'.$strWcListShow.getSelectHtml('wclist_show', $WcListShow, FALSE, 1, 0, $WcListShow[$wclist_show], FALSE, TRUE)."</p>\n";
if (isset($mon_type))
   print '<input type="hidden" name="mon_type" value="'.$mon_type.'">'."\n";
print '</form>'."\n";

$total_active_cams=0;
$tot_act_cams_ar=array();
if ($wclist_show>0)
{
  print '<div align="center">'. "\n";
  /* Printing results in HTML */
  print '<table cellspacing="0" border="1" cellpadding="3">'. "\n";
}

$GCP_query_param_list=array('work','live_view','webcam_live','wc_port', 'text_left','geometry','Hx2','cam_type','color','InetCam_IP','v4l_dev','input','Aviosys9100_chan');
require ('../lib/get_cams_params.inc.php');

$local_cam_nr = -1;
$cams_count = 0;
unset($cams_array);
$cams_array = &$GCP_cams_params;

/*
echo '<pre style="text-align:left;">'."\n";
print_r($cams_array);
echo '</pre>'."\n";
*/

if ( $GCP_cams_nr > 0 )
{
    if ($wclist_show>0)
    {
		print '<tr style="background-color:'.$rowHiLight.';">'."\n";
		print '<th>&nbsp;</th>'."\n";
		print '<th nowrap>'.$strOrder.'</th>'."\n";
		print '<th>'.$sInformation.'</th>'."\n";
        if ($wclist_show>1 && $admin_user)
			  print '<th>'.$sUnavailableReason.'</th>'."\n";
		print '</tr>'."\n";
    }
	
   foreach ($GCP_cams_list as $__cam_nr)
   {
		$wc = &$cams_array[$__cam_nr];
		$cam_name = getCamName($wc['text_left']);

		// $cam_nr, $_sip, $w_port, $geo, $Hx2, $cam_name, $_named
		$webcam_def = sprintf('%u;%s;%u;%s;%u;%s',
			$__cam_nr, $_SERVER['SERVER_NAME'], $wc['wc_port'], $wc['geometry'], $wc['Hx2'], $cam_name);
		if ( $wc['work'] && $wc['live_view'] && $wc['webcam_live'] && $wc['wc_port'] )
		{
              if ($wclist_show>0) {
                print "<tr>\n";
				// print '<td align="center" valign="center"><input type="checkbox" name="cams[]" value="'.$webcam_def.'">&nbsp;</td>' . "\n";
				print '<td align="center"><img src="'.$conf['prefix'].'/img/camera-red.gif" alt="'.$flags[1].'" width="22" height="22" border="0"></td>' . "\n";
				print '<td align="center" valign="middle" nowrap><b>'.$__cam_nr . '</b></td>' . "\n";
				print '<td>'.$cam_name.' ('.$wc['geometry'].')</td>' . "\n";
                if ($wclist_show>1 && $admin_user)
				   print '<td>&nbsp;</td></tr>' . "\n";
                }
				array_push($tot_act_cams_ar,$webcam_def);
				$total_active_cams++;
			} else {
            if ($wclist_show>1 && $admin_user) {
                print "<tr>\n";
				// print '<td align="center" valign="center"><input type="checkbox" disabled name="cams[]" value="'.$webcam_def.'">&nbsp;</td>' . "\n";
				print '<td align="center"><img src="'.$conf['prefix'].'/img/camera.gif" alt="'.$flags[1].'" width="22" height="22" border="0"></td>' . "\n";
				print '<td align="center" valign="middle" nowrap><b>'. $__cam_nr . '</b></td>' . "\n";
				print '<td>'. $cam_name .'</td>' . "\n";
                $off_reason = '';
                if ($wc['work']==0)
                   $off_reason .= 'work="'.$flags[0].'";&nbsp;&nbsp;';
                if ($wc['live_view']==0)
                   $off_reason .= 'live_view="'.$flags[0].'";&nbsp;&nbsp;'; 
                if ($wc['webcam_live']==0)
                   $off_reason .= 'webcam_live="'.$flags[0].'";&nbsp;&nbsp;'; 
                if ($wc['wc_port']==0)
                   $off_reason .= 'wc_port="'.$srtUndef.'";'; 
				print '<td>'. $off_reason .'</td></tr>' . "\n";
                }
			}
		}
}

if ($wclist_show>0)
  print "</table>\n";
require ('../lib/my_close.inc.php');
$act_wc_nr_ar = array();
$tot_wc_nr = count($tot_act_cams_ar);
if ($tot_wc_nr>0){
print '<script type="text/javascript" language="JavaScript1.2">'."\n";
print '<!--'."\n";
print 'var CNAMES = new MakeArray('.$tot_wc_nr.')'."\n";
for ($i = 0; $i < $tot_wc_nr; $i++)
{
	list($cam_nr, $_sip, $w_port, $geo, $Hx2, $cam_name) = explode(';', $tot_act_cams_ar[$i]);
	list($ww,$wh) = explode('x',$geo);
	if (empty($ww)) $ww=640;
	if (empty($wh)) $wh=480;
        if ($Hx2) $wh *= 2;
	$act_wc_nr_ar[$cam_nr]=sprintf('%u;%s;%u;%ux%u', $cam_nr,  $_SERVER['SERVER_NAME'], $w_port, $ww,$wh);
    print 'CNAMES['.$i.']="'.$cam_name.'";'."\n";
}
print '// -->'."\n";
print '</script>'."\n";
}
/*
print '<br><pre style="text-align:left;">'."\n";
var_dump($tot_act_cams_ar);
var_dump($act_wc_nr_ar);
var_dump($tot_wc_nr);
print '</pre>'."\n";
*/
echo '<h3 align="center">'.(($tot_wc_nr>0)?sprintf($fmtActiveWEBCAMS, $total_active_cams):$srtNoActiveWEBCAMS).'</h3>' ."\n";
print '</div>'."\n";
/*
echo '<pre>'."\n";
print_r($tot_act_cams_ar);
echo '<pre>'."\n";
*/

?>
