<?php
$lang_file = '_admin_cams.php';
require('../head.inc.php');
require('../lib/my_conn.inc.php');
require('../lib/cams_main_detail.inc.php');

echo '<h1>' . sprintf($r_cam_list, $named, $sip) . '</h1>' ."\n";

if ( isset($cmd) )
{
   DENY($install_status);
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

if ( !isset($cam_nr) )
{
   if ( $install_user )
      echo '<div class="warn">' . $r_cam_tips_installers . '</div>' ."\n";
   else if ( $admin_user )
      echo '<div class="warn">' . $r_cam_tips_admins . '</div>' ."\n";

   $GCP_query_param_list=array('work','cam_type','geometry','color',
      'text_left','InetCam_IP','v4l_dev','input',
      'V.http_get','A.http_get','Aviosys9100_chan');
   require ('../lib/get_cams_params.inc.php');
   if ( $install_user )
      print '<p align="center"><a href="'.$conf['prefix'].'/admin/cam-addnew.php">'.$l_cam_addnew.'</a></p>'."\n";
   if ( $GCP_cams_nr > 0 )
   {
      /* Printing results in HTML */
      print $tabletag . "\n";
      print '<tr bgcolor="'.$header_color.'">'."\n";
      if ( $admin_user ) {
         if ($install_user )
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
         if ( $install_user )
            print '<td>&nbsp;</td>'."\n";
         print '<td><a href="./cam-tune.php?cam_nr=0">'.$strTune.'</td>' . "\n";
      }
      $__cam_nr = 0;
      $cam_detail = &$GCP_def_pars;
      print_cam_detail_row(&$conf, $__cam_nr, &$cam_detail);
      print "</tr>\n";

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
         if ( $install_user )
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
         print_cam_detail_row(&$conf, $__cam_nr, &$cam_detail);
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
