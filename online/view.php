<?php

$NO_OB_END_FLUSH = true; // for setcookie()
$pageTitle = 'WebCam';
$body_style='overflow: hidden;  overflow-y: hidden !important; padding: 0; margin: 0; width: 100%; height: 100%;';
$css_links=array('lib/js/jqModal.css');
$USE_JQUERY = true;
$link_javascripts=array('lib/js/jqModal.js');
$body_addons='scroll="no"';
$ie6_quirks_mode = true;
$lang_file='_online.php';
require ('../head.inc.php');
if ( !isset($cams_in_wins) || empty($cams_in_wins))
   die('should use "cams_in_wins" cgi param');
if (is_string($cams_in_wins))
   $cams_in_wins = explode('.', $cams_in_wins);
foreach ($cams_in_wins as &$value)
   settype($value, 'int');

require('../admin/mon-type.inc.php');
if (!isset($mon_type) || empty($mon_type) || !array_key_exists($mon_type, $layouts_defs) ) 
   MYDIE("not set ot invalid \$mon_type=\"$mon_type\"",__FILE__,__LINE__);
$l_defs = &$layouts_defs[$mon_type];
$wins_nr = $l_defs[0];

$_cookie_value = sprintf('%s-%u-%u-%u-%s',
   implode('.', $cams_in_wins),
   isset($OpenInBlankPage),
   isset($PrintCamNames),
   isset($EnableReconnect),
   isset($AspectRatio) ? $AspectRatio : 'calc' );
setcookie("avreg_$mon_type", $_cookie_value, time()+5184000, dirname($_SERVER['SCRIPT_NAME']).'/build_mon.php');
while (@ob_end_flush());

/*
print '<pre>';
var_dump($cams_in_wins);
var_dump($camnames);
$GCP_query_param_list=array('work', 'allow_networks', 'text_left', 'geometry', 'Hx2');
require('../lib/get_cams_params.inc.php');
var_dump($GCP_cams_params);
print '</pre>'."\n";
die();
 */

?>
<div id="canvas"
     style="position:relative; background-color:#000000; width:100%; height:0px; margin:0; padding:0;
           -ms-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; -webkit-box-sizing: border-box;">
</div>

<?php

echo "<script type='text/javascript'>\n";

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

$major_win_cam_geo = null;
$major_win_nr = $l_defs[4] - 1;
$msie_addons_scripts=array();

$GCP_query_param_list=array('work', 'allow_networks', 'text_left', 'geometry', 'Hx2');
require('../lib/get_cams_params.inc.php');
if ( $GCP_cams_nr == 0 )
   die('There are no available cameras!');
$cam_params = array_merge_recursive($GCP_def_pars, $GCP_cams_params);
require_once('../lib/get_cam_url.php');

echo "/*\n GCP_cams_params";
var_dump($GCP_cams_params);
echo "\n\n GCP_def_pars";
var_dump($GCP_def_pars);
echo "\n\n merged";
var_dump($cam_params);
echo "*/\n";

print 'var WINS_DEF = new MakeArray('.$wins_nr.')'."\n";

for ($win_nr=0; $win_nr<$wins_nr; $win_nr++)
{
   if ( empty($cams_in_wins[$win_nr]) || !array_key_exists($cams_in_wins[$win_nr], $GCP_cams_params) /* DeviceACL */ )
      continue;

   $cam_nr = $cams_in_wins[$win_nr];

   list($ww,$wh) = explode('x', $cam_params[$cam_nr]['geometry']);
   settype($ww, 'integer'); settype($wh, 'integer');
   if (empty($ww)) $ww=640;
   if (empty($wh)) $wh=480;
   if (!empty($cam_params[$cam_nr]['Hx2'])) $wh *= 2;

   if (is_null($major_win_cam_geo) || $major_win_nr === $win_nr )
      $major_win_cam_geo = array($_ww, $_wh);
   $l_wins = &$l_defs[3][$win_nr];

   printf(
'WINS_DEF[%d]={
   row: %u,
   col: %u,
   rowspan: %u,
   colspan: %u,
   cam: {
      nr:   %s,
      name: "%s",
      url:  "%s",
      orig_w: %u,
      orig_h: %u
   }
};%s',
   $win_nr, $l_wins[0], $l_wins[1],$l_wins[2],$l_wins[3],
   $cam_nr, getCamName($cam_params[$cam_nr]['text_left']),
   get_cam_http_url(&$conf, $cam_nr, 'mjpeg'),
   $ww, $wh, "\n" );

if ( $MSIE )
   $msie_addons_scripts[] = sprintf('<script for="cam%d" event="OnClick()">
   var amc = this;
if (amc.FullScreen) 
   amc.FullScreen=0;
else
   amc.FullScreen=1;
</script>', $cam_nr);
}

printf("var FitToScreen = %s;\n", empty($FitToScreen) ? 'false' : 'true');
printf("var PrintCamNames = %s;\n", empty($PrintCamNames) ? 'false' : 'true');
printf("var EnableReconnect = %s;\n", empty($EnableReconnect) ? 'false' : 'true');
if ( empty($AspectRatio) ) {
   print 'var CamsAspectRatio = \'fs\';'."\n";
} else {
   if ( 0 === strpos($AspectRatio, 'calc') ) {
      $ar = calcAspectForGeo($major_win_cam_geo[0], $major_win_cam_geo[1]);
      printf("var CamsAspectRatio = { num: %d, den: %d };\n", $ar[0], $ar[1]);
   } else if (preg_match('/^(\d+):(\d+)$/', $AspectRatio, $m)) {
      printf("var CamsAspectRatio = { num: %d, den: %d };\n", $m[1], $m[2]);
   } else
      print 'var CamsAspectRatio = \'fs\';'."\n";
}

printf("var BorderLeft   = %u;\n", empty($BorderLeft)   ? 2 : $BorderLeft);
printf("var BorderRight  = %u;\n", empty($BorderRight)  ? 2 : $BorderRight);
printf("var BorderTop    = %u;\n", empty($BorderTop)    ? 2 : $BorderTop);
printf("var BorderBottom = %u;\n", empty($BorderBottom) ? 2 : $BorderBottom);

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

readfile('view.js');
echo "</script>\n";

if ( !empty($msie_addons_scripts) || is_array($msie_addons_scripts) )  {
   foreach ($msie_addons_scripts as $value)
      print "$value\n";
}

require ('../foot.inc.php');
?>
