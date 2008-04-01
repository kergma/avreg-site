<?php
$pageTitle = 'WebCam';
require ('../head.inc.php');
print '<div align="center"><b><a href="'.$conf['prefix'].'/" target="_parent">'.$MainPage.'</a></b></div>'."\n";
require ('./active_wc.inc.php');
if ($tot_wc_nr===0) {
  require ('../foot.inc.php');
  die();
}
require ('../admin/mon-type.inc.php');


if (!isset($mon_type) || empty($mon_type)) 
   MYDIE('not set $mon_type',__FILE__,__LINE__);

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
    MYDIE("unknown mon_type=$mon_type",__FILE__,__LINE__);  
}
?>

<script type="text/javascript" language="JavaScript1.2">
<!--
function validate(){
   var cams_selects = document.getElementsByName('cams[]');
   var camnames_inputs = document.getElementsByName('camnames[]');
   if (typeof(cams_selects) == 'undefined' || typeof(camnames_inputs) == 'undefined') 
     return false;
   var cams_select=null;
   var i;
   var choised=0;
   for(i=0;i<cams_selects.length;i++) {
      cams_select = cams_selects[i];
      if (cams_select.selectedIndex>0 )
      {
        choised++;
        camnames_inputs[i].value=CNAMES[cams_select.selectedIndex-1];
      }
   }
   if (choised>0)
     return true;
   else {
     alert('Сначала Вы должны выбрать камеры для просмотра в форме.');
     return false;
   }
}

function sel_change(sel) {
  if (sel.selectedIndex==0)
    return true;
  var cams_selects = document.getElementsByName('cams[]');
  if (typeof(cams_selects) == 'undefined') 
     return false;
   var cams_select=null;
   var i;
   var choised=0;
   for(i=0;i<cams_selects.length;i++) {
      cams_select = cams_selects[i];
      if (cams_select != sel)
      {
         if ( cams_select.selectedIndex == sel.selectedIndex ) {
            alert(sel.options[sel.selectedIndex].text + "\n\n" + ' уже выбрана  в другом окне' );
            sel.selectedIndex=0;
            break;
         }
      }
   }  
  return false;
}
// -->
</script>

<?php
print '<div align="center">'."\n";
//var_dump($_COOKIE);
print '<form id="buildform" action="view.php" method="POST" onSubmit="return validate();"  target="_blank">'."\n";
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
    $aaa[$i] = getSelectHtmlByName('cams[]',$act_wc_nr_ar, FALSE , 1, 1, $a, TRUE, 'sel_change(this);');
  }
   show_mon_type ($mon_type, 0, $aaa);
} else {
   $a=getSelectHtmlByName('cams[]',$act_wc_nr_ar, FALSE , 1, 1, '', TRUE, 'sel_change(this);');
   show_mon_type ($mon_type, 0, NULL, $a);
}
for ($i = 0; $i < $wins_nr; $i++)
  print '<input type="hidden" name="camnames[]" value="" />'."\n";

$cfts=$mon_type.'_FitToScreen';
if (isset($_COOKIE['avreg_'.$cfts]) && $_COOKIE['avreg_'.$cfts] != 0 )
    $FitToScreen_checked = 'checked';
else
   $FitToScreen_checked = '';
print '<br>'.$strFitToScreen.': <input type="checkbox" name="FitToScreen" '.$FitToScreen_checked.' />'."\n";

$cpn=$mon_type.'_PrintCamNames';
if (isset($_COOKIE['avreg_'.$cpn]) && $_COOKIE['avreg_'.$cpn] != 0 )
    $PrintCamNames = 'checked';
else
   $PrintCamNames = '';
print '<br>'.$strPrintCamNames.': <input type="checkbox" name="PrintCamNames" '.$PrintCamNames.' />'."\n";

print '<br><br><input type="submit" name="btnShow" value="'.$strShowCam.'" />'."\n";
print '<input type="hidden" name="mon_type" value="'.$mon_type.'" />'."\n";
print '</form>'."\n";
print '</div>'."\n";

print '<div align="center"><a href="./index.php">'.$strBack.'</a></div>'."\n";

require ('../foot.inc.php');
?>
