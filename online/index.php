<?php
$pageTitle = 'WebCam';
$lang_file='_online.php';
require ('../head.inc.php');

print '<div align="center"><b><a href="'.$conf['prefix'].'/" target="_parent">'.$MainPage.'</a></b></div>'."\n";
require ('./active_wc.inc.php');
if ($tot_wc_nr===0) {
  require ('../foot.inc.php');
  die();
}
require ('../admin/mon-type.inc.php');

print '<div align="center">'. "\n";
echo '<h4 align="center">'.$strWcMons.'</h4>' ."\n";

$wins = array ('@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@','@');
print '<table cellspacing="0" border="0" cellpadding="2">'."\n";

print '<tr>'."\n";
print '<td align="center"><a href="build_mon.php?mon_type=ONECAM">'.$strONECAM.'&nbsp;&gt;&gt;</a></td>'."\n";
print '<td align="center"><a href="build_mon.php?mon_type=QUAD_4_4">'.$strQUAD_4_4.'&nbsp;&gt;&gt;</a></td>'."\n";
print '<td align="center"><a href="build_mon.php?mon_type=POLY_3_2">'.$strPOLY_3_2.'&nbsp;&gt;&gt;</a></td>'."\n";
print '<td align="center"><a href="build_mon.php?mon_type=POLY_4_2">'.$strPOLY_4_2.'&nbsp;&gt;&gt;</a></td>'."\n";    
print '</tr>'."\n";

print '<tr>'."\n";
print '<td valign="top">';
	show_mon_type ( 'ONECAM',   100, $wins);
print '</td>'."\n";
print '<td valign="top">';
	show_mon_type ( 'QUAD_4_4', 100, $wins);
print '</td>'."\n";
print '<td valign="top">';
	show_mon_type ( 'POLY_3_2', 100, $wins);
print '</td>'."\n";
print '<td valign="top">';
	show_mon_type ( 'POLY_4_2', 100, $wins);
print '</td>'."\n";
print '</tr>'."\n";

print '<tr><td colspan="4">&nbsp;</td></tr>'."\n";

print '<tr>'."\n";
print '<td align="center"><a href="build_mon.php?mon_type=QUAD_9_9">'.$strQUAD_9_9.'&nbsp;&gt;&gt;</a></td>'."\n";
print '<td align="center"><a href="build_mon.php?mon_type=POLY_4_3">'.$strPOLY_4_3.'&nbsp;&gt;&gt;</a></td>'."\n";
print '<td align="center"><a href="build_mon.php?mon_type=QUAD_16_16">'.$strQUAD_16_16.'&nbsp;&gt;&gt;</a></td>'."\n";
print '<td align="center"><a href="build_mon.php?mon_type=QUAD_25_25">'.$strQUAD_25_25.'&nbsp;&gt;&gt;</a></td>'."\n";
print '</tr>'."\n";

print '<tr>'."\n";
print '<td valign="top">';
	show_mon_type ( 'QUAD_9_9',   100, $wins);
print '</td>'."\n";
print '<td valign="top">';
	show_mon_type ( 'POLY_4_3', 100, $wins);
print '</td>'."\n";
print '<td valign="top">';
	show_mon_type ( 'QUAD_16_16', 100, $wins);
print '</td>'."\n";
print '<td valign="top">';
	show_mon_type ( 'QUAD_25_25', 100, $wins);
print '</td>'."\n";
print '</tr>'."\n";


print '</table>'."\n";
print '</div>'."\n";
/* choice number cam */
print '<br><br><div align="center"><b><a href="'.$conf['prefix'].'/" target="_parent">'.$MainPage.'</a></b></div>'."\n";
require ('../foot.inc.php');
?>
