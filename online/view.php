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

$mon_type=$_POST['mon_type'];
switch ($mon_type)
{
  case 'ONECAM':
    $wins_nr=1;
    break;
  case 'QUAD_4_4':
    $wins_nr=4;
    break;
  case 'POLY_3_2':
    $wins_nr=6;
    break;
  case 'POLY_4_2':
    $wins_nr=8;
    break;
  case 'QUAD_9_9':
    $wins_nr=9;
    break;
  case 'POLY_4_3':
    $wins_nr=12;
    break;
  case 'QUAD_16_16':
    $wins_nr=16;
    break;
  case 'QUAD_25_25':
    $wins_nr=25;
    break;
  case 'QUAD_36_36':
    $wins_nr=36;
    break;
  default:
    die("unknown mon_type=$mon_type");
}
$cfts = 'avreg_' . $mon_type . '_FitToScreen'; 
$cnm  = 'avreg_' . $mon_type . '_PrintCamNames';
$ercnt = 'avreg_' . $mon_type . '_EnableReconnect';
$expired = time()+5184000;
$ca=dirname($_SERVER['SCRIPT_NAME']).'/build_mon.php';
if (isset($_POST['FitToScreen']))
   setcookie($cfts,  '1', $expired,$ca);
else
   setcookie($cfts,  '0', $expired,$ca);
if (isset($_POST['PrintCamNames']))
   setcookie($cnm,  '1', $expired,$ca);
else
   setcookie($cnm,  '0', $expired,$ca);
if (isset($_POST['EnableReconnect']))
   setcookie($ercnt,  '1', $expired,$ca);
else
   setcookie($ercnt,  '0', $expired,$ca);

for ($i=0;$i<$wins_nr;$i++) 
  if (isset($_POST['cams'][$i]))
     setcookie('avreg_' . $mon_type.'_cams['.$i.']',$_POST['cams'][$i],$expired,$ca);


$pageTitle = 'WebCam';
$body_style='overflow: hidden; padding: 0; margin: 0; width: 100%; height: 100%;';
$link_javascripts=array('lib/js/jquery-1.2.6.min.js');
$include_javascripts=array('online/view.js.php', 'online/view.js');
$body_addons='scroll="no"';
require ('../head.inc.php');
if ( !isset($cams) || !is_array($cams)) 
   MYDIE('not set cams',__FILE__,__LINE__);

/*
print '<pre>';
var_dump($cams);
var_dump($camnames);
print '</pre>'."\n";
die();
*/
?>

<div id="toolbar" style="position:absolute; height:25px; width:100%; margin:0; padding:0;background-color:#003366;overflow:hidden;">
<img src="<?echo $conf['prefix']; ?>/img/dvrlogo-134x25.png" width="134" height="25" align="left" border="0">
<table cellspacing="0" border="0" cellpadding="1" align="right">
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td><p style="color:white;font-weight:bold;"><script type="text/javascript" language="JavaScript1.2">br_spec_out();</script> &nbsp;&nbsp;Если Вы не видите изображение от видеокамер нажмите <a title="HELP" onclick="not_show(); return false;"  style="cursor: pointer; color:#FF9933;font-weight:bold;">здесь</a></p></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><input type="button" value="<? echo $strClose; ?>" class="btnNormal" onClick="window.close();"></td>
    </tr>
  </tbody>
</table>
</div>
<div id="canvas" style="position:absolute; top:25px; background-color:#000000; width:100%; height:0px; overflow:hidden; margin:0; padding:0;"></div>
<?php
require ('../foot.inc.php');
?>
