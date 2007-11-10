<?php

require ('../head.inc.php');
DENY($arch_status);

if ( isset ($set) && !empty($comment) )
{
	require_once ('../lib/my_conn.inc.php');
	$query = sprintf('UPDATE PICTURIES SET COMMENT=\'%s\', savetime=\'%s\' WHERE CAM_NR=%d AND savetime=\'%s\'  AND FILENAME=\'%s\'',
			$comment, $saved, $cam, $saved, $src);
	// print '<code><font size="-2">'.$query.'</font></code>'."\n";
	mysql_query($query) or die('Query failed:`'.mysql_error().'`');
	require ('../lib/my_close.inc.php');
	print '<p>'.$strSetComment.'</p>'."\n";
}


if ( isset ($unset) && !empty($comment) )
{
	require_once ('../lib/my_conn.inc.php');
	$query = sprintf('UPDATE PICTURIES SET COMMENT=NULL, savetime=\'%s\' WHERE CAM_NR=%d AND savetime=\'%s\' AND FILENAME=\'%s\'',
			$saved, $cam, $saved, $src);
	// print '<code><font size="-2">'.$query.'</font></code>'."\n";
	mysql_query($query) or die('Query failed:`'.mysql_error().'`');
	require ('../lib/my_close.inc.php');
	$comment='';
	print '<p>'.$strUnSetComment.'</p>'."\n";
}



/*    
 print '<div id="form" style="vizible:false;position:relative;left:0px;top:95%;width:100%;height:5%;">'."\n";
	print '<form action="/offline/set-comment.php" method="POST" enctype="application/x-www-form-urlencoded" target="_blank">'."\n";
	if (empty($comment))
		print '&nbsp;&nbsp;'.$strComment.'&nbsp;&nbsp;<input type="text" name="comment" size="20" maxlength="20" style="background-color: #ffffcc;">'."\n";
	else
		print '&nbsp;&nbsp;'.$strComment.'&nbsp;&nbsp;<input type="text" name="comment" value="'.$comment.'" size="20" maxlength="20" style="background-color: #ffffcc;">'."\n";
	print '<input type="hidden" name="src" value="'.$src.'">'."\n";
	if (isset($scale) )
		print '<input type="hidden" name="scale" value="'.$scale.'">'."\n";
	print '<input type="hidden" name="cam" value="'.$cam.'">'."\n";
	print '<input type="hidden" name="saved" value="'.$saved.'">'."\n";
	print '<input type="submit" name="set" value="'.$strSave.'">'."\n";
	print '<input type="submit" name="unset" value="'.$strEmpty.'">'."\n";
 	print '</form>'."\n";
 print  '</div>'."\n";
*/



require ('../foot.inc.php');
?>
