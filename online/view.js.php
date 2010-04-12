<?php

function calcAspectForGeo($w,$h) {

   foreach ($GLOBALS['WellKnownAspects'] as &$pair) {
      if ( 0 === $w % $pair[0] &&  0 === $h % $pair[1] ) {
         if ( $w/$pair[0] === $h/$pair[1] )
            return $pair;
      }
      if ( $h % $pair[0] &&  $w % $pair[1] ) {
         if ( $h/$pair[0] === $w/$pair[1] )
            return array($pair[1],$pair[0]);
      }
   }

   $ar = array($w,$h);
   $_stop = ($w>$h)?$h:$w;
   for ($i=1; $i<=$_stop; $i++) {
      if ( 0 === $w%$i && 0 === $h%$i ) {
         $ar[0] = $w/$i;
         $ar[1] = $h/$i;
      }
   }

   return $ar;
}

print 'var WINS_DEF = new MakeArray('.$wins_nr.')'."\n";

$major_win_cam_geo = null;
$major_win_nr = $l_defs[4] - 1;
$msie_addons_scripts=array();

for ($i=0; $i<$wins_nr; $i++)
{
    if (empty($cams[$i]))
        continue;

    if (!preg_match("/^(\d+);(\d*\.\d*\.\d*\.\d*|[a-zA-Z-_0-9\.]+);(\d+);(\d+)x(\d+)/i",
            $cams[$i], $matches) )
            MYDIE("preg_match($cams[$i]) failed",__FILE__,__LINE__);

    $cam_nr = $matches[1];settype($cam_nr,'int');
    $_sip = $matches[2];
    $w_port = $matches[3];settype($w_port,'int');
    $_ww=$matches[4]; settype($_ww,'int');
    $_wh=$matches[5]; settype($_wh,'int');
    if (is_null($major_win_cam_geo) || $major_win_nr === $i )
       $major_win_cam_geo = array($_ww, $_wh);

    $l_wins = &$l_defs[3][$i];

    printf(
'WINS_DEF[%d]={
   row: %u,
   col: %u,
   rowspan: %u,
   colspan: %u,
   cam: {
      nr: %s,
      name: "%s",
      url: "http://%s:%u/avreg-cgi/mjpg/video.cgi?camera=%u",
      orig_w: %u,
      orig_h: %u
      }
};%s', $i, $l_wins[0], $l_wins[1],$l_wins[2],$l_wins[3], $cam_nr, $camnames[$i], $_sip, $w_port, $cam_nr, $_ww, $_wh, "\n" );

    if ( $MSIE )
        $msie_addons_scripts[] = sprintf('<script for="cam%d" event="OnClick()">
            var amc = this;
            if (amc.FullScreen) 
                amc.FullScreen=0;
            else
                amc.FullScreen=1;
           </script>', $cam_nr);
}

if (isset($_POST['FitToScreen']))
  print 'var FitToScreen = true;'."\n";
else
  print 'var FitToScreen = false;'."\n";

if (isset($_POST['PrintCamNames']))
  print 'var PrintCamNames = true;'."\n";
else
   print 'var PrintCamNames = false;'."\n";

if (isset($_POST['EnableReconnect']))
  print 'var EnableReconnect = 1;'."\n";
else
  print 'var EnableReconnect = 0;'."\n";

if (isset($_POST['AspectRatio'])) {
   if ( 0 === strpos($_POST['AspectRatio'], 'calc') ) {
      $ar = calcAspectForGeo($major_win_cam_geo[0], $major_win_cam_geo[1]);
      printf("var CamsAspectRatio = { num: %d, den: %d };\n", $ar[0], $ar[1]);
   } else if (preg_match('/^(\d+):(\d+)$/', $_POST['AspectRatio'], $m)) {
      printf("var CamsAspectRatio = { num: %d, den: %d };\n", $m[1], $m[2]);
   } else
      print 'var CamsAspectRatio = \'fs\';'."\n";
} else
  print 'var CamsAspectRatio = \'fs\';'."\n";

if (isset($_POST['BorderLeft']))
  print 'var BorderLeft = parseInt('.$_POST['BorderLeft'].");\n";
else
   print 'var BorderLeft = 2;' . "\n";
if (isset($_POST['BorderRight']))
  print 'var BorderRight = parseInt('.$_POST['BorderRight'].");\n";
else
  print 'var BorderRight = 2;'. "\n";
if (isset($_POST['BorderTop']))
  print 'var BorderTop = parseInt('.$_POST['BorderTop'].");\n";
else
  print 'var BorderTop = 2;' . "\n";
if (isset($_POST['BorderBottom']))
  print 'var BorderBottom = parseInt('.$_POST['BorderBottom'].");\n";
else
  print 'var BorderBottom = 1;' . "\n";

// $user_info config.inc.php
print 'var ___u="'.$user_info['USER']."\"\n";
if (empty($user_info['PASSWD']) /* задан пароль */)
    print 'var ___p="empty"'.";\n"; // нужно чтобы AMC не запрашивал пароль при пустом пароле
else
    print 'var ___p="'.$_SERVER["PHP_AUTH_PW"]."\";\n";

print 'var ___abenc="'.base64_encode($user_info['USER'].':'.$_SERVER["PHP_AUTH_PW"])."\";\n";

/* other php layout_defs to javascript vars */

print "var WINS_NR = $wins_nr;\n";
print "var ROWS_NR = $l_defs[1];\n";
print "var COLS_NR = $l_defs[2];\n";

?>
