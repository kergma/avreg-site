<?php
require ('../head.inc.php');
// require ('../lib/my_conn.inc.php');

echo '<font color="' . $inactive_h_color . '"><h1>' . sprintf($r_bugs,$named,$sip) . '</h1></font>' ."\n";

print '<p><font size="+1" color="' . $error_color . '">' . $bugs_rules . '</font></p>' ."\n";
echo '<br><br>'."\n";
print '<form action="/admin/getlogs.php" method="POST" enctype="application/x-www-form-urlencoded">'."\n";
?>
<select name="howmatch" size="1">
  <option value="1000" selected>1000</option>
  <option value="2000">2000</option>
  <option value="5000">5000</option>
  <option value="10000">10000</option>                                     
</select>
<?php
print '<input type="submit" name="getlog" value="'.$strGetLogs.'">'."\n";
print '</form>'."\n";

// phpinfo();
require ('../foot.inc.php');
?>
