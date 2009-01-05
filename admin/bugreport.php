<?php
require ('../head.inc.php');
// require ('../lib/my_conn.inc.php');
?>

<script type="text/javascript" language="JavaScript">
<!--
function disable_submit_btn(btn) {
  btn.disabled=true;
  btn.value = '<?php echo $sWaitLogsDownload; ?>';
  return true;
}
// -->
</script>

<?php
echo '<h1>' . sprintf($r_bugs,$named,$sip) . '</h1>' ."\n";

print '<div class="Warn">' . $bugs_rules . '</div>' ."\n";
echo '<br />'."\n";
print '<form action="'.$conf['prefix'].'/admin/getlogs.php" method="GET" enctype="application/x-www-form-urlencoded">'."\n";
print "<span>$sMaxTailNum</span>\n";
?>
<select name="howmatch" size="1">
  <option value="1000">1000</option>
  <option value="3000" selected>3000</option>
  <option value="5000">5000</option>
  <option value="10000">10000</option>
</select>
<?php
print '<br /><br /><input type="submit" name="getlog" value="'.$strGetLogs.'" onclick="disable_submit_btn(this); return true;">'."\n";
print '</form>'."\n";


require ('../foot.inc.php');
?>
