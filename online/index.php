<?php
$pageTitle = 'WebCam';
$lang_file='_online.php';

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

print '<div align="center">'. "\n";
echo '<h4 align="center">'.$strWcMons.'</h4>' ."\n";

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
print '<br><br><div align="center"><b><a href="'.$conf['prefix'].'/" target="_parent">'.$MainPage.'</a></b></div>'."\n";
require ('../foot.inc.php');
?>
