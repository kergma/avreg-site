<?php

/**
 * @file online/index.php
 * @brief Наблюдение в реальном времени
 * 
 * 
 * @page online Модуль наблюдения
 * Модуль наблюдения в реальном времени
 *  
 * Файлы модуля:
 * - online/index.php	
 * - online/build_mon.php
 * - online/view.php
 * - online/active_wc.inc.php
 */

$pageTitle = 'strWcMons';
$lang_file='_online.php';

require ('../head.inc.php');
print '<div align="center">';
if ( $user_status < $operator_status )
   print '<b><a href="'.$conf['prefix'].'/" target="_parent">'.$MainPage.'</a>&nbsp;&nbsp;::&nbsp;&nbsp;</b>';
print "$strYou: <a href='../index.php?logout=1' title='$strLogout'>$login_user@$remote_addr</a>";
print "</div>\n";
require ('../admin/mon-type.inc.php');

print '<div align="center">'. "\n";
echo '<h2 align="center">'.$strWcMons2.'</h3>' ."\n";

$wins = range(1, 25);
$lm = count($layouts_defs);
$mc = 5; // 5 столбцов
$mr = $lm / $mc;
if ( $lm % $mc )
   $mr++;
reset($layouts_defs);
print '<table cellspacing="0" border="0" cellpadding="5">'."\n";
for ($r=0; $r<$mr; $r++) {
   print '<tr>'."\n";
   for ($c=0; $c<$mc; $c++) {
      list($lname, $ldef ) = each($layouts_defs);
      print  '<td  align="center" valign="top">'."\n";
      if (empty($lname)) {
         print('&nbsp;');
      } else {
         printf('<a href="build_mon.php?mon_type=%s">%s&nbsp;&gt;&gt;</a>',$lname, $ldef[5]);
         layout2table ( $lname, 140, $wins );
      }
      print  '<br /></td>'."\n";
   }
   print '</tr>'."\n";
}
print '</table>'."\n";
print '</div>'."\n";
/* choice number cam */
if ( $user_status < $operator_status )
   print '<br><div align="center"><b><a href="'.$conf['prefix'].'/" target="_parent">'.$MainPage.'</a></b></div>'."\n";
require ('../foot.inc.php');
?>
