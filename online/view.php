<?php

if (!isset($_POST) || !isset($_POST['mon_type']) || !isset($_POST['cams']) || !is_array($_POST['cams']))
  die('you need start from /online/index.php');

$ccnt=count($_POST['cams']);
$ccnr_a=0;
for ($i=0;$i<$ccnt;$i++) 
   if (!empty($_POST['cams'][$i]))
      $ccnr_a++;
if ($ccnr_a===0) 
   die('not defined cams for view');

require ('../admin/mon-type.inc.php');
$mon_type=$_POST['mon_type'];
if (!isset($mon_type) || empty($mon_type) || !array_key_exists($mon_type, $layouts_defs) ) 
   MYDIE("not set ot invalid \$mon_type=\"$mon_type\"",__FILE__,__LINE__);
$l_defs = &$layouts_defs[$mon_type];
$wins_nr = $l_defs[0];

$сb = 'avreg_' . $mon_type . '_OpenInBlankPage'; 
$cnm  = 'avreg_' . $mon_type . '_PrintCamNames';
$ercnt = 'avreg_' . $mon_type . '_EnableReconnect';
$car = 'avreg_' . $mon_type . '_AspectRatio';
$expired = time()+5184000;
$ca=dirname($_SERVER['SCRIPT_NAME']).'/build_mon.php';
if (isset($_POST['OpenInBlankPage']))
   setcookie($сb,  '1', $expired,$ca);
else
   setcookie($сb,  '0', $expired,$ca);
if (isset($_POST['PrintCamNames']))
   setcookie($cnm,  '1', $expired,$ca);
else
   setcookie($cnm,  '0', $expired,$ca);
if (isset($_POST['EnableReconnect']))
   setcookie($ercnt,  '1', $expired,$ca);
else
   setcookie($ercnt,  '0', $expired,$ca);
if (isset($_POST['AspectRatio']))
   setcookie($car,  $_POST['AspectRatio'], $expired,$ca);

for ($i=0;$i<$wins_nr;$i++) 
  if (isset($_POST['cams'][$i]))
     setcookie('avreg_' . $mon_type.'_cams['.$i.']',$_POST['cams'][$i],$expired,$ca);


$pageTitle = 'WebCam';
$body_style='overflow: hidden;  overflow-y: hidden !important; padding: 0; margin: 0; width: 100%; height: 100%;';
$css_links=array('lib/js/jqModal.css');
$USE_JQUERY = true;
$link_javascripts=array('lib/js/jqModal.js');
$include_javascripts=array('online/view.js.php', 'online/view.js');
$body_addons='scroll="no"';
$ie6_quirks_mode = true;
$lang_file='_online.php';
require ('../head.inc.php');
if ( !isset($cams) || !is_array($cams)) 
   MYDIE('not set cams',__FILE__,__LINE__);

/*
print '<pre>';
var_dump($cams);
var_dump($camnames);
print '</pre>'."\n";
die();

<div id="toolbar" style="position:absolute; height:25px; width:100%; margin:0; padding:0; background-color:#003366; overflow:hidden; color:#E0E0E0;" >

<div id="canvas"
     style="position:absolute; top:25px; background-color:#000; width:100%; height:0px; overflow:hidden; margin:0; padding:0;
           -ms-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; -webkit-box-sizing: border-box;">
</div>
 */
?>
<div id="canvas"
     style="position:relative; background-color:#000000; width:100%; height:0px; margin:0; padding:0;
           -ms-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; -webkit-box-sizing: border-box;">
</div>

<?php
if ( !empty($msie_addons_scripts) || is_array($msie_addons_scripts) )  {
    foreach ($msie_addons_scripts as $value)
        print "$value\n";
}

require ('../foot.inc.php');
?>
