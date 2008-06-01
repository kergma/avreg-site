<?php
require ('../head.inc.php');
require ('../lib/my_conn.inc.php');

echo '<h1>' . sprintf($r_cam,$named, $sip) . '</h1>' ."\n";

if ( isset($cmd) )
{
      DENY($admin_status);
      switch ( $cmd ) {
               case 'DEL':
                        echo '<p class="HiLiteBigWarn">' . sprintf ($strDeleteCamConfirm, $cam_nr, $cam_name, $named, $sip) . '</p>' ."\n";
                        print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
                        print '<input type="hidden" name="cmd" value="DEL_OK">'."\n";
               print '<input type="hidden" name="cam_nr" value="'.$cam_nr.'">'."\n";
                        print '<input type="hidden" name="cam_name" value="'.$cam_name.'">'."\n";
                        print '<input type="submit" name="mult_btn" value="'.$strYes.'">'."\n";
                        print '<input type="submit" name="mult_btn" value="'.$strNo.'">'."\n";
                        print '</form>'."\n";
                        require ('../foot.inc.php');
                        exit;
                        break; /**/
               case 'DEL_OK':
                        if ( ($mult_btn == $strYes) && isset($cam_nr) )
                        {
                              $query = sprintf('DELETE FROM CAMERAS WHERE BIND_MAC=\'local\' AND CAM_NR=%d', $cam_nr);
                              mysql_query($query) or die("Query failed");
                              echo '<p class="HiLiteWarn">' . sprintf ($strDeleteCam, $cam_nr, $cam_name) . '</p>' ."\n";
                        }
                        unset($cam_nr);
               break;
      }
}

echo '<h2>' . $r_cam_list . '</h2>' ."\n";

if ( !isset($cam_nr) )
{
   echo '<div class="warn">' . $r_cam_tips1 . '</div>' ."\n";
   $GCP_query_param_list=array('work','cam_type','geometry','color',
         'text_left','InetCam_IP','v4l_dev','input','Aviosys9100_chan');
   require ('../lib/get_cams_params.inc.php');
      if ( $admin_user )
         print '<p align="center"><a href="'.$conf['prefix'].'/admin/cam-addnew.php">'.$l_cam_addnew.'</a></p>'."\n";
      if ( $GCP_cams_nr > 0 )
      {
         /* Printing results in HTML */
         print $tabletag . "\n";
         print '<tr bgcolor="'.$header_color.'">'."\n";
         if ( $admin_user )
         {
               print '<th>&nbsp;</th>'."\n";
               print '<th>&nbsp;</th>'."\n";
         }
         print '<th>&nbsp;</th>'."\n";
         print '<th nowrap>'.$strCam.'</th>'."\n";
         print '<th>'.$strName.'</th>'."\n";
         print '<th>'.$strType.'</th>'."\n";
         print '<th>'.$strGeo.'</th>'."\n";
         print '</tr>'."\n";

         print '<tr>'."\n";
         if ( $admin_user ) {
            print '<td>&nbsp;</td>'."\n";
            print '<td><a href="./cam-tune.php?cam_nr=0">'.$strTune.'</td>' . "\n";
         }
         print '<td colspan="2" align="center" nowrap><b>For ALL</b></td>'."\n";
         print '<td><b>'.$r_cam_defs2.'</b></td>'."\n";

         if ( $GCP_def_pars['cam_type'] === 'netcam' )
         {
            if (!is_null($GCP_def_pars['Aviosys9100_chan']))
               print '<td valign="center"  nowrap>'.
                     (is_null($GCP_def_pars['InetCam_IP'])?'http://not_defined':$GCP_def_pars['InetCam_IP']).
                  '&nbsp; chan '.$GCP_def_pars['Aviosys9100_chan'].'</td>' . "\n";
            else
               print '<td valign="center"  nowrap>'.
                  (is_null($GCP_def_pars['InetCam_IP'])?'http://not_defined':$GCP_def_pars['InetCam_IP']).
                  '</td>' . "\n";
         } else {
            print '<td valign="center"  nowrap>/dev/video'.
            (is_null($GCP_def_pars['v4l_dev'])?'X':$GCP_def_pars['v4l_dev']).
            ' input '.$GCP_def_pars['input'].'</td>' . "\n";
         }

         print '<td align="center" valign="center">'.
                  $GCP_def_pars['geometry'].
                  ' ('.
                  (($GCP_def_pars['color']>0)?'color':'grey').
                  ')</td>' . "\n";
         print '</tr>'."\n";

         $r_count = 0;
         reset($GCP_cams_params);
         while (list($__cam_nr, $cam_detail) = each($GCP_cams_params)) 
         {
            $cam_name = getCamName($cam_detail['text_left']);
            $r_count++;
            if($r_count%2)
               print '<tr style="background-color:#FCFCFC">'."\n";
            else
               print "<tr>\n";
            if ( $admin_user )
            {
               if ( $r_count == $GCP_cams_nr) {
                  $ggg = sprintf('<a href="%s/%s?cmd=DEL&cam_nr=%d&cam_name=%s">%s</a>',
                         $_SERVER['PHP_SELF'], $conf['prefix'],
                         $__cam_nr, $cam_name, $strDelete);
                  print '<td>'.$ggg.'</td>' . "\n";
               } else
                  print '<td>&nbsp;</td>' . "\n";
            }
            if ( $admin_user ) {
               $ggg = sprintf('<a href="./cam-tune.php?cam_nr=%d&cam_name=%s">%s</a>',
                     $__cam_nr, $cam_name, $strTune);
                     print '<td>'.$ggg.'</td>' . "\n";
            }
            require('../lib/cams_main_detail.inc.php');
            print "</tr>\n";
         }
         print "</table>\n";

         if ( $install_user ) print '<p align="center"><a href="'.$conf['prefix'].'/admin/cam-addnew.php">'.$l_cam_addnew.'</a></p>'."\n";
      } else {
         print '<p><b>' . $strNotCamsDef . '</b></p>' . "\n";
      }
      /* choice number cam */
} else {
/* defined cam with cam_nr */

}

// phpinfo ();
require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
