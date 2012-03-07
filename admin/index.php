<?php
$NOBODY=1;
$pageTitle = 'left_logo';
require('../head.inc.php');
DENY($admin_status);
print '<frameset border=1 framespacing=0 cols="200,*" rows="*">'."\n";
print '<frame src="'.$conf['prefix'].'/admin/menu.php?'.urlencode($_SERVER['QUERY_STRING']).'" align=right noresize marginheight=5 marginwidth=5 scrolling="auto" name="menu">'."\n";
print '<frame src="'.$conf['prefix'].'/admin/_index1.php?'.urlencode($_SERVER['QUERY_STRING']).'" marginheight=5 marginwidth=5 scrolling="auto" name="content">'."\n";
print '</frameset>'."\n";
require('../foot.inc.php');
?>
