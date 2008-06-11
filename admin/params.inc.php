<?php

function translit_ru($rustr)
{
  if (empty($rustr))
      return $rustr;
  mb_regex_encoding('UTF-8');

  $patterns = array(
  'а','б','в','г','д','е','ё','з','и','й','к','л','м',
  'н','о','п','р','с','т','у','ф','х','ъ','ы','э',
  'А','Б','В','Г','Д','Е','Ё','З','И','Й','К','Л','М',
  'Н','О','П','Р','С','Т','У','Ф','Х','Ъ','Ы','Э',
  'ж','ц','ч','ш','щ','ь','ю','я',
  'Ж','Ц','Ч','Ш','Щ','Ь','Ю','Я');

  $replacements = array(
  'a','b','v','g','d','e','e','z','i','y','k','l','m',
  'n','o','p','r','s','t','u','f','h','`','i','e',
  'A','B','V','G','D','E','E','Z','I','Y','K','L','M',
  'N','O','P','R','S','T','U','F','H','`','I','E',
  'zh', 'ts', 'ch', 'sh', 'shch', '', 'yu', 'ya',
  'ZH', 'TS', 'CH', 'SH', 'SHCH', '', 'YU', 'YA');

  for ($i=0; $i<sizeof($patterns); $i++) {
        $rustr = mb_ereg_replace($patterns[$i], $replacements[$i], $rustr);
  }

  return preg_replace(array('/[^A-Za-z0-9\-_\.~]/u'), array('_'), $rustr);
}


function WhatViddev ( $dev_file )
{
      $ret = -1;

      if ( is_readable($dev_file) )
      {
               $handle = fopen ($dev_file, 'r');
               while (!feof ($handle)) {
                        $buffer = fgets($handle, 256);
                        if ( preg_match ('/^type +: VID_TYPE_CAPTURE.+/', $buffer))
                        {
                              $ret = 1;
                              break;
                        }	elseif 	( preg_match ('/^name +: Video loopback \d* input$/', $buffer)) {
                              $ret = 2;
                              break;
                        }
               }
               fclose ($handle);
      }
      return $ret;
}

function checkParam ( $parname, $parval )
{
switch ( $parname )
{
      case 'v4l_dev':
      case 'v4l_pipe':
      if ($parname == 'v4l_dev' ) $dev_code = 1; else $dev_code = 2;
               $viddev_nums = array();
               if (is_dir('/proc/video/dev'))
               {
               /* 2.4 kernel */
                        if ($dh = opendir('/proc/video/dev'))
                        {
                              while (($file = readdir($dh)) !== false) {
                                       if ( preg_match ('/^video(\d{1,2})$/', $file, $matches)) {
                                       // print "A match was found.";
                                                if ( $dev_code == WhatViddev('/proc/video/dev/'.$file) )
                                                      array_push ( $viddev_nums, $matches[1]);
                                       }
                              }
                              closedir($dh);
                        }
               } else {
                        /* 2.6 kernel */
                        /* считаем что vloopback загружен начиная с dev_offset=15 */
                        $__viddev_nums=($dev_code === 1)?glob('/dev/video[0-9]') : glob('/dev/video[0-9][13579]');
                        foreach ($__viddev_nums as $file) {
                              if ( preg_match ('/^\/dev\/video(\d+)$/', $file, $matches))
                                       array_push ( $viddev_nums, $matches[1]);
                        }
               }
               if ( count($viddev_nums) > 0 ) {
                        sort($viddev_nums,SORT_NUMERIC);
                        $ret = getSelectHtmlByName('fields['.$parname.']', $viddev_nums, FALSE, 1, 0, $parval, TRUE, FALSE, '/dev/video');
               } else $ret = '<p><font color="'.$GLOBALS['error_color'].'">'. $GLOBALS['notVidDevs'] .'</font></p>'."\n";
               break;
      case 'norm':
               if ( $parval == '' || is_null($parval))
                        $sel = '';
               else
                        $sel = $GLOBALS['vid_standarts'][$parval];
               $ret = getSelectHtml('fields['.$parname.']', $GLOBALS['vid_standarts'], FALSE, 1, 0, $sel, TRUE, FALSE);
               break;
      case 'geometry':
               $ret = getSelectHtmlByName('fields['.$parname.']', $GLOBALS['geometry'], FALSE, 1, 0, $parval, TRUE, FALSE);
               break;
      case 'deinterlacer':
               if ( $parval == '' )
                        $sel = '';
               else
                        $sel = $GLOBALS['deinterlacers'][$parval];
               $ret = getSelectHtml('fields['.$parname.']', $GLOBALS['deinterlacers'], FALSE, 1, 0, $sel, TRUE, FALSE);
               break;
      case 'mask_file':
               if (empty($parval) )
                        $ret = $GLOBALS['strEmpted'] . '<br>';
               else {
                        $ret  = '<a href="'.$GLOBALS['conf']['prefix'].'/masks/'.basename($parval).'"  target="_blank">'.basename($parval).'</a><br>'."\n";
                        $ret .= $GLOBALS['strDelete'].' &nbsp;&nbsp;<input type="checkbox" name="'.$parname.'_del"><br>'."\n";
               }
               $ret .= '<input type="hidden" name="MAX_FILE_SIZE" value="500000">'."\n";
               $ret .= '<input type="file" name="'.$parname.'" size=20 maxlength=200>'."\n";
               break;
      case 'cam_type':
               $ret = getSelectHtmlByName('fields['.$parname.']', $GLOBALS['strCamType'], FALSE, 1, 0, $parval, TRUE, FALSE);
      break;
      case 'InetCam_Proto':
               $ret = getSelectHtmlByName('fields['.$parname.']', $GLOBALS['strNetProto'], FALSE, 1, 0, $parval, TRUE, FALSE);
      break; 

      case 'V.save_fmt':
               $ret = getSelectHtmlByName('fields['.$parname.']', $GLOBALS['strFileFmt'], FALSE, 1, 0, $parval, TRUE, FALSE);
      break;
      case 'play-on-first':
      case 'play-on-end':
               if (empty($parval) )
                        $ret = $GLOBALS['strEmpted'] . '<br>';
               else {
                        $ret = '<a href="'.$GLOBALS['conf']['prefix'].'/sounds/'.basename($parval).'" target="_blank">'.basename($parval).'</a><br>'."\n";
         $ret .= $GLOBALS['strDelete'].' &nbsp;&nbsp;<input type="checkbox" name="'.$parname.'_del"><br>'."\n";
      }
               $ret .= '<input type="hidden" name="MAX_FILE_SIZE" value="500000">'."\n";
               $ret .= '<input type="file" name="'.$parname.'" size=20 maxlength=200>'."\n";
               break;
   case 'input':
      $ret = getSelectHtml('fields['.$parname.']', array(0,1,2,3), FALSE, 1, 0, $parval, TRUE, FALSE);  
      break;
      case 'jpeg_reconnect':
               $ret = getSelectHtmlByName('fields['.$parname.']', $GLOBALS['Snap_Reconnect_array'], FALSE, 1, 0, $parval, TRUE, FALSE);
               break;

      case 'A.force_fmt':
               $ret = getSelectHtmlByName('fields['.$parname.']', $GLOBALS['str_audio_force_fmt'], FALSE, 1, 0, $parval, TRUE, FALSE);
               break;

      case 'A.save_fmt':
               $ret = getSelectHtmlByName('fields['.$parname.']', $GLOBALS['str_audio_save_fmt'], FALSE, 1, 0, $parval, TRUE, FALSE);
               break;

      case 'rotate':
               if ( $parval == '' || is_null($parval) || $parval == '0' )
                        $sel = '';
               else
                        $sel = $GLOBALS['flip_type'][$parval-1];
               $ret = getSelectHtml('fields['.$parname.']', $GLOBALS['flip_type'], FALSE, 1, 1, $sel, TRUE, FALSE);
               break;
      case 'OnAlarm':
               if ( $parval == '' )
                        $sel = '';
               else
                        $sel = $GLOBALS['syslog_levels'][$parval];
               $ret = getSelectHtml('fields['.$parname.']', $GLOBALS['syslog_levels'], FALSE, 1, 0, $sel, TRUE, FALSE);
               break;
      default:
      $ret = '<p><font color="'.$GLOBALS['error_color'].'">'. sprintf($GLOBALS['unknownCheckParams'], $parname) .'</font></p>'."\n";
} // switch

return $ret;
}

/* CorrectParVal($parname, &$parval) */
function CorrectParVal($parname, $parval)
{
   switch ( $parname )
   {
      case 'text_left':
         $parval = translit_ru($parval);
      break;
   }
}

function checkExec ( $parname, $parval )
{
   $ret = '<input type="text" name="fields['.$parname.']" value="'.getBinString($parval).'" size=20 maxlength=200>' . "\n";
   if ( FALSE === file_exists('/usr/local/sbin/'.$parval) )
      $ret .= '<br/><p><font color="'.$GLOBALS['error_color'].'">'.$parval.
                  ' - not found in /usr/local/sbin</font></p>'."\n";
   return $ret;
}
?>
