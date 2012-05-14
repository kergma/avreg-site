<?php
/**
 * @file online/build_mon.php
 * @brief Создание раскладки для просмотра
 * 
 * 
 */

$pageTitle = 'WebCam';
$lang_file='_online.php';
$USE_JQUERY = true;
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
if ( $user_status < $operator_status )

   print '<div align="center"><b><a href="'.$conf['prefix'].'/" target="_parent">'.$MainPage.'</a></b></div>'."\n";
require ('./active_wc.inc.php');
if ($tot_wc_nr===0) {
	
   require ('../foot.inc.php');
   die();
}

require ('../admin/mon-type.inc.php');

if (!isset($mon_type)) 
  die('not set mon_type');
if (!isset($mon_type) || empty($mon_type) || !array_key_exists($mon_type, $layouts_defs) ) 
   MYDIE("not set ot invalid \$mon_type=\"$mon_type\"",__FILE__,__LINE__);
$l_defs = &$layouts_defs[$mon_type];
$wins_nr = $l_defs[0];

print '<div align="center">'."\n";
print "<h3>$sWcDefLayout</h3>\n";
//var_dump($_COOKIE);
$cookie_name = "avreg_$mon_type";
if ( !empty($_COOKIE[$cookie_name]) ) {
   $a = explode('-', $_COOKIE[$cookie_name]);
   $_cams_in_wins = explode('.', $a[0]);
   $OpenInBlankPage  = empty($a[1]) ? '' : 'checked';
   $PrintCamNames    = empty($a[2]) ? '' : 'checked';
   $EnableReconnect  = empty($a[3]) ? '' : 'checked';
   $AspectRatio = empty($a[4]) ? 'calc' : $a[4];
} else {
   $OpenInBlankPage  = '';
   $PrintCamNames    = 'checked';
   $EnableReconnect  = '';
   $AspectRatio      = 'calc';
}


print '<form id="buildform" action="view.php" method="POST" onSubmit="return validate();">'."\n";

if ( !empty($_cams_in_wins) && is_array($_cams_in_wins) ) {
   $aaa = array();
   for ($win_nr=0; $win_nr<$wins_nr; $win_nr++ ) {
      $b = empty($_cams_in_wins[$win_nr]) ? '' : (int)$_cams_in_wins[$win_nr];
      $aaa[$win_nr] = getSelectHtmlByName('cams_in_wins[]', $tot_act_cams_ar, FALSE , 1, 1, $b, TRUE, 'sel_change(this);', null, null, true);
   }
   layout2table ($mon_type, 400, $aaa);
} else {
   $a=getSelectHtmlByName('cams_in_wins[]',$tot_act_cams_ar, FALSE , 1, 1, '', TRUE, 'sel_change(this);', null, null, true);
   layout2table ($mon_type, 400, NULL, $a);
}

print '<br /><table border="0" cellpadding="4">'."\n";
print '<tr><td align="right">'.$strAspectRatio.":</td>\n";
print '<td align="left">'.getSelectByAssocAr('AspectRatio', $AspectRatioArray, false , 1, 1, $AspectRatio, false)."</td></tr>\n";

print '<tr><td align="right">'.$strPrintCamNames.":</td>\n";
print '<td align="left"><input type="checkbox" name="PrintCamNames" '.$PrintCamNames.' />'."</td></tr>\n";

if ( $MSIE ) {
   print '<tr><td align="right">'.$strEnableReconnect.":</td>\n";
   print '<td align="left"><input type="checkbox" name="EnableReconnect" '.$EnableReconnect.' />'."</td></tr>\n";
}

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
