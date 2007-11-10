<?php
require ('../head.inc.php');
DENY($admin_status);

require ('../lib/my_conn.inc.php');

?>

<script type="text/javascript" language="javascript">
<!--
function reset_to_list()
{
	window.open('<?php echo $conf['prefix']; ?>/admin/cam-list.php', target='_self');
}
// -->
</script>

<?php

		echo '<h1>' . sprintf($r_cam, $named, $sip) . '</h1>' ."\n";

if ( isset($cmd) && $cmd == '_ADD_NEW_CAM_' )
{
	if ( isset($cam_nr) && !empty($cam_nr) )
	{
		if ( ( $cam_nr < 1 ) || ( $cam_nr > $MAX_CAM ) ) 
		{
			$str_err_fmt = "<font color=\"$error_color\"><p>" . $strError . ": " . $strField . " '%s' %s</p></font>\n";
			printf ($str_err_fmt, $strCam_nr_Range, $cam_nr);
			require ('../lib/my_close.inc.php');
			require ('../foot.inc.php');
			exit;
		}
		/* insert CAMS with min PARAMS */
		settype($cam_nr, 'integer');
		$query = sprintf('INSERT INTO CAMERAS '.
		'( BIND_MAC, CAM_NR, PARAM, VALUE, CHANGE_HOST, CHANGE_USER) '.
		'VALUES ( \'local\', %d, \'%s\', \'%d\', \'%s\', \'%s\')',
		$cam_nr, 'work', 0, $remote_addr, $login_user);
		mysql_query($query) or die("Query failed");
		if (isset($cam_text) && !empty($cam_text))
		{
			$query = sprintf('INSERT INTO CAMERAS '.
					'( BIND_MAC, CAM_NR, PARAM, VALUE, CHANGE_HOST, CHANGE_USER) '.
					'VALUES ( \'local\', %d, \'%s\', \'%s\', \'%s\', \'%s\')',
			$cam_nr, 'text_left', $cam_text, $remote_addr, $login_user);
			mysql_query($query) or die("Query failed");
		}
		print ('<h4><font color="'.$warn_color.'">'.sprintf($r_cam_addnew_ok1, $cam_nr,$cam_text).'</font></h4>');
		print ('<h4><font color="'.$warn_color.'">'.$r_cam_addnew_ok2.'</font></h4>');
	} else {
		print ('<p><font color="'.$error_color.'">'.$strInvalidParams.'</font></p>');
	}
}

/* GET LAST NOT DEFINED CAM NUMBER */
/* Performing new SQL query */
		$query = 'SELECT MAX(CAM_NR) AS LAST_NUM FROM CAMERAS WHERE BIND_MAC=\'local\'';
$result = mysql_query($query) or die("Query failed");
$num_rows = mysql_num_rows($result);
if ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
	$cam_nr = $row['LAST_NUM'] + 1;
else 
	$cam_nr = 1;
mysql_free_result($result); $result = NULL;

echo '<h2>' . sprintf ($r_cam_addnew, $cam_nr, $named, $sip) . '</h2>' ."\n";
print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
print $strCam.': '.'<input type="text" name="cam_text" size=15 maxlength=15 value="Camera '.$cam_nr.'"><br>'."\n";
print '<input type="hidden" name="cmd" value="_ADD_NEW_CAM_">'."\n";
print '<input type="hidden" name="cam_nr" value="'.$cam_nr.'">'."\n";
print '<br><input type="submit" name="btn" value="'.$l_cam_addnew.'" >'."\n";
print '&nbsp;&nbsp;'."\n";
print '<input type="reset" name="btnRevoke" value="'.$strRevoke.'" onclick="reset_to_list();">'."\n";
print '</form>'."\n";

require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
