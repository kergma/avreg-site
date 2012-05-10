<?php

/**
 * 
 * @file lib/cams_main_detail.inc.php
 * @brief Функция позволяет вывести детальную информацию о камере
 * 
 */

/// поля по умолчанию
$__DEF_CAM_DETAIL_COLUMNS = array(
   'ICONS'  => true,
   'CAM_NR' => true,
   'NAME'   => true,
   'SRC'    => true,
   'CAPS'   => true,
);
/**
 * 
 * Функция позволяет вывести детальную информацию о камере
 * @param array $conf настройки
 * @param int $cam_nr номер камеры
 * @param array $cam_detail детальная информация
 * @param array $columns поля
 */
function print_cam_detail_row($conf, $cam_nr, $cam_detail, $columns = null)
{
   if (isset($columns))
      $_cols = &$columns;
   else
      $_cols = &$GLOBALS['__DEF_CAM_DETAIL_COLUMNS'];
   $cam_active = ($cam_detail['work'] > 0);
   if ( $cam_nr > 0 )
      $cam_name = &$cam_detail['text_left'];
   else
      $cam_name = $GLOBALS['r_cam_defs2'];
   $cam_has_video = false;
   $cam_has_audio = false;
   if ( isset($cam_detail['cam_type']) && $cam_detail['cam_type'] === 'netcam' ) {
      if ( $cam_detail['V.http_get'] ) {
         $cam_has_video = true;
      }
      if ( isset($cam_detail['A.http_get']) && $cam_detail['A.http_get'] ) {
         $cam_has_audio = true;
      }
   } else
      $cam_has_video = true;

   /* print icons <td> */
   if ( isset($_cols['ICONS']) && $_cols['ICONS'] ) {
      print '<td>';
      if ($cam_has_video)
         printf('<img src="'.$conf['prefix'].'%s" title="video" alt="%s" width="35" height="32" border="0">' . "\n",
            $cam_active ? '/img/cam_on_35x32.gif' : '/img/cam_off_35x32.gif',
            $cam_active ? $GLOBALS['flags'][1] : $GLOBALS['flags'][0]
         );
      else
         print '<span style="margin-left: 32px"></span>' . "\n";

      if ($cam_has_audio)
         printf('<img src="'.$conf['prefix'].'%s" title="audio" alt="%s" width="32" height="32" border="0">' . "\n",
            $cam_active ? '/img/mic_on_32x32.gif' : '/img/mic_off_32x32.gif',
            $cam_active ? $GLOBALS['flags'][1] : $GLOBALS['flags'][0]
         );
      print '</td>';

   }

   /* print cameras number <td> */
   if ( isset($_cols['CAM_NR']) && $_cols['CAM_NR'] ) {
      if ( $cam_active )
         print '<td align="center" valign="center" nowrap><div style="font-weight: bold;">'.$cam_nr. '</div></td>' . "\n";
      else
         print '<td align="center" valign="center" nowrap><div>'. $cam_nr.'</div></td>' . "\n";
   }

   /* print cameras name <td> */
   if ( isset($_cols['NAME']) && $_cols['NAME'] ) {
      print '<td align="left" valign="center" nowrap>';
      if ( !empty($_cols['NAME']['href']) ) {
         $tag_a_cont = sprintf('href="%s?camera=%d" title="%s"',
                       $_cols['NAME']['href'], $cam_nr, $_cols['NAME']['title']);
         if ( $cam_active )
            print '<a '.$tag_a_cont.' style="font-weight: bold;">'.$cam_name. '</a>' . "\n";
         else
            print '<a '.$tag_a_cont.'>'. $cam_name.'</a>' . "\n";
      } else {
         if ( $cam_active )
            print '<div style="font-weight: bold;">'.$cam_name. '</div>' . "\n";
         else
            print '<div>'. $cam_name.'</div>' . "\n";
      }
      print "</div></td>\n";
   }

   /* print cameras source/type <td> */
   if ( isset($_cols['SRC']) && $_cols['SRC'] ) {
      if ( $cam_detail['cam_type'] === 'netcam' ) {
         $proto_scheme='http://';
         if (!is_null($cam_detail['Aviosys9100_chan']))
            print '<td valign="center"  nowrap>'.$proto_scheme.
            (empty($cam_detail['InetCam_IP'])?
            'not_defined':$cam_detail['InetCam_IP']).
            '&nbsp; chan '.$cam_detail['Aviosys9100_chan'].'</td>' . "\n";
         else
            print '<td valign="center"  nowrap>'.$proto_scheme.
            (empty($cam_detail['InetCam_IP'])?'not_defined':$cam_detail['InetCam_IP']).
            '</td>' . "\n";
      } else {
         if ($cam_has_video) {
            print '<td valign="center"  nowrap>';
            if ( isset($cam_detail['v4l_dev']) )
               echo '/dev/video',$cam_detail['v4l_dev'];
            else
               echo '/dev/no_device';
            if ( isset($cam_detail['input']) )
               echo ':',$cam_detail['input'];
            echo '</td>' . "\n";
         }
      }
   }

   /* print cameras short capabilities <td> */
   if ( isset($_cols['CAPS']) && $_cols['CAPS']) {
      if ( $cam_has_video || $cam_nr == 0 )
         print '<td align="center" valign="center">'.$cam_detail['geometry'].
         ' ('.
         ((!isset($cam_detail['color']) || $cam_detail['color']>0)?'color':'grey').
         ')</td>' . "\n";
      else
         print '<td>&nbsp;</td>' . "\n";
   }
}
?>
