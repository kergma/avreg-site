<?php
require ('./head.inc.php');
if ( !$install_user ) DENY();
require ($wwwdir.'/lib/my_conn.inc.php');
?>

<script type="text/javascript" language="javascript">
<!--
function reset_to_list()
{
	window.open('<?php echo $conf['prefix']; ?>/servers.php', target='_self');
}
// -->
</script>

<?php

echo '<font color="' . $inactive_h_color . '"><h1>' . $r_servers . '</h1></font>' ."\n";

if ( isset($cmd) && $cmd ===  'ADD_NEW_SERVER')
{
	if ( !isset($sip) || empty($sip) || !isset($named) || empty($named))
	{
		print '<font color="' . $error_color . '"><p>' . $strAddServerErr1 . '</p></font>' ."\n";
		print '<br><br><center><a href="javascript:window.history.back();"><img src="'.$conf['prefix'].'/img/undo_dark.png" alt="'.$strBack.'" width="24" hspace="24" border="0"></a><center>' ."\n";
		require ($wwwdir.'/lib/my_close.inc.php');
		require ($wwwdir.'/foot.inc.php');
		exit;
	}
	$ip   = gethostbyname($sip);
	$long = ip2long($ip);
	if ( $long === -1 )
	{
		print '<font color="' . $error_color . '"><p>' . sprintf($fmtAddServerErr2, $sip) . '</p></font>' ."\n";
		print '<br><br><center><a href="javascript:window.history.back();"><img src="'.$conf['prefix'].'/img/undo_dark.png" alt="'.$strBack.'" width="24" hspace="24" border="0"></a><center>' ."\n";
		require ($wwwdir.'/lib/my_close.inc.php');
		require ($wwwdir.'/foot.inc.php');
		exit;
	}
	$query = sprintf('INSERT INTO SERVERS '.
			 		 '( IP, NAMED, CHANGE_HOST, CHANGE_USER, CHANGE_TIME) '.
 			 		 'VALUES ( %d, \'%s\', \'%s\', \'%s\', NOW())',
					$long, $named, $remote_addr, $login_user);
	// print ($query);
	$ip = long2ip($long);
	if ( mysql_query($query) )
	{
		print '<font color="' . $warn_color . '"><b><p>' . sprintf ($fmtServerAdded, $named, $ip) . '</p></b></font>' ."\n";
		print '<br><br><center><a href="javascript:reset_to_list();">'.$l_servers_list.'</a><center>' ."\n";
	} else {
		print '<font color="' . $error_color . '"><b><p>' . sprintf ($fmtAddServerErr3, $named, $ip, mysql_error() ) . '</p></b></font>' ."\n";
		print '<br><br><center><a href="javascript:window.history.back();"><img src="'.$conf['prefix'].'/img/undo_dark.png" alt="'.$strBack.'" width="24" hspace="24" border="0"></a><center>' ."\n";
	}
} else {

	echo '<h2>' . $r_servers_add . '</h2>' ."\n";

	print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
	print '<table cellspacing=0 border=1 cellpadding=5>'."\n";
	print '<tr>'."\n";
	print '<td>'.$strHostName.'</td>'."\n";
	print '<td><input type="text" name="sip" size="19" maxlength="19">'."\n";
	print '</tr>'."\n";
	print '<tr>'."\n";
	print '<td>'.$strHostText.'</td>'."\n";
	print '<td><input type="text" name="named" size="20" maxlength="20">'."\n";
	print '</tr>'."\n";
	print '</table>'."\n";
	print '<br>'."\n";
	print '<input type="hidden" name="cmd" value="ADD_NEW_SERVER">'."\n";
	print '<input type="submit" name="submit_btn" value="'.$strSave.'">'."\n";
	print '<input type="reset" name="reset_btn" value="'.$strRevoke.'" onclick="reset_to_list();">'."\n";
	print '</form>'."\n";
}

// phpinfo ();
require ($wwwdir.'/lib/my_close.inc.php');
require ($wwwdir.'/foot.inc.php');
?>
