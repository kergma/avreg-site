<?php
$pageTitle = 'WebCam';
$lang_file='_online.php';
$link_javascripts=array('lib/js/jquery-1.2.6.min.js');
$include_javascripts=array('online/build_mon.js');
if ( isset($_POST) && isset($_POST['wclist_show']) )
   $wclist_show = $_POST['wclist_show'];
if ( isset ($wclist_show) ) {
   settype($wclist_show, 'int');
   setcookie('avreg_wclist_show',  $wclist_show, time()+5184000);
} else if ( isset($_COOKIE['avreg_wclist_show']) ) {
   $wclist_show = (Integer)$_COOKIE['avreg_wclist_show'];
} else {
   $wclist_show = 1;
}
require ('../head.inc.php');
print '<div align="center"><b><a href="'.$conf['prefix'].'/" target="_parent">'.$MainPage.'</a></b></div>'."\n";
require ('./active_wc.inc.php');
if ($tot_wc_nr===0) {
  require ('../foot.inc.php');
  die();
}

require ('../admin/mon-type.inc.php');
if (!isset($mon_type) || empty($mon_type) || !array_key_exists($mon_type, $layouts_defs) ) 
   MYDIE("not set ot invalid \$mon_type=\"$mon_type\"",__FILE__,__LINE__);
$l_defs = &$layouts_defs[$mon_type];
$wins_nr = $l_defs[0];

print '<div align="center">'."\n";
print "<h3>$sWcDefLayout</h3>\n";
//var_dump($_COOKIE);
print '<form id="buildform" action="view.php" method="POST" onSubmit="return validate();" target="_blank">'."\n";
$ccams=$mon_type.'_cams';
if ( isset($_COOKIE['avreg_'.$ccams]) && is_array($_COOKIE['avreg_'.$ccams]) )
{
  $aaa = array();
  for ($i=0;$i<$wins_nr;$i++)
  {
    if (isset($_COOKIE['avreg_'.$ccams][$i])) 
       $a = $_COOKIE['avreg_'.$ccams][$i];
    else
       $a='';
    $aaa[$i] = getSelectByAssocAr('cams[]',$act_wc_nr_ar, FALSE , 1, 1, $a, TRUE, 'sel_change(this);', null, null, true);
  }
  layout2table ($mon_type, 400, $aaa);
} else {
	$a=getSelectByAssocAr('cams[]',$act_wc_nr_ar, FALSE , 1, 1, '', TRUE, 'sel_change(this);', null, null, true);
   layout2table ($mon_type, 400, NULL, $a);
}
for ($i = 0; $i < $wins_nr; $i++)
  print '<input type="hidden" name="camnames[]" value="" />'."\n";

print '<br /><table border="0" cellpadding="4">'."\n";

$car=$mon_type.'_AspectRatio';
if (isset($_COOKIE["avreg_$car"]))
   $AspectRatio = $_COOKIE["avreg_$car"];
else
   $AspectRatio = 'calc';
print '<tr><td align="right">'.$strAspectRatio.":</td>\n";
print '<td align="left">'.getSelectByAssocAr('AspectRatio', $AspectRatioArray, false , 1, 1, $AspectRatio, false)."</td></tr>\n";

$cpn=$mon_type.'_PrintCamNames';
if (isset($_COOKIE['avreg_'.$cpn]) && $_COOKIE['avreg_'.$cpn] != 0 )
    $PrintCamNames = 'checked';
else
   $PrintCamNames = '';
print '<tr><td align="right">'.$strPrintCamNames.":</td>\n";
print '<td align="left"><input type="checkbox" name="PrintCamNames" '.$PrintCamNames.' />'."</td></tr>\n";

if (false !== strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')) {
   $ercnt=$mon_type.'_EnableReconnect';
   if (isset($_COOKIE['avreg_'.$ercnt]) && $_COOKIE['avreg_'.$ercnt] != 0 )
      $EnableReconnect_checked = 'checked';
   else
      $EnableReconnect_checked = '';
   print '<tr><td align="right">'.$strEnableReconnect.":</td>\n";
   print '<td align="left"><input type="checkbox" name="EnableReconnect" '.$EnableReconnect_checked.' />'."</td></tr>\n";
}

$cblank=$mon_type.'_OpenInBlankPage';
if (isset($_COOKIE["avreg_$cblank"]) && $_COOKIE["avreg_$cblank"] != 0 )
   $OpenInBlankPage = 'checked';
else
   $OpenInBlankPage = '';
print '<tr><td align="right">'.$strOpenInBlank.":</td>\n";
print '<td align="left"><input type="checkbox" name="OpenInBlankPage" '.$OpenInBlankPage.' />'."</td></tr>\n";
print "</table>\n";
print '<br /><input type="submit" name="btnShow" value="'.$strShowCam.'" />'."\n";

print '<input type="hidden" name="mon_type" value="'.$mon_type.'" />'."\n";
print '</form>'."\n";
print '</div>'."\n";

print '<div align="center"><a href="./index.php">'.$strBack.'</a></div>'."\n";

require ('../foot.inc.php');
?>
