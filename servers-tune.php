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

if ( isset($cmd) && $cmd ===  'CHANGE_SERVER')
{
	if ( !isset($sip) || empty($sip) || !isset($named) || empty($named) || !isset($sip_old) || empty($sip_old) )
	{
		print '<font color="' . $error_color . '"><p>' . $strAddServerErr1 . '</p></font>' ."\n";
		print '<br><br><center><a href="javascript:window.history.back();"><img src="'.$conf['prefix'].'/img/undo_dark.png" alt="'.$strBack.'" width="24" hspace="24" border="0"></a><center>' ."\n";
		require ($wwwdir.'/lib/my_close.inc.php');
		require ($wwwdir.'/foot.inc.php');
		exit;
	}
	$long = ip2long($sip);
	$long_old = ip2long($sip_old);

	if ( $long === -1 || $long_old === -1 || $long_old === $localip )
	{
		print '<p><font color="' . $error_color . '">' . sprintf($fmtChangeServerErr1, $sip, $sip_old) . '</font></p>' ."\n";
		print '<br><br><center><a href="javascript:window.history.back();"><img src="'.$conf['prefix'].'/img/undo_dark.png" alt="'.$strBack.'" width="24" hspace="24" border="0"></a><center>' ."\n";
		require ($wwwdir.'/lib/my_close.inc.php');
		require ($wwwdir.'/foot.inc.php');
		exit;
	}
    
    if ($long === $long_old)
    {
    	$query = sprintf('UPDATE SERVERS '.
			 'SET NAMED=\'%s\', CHANGE_HOST=\'%s\', CHANGE_USER=\'%s\', CHANGE_TIME=NOW() '.
 			 'WHERE IP=%d',
			 $named, $remote_addr, $login_user, $long_old);       
    } else {
        // IP изменился
		$query = sprintf('UPDATE ARCH_CLEAN '.
               ' SET SERV_IP=%d, CHANGE_HOST=\'%s\', CHANGE_USER=\'%s\', CHANGE_TIME=NOW() '.
               ' WHERE SERV_IP=%d',
               $long, $remote_addr, $login_user, $long_old);
		mysql_query($query) or die(mysql_error().'<br />ARCH_CLEAN: '.$query);
		$query = sprintf('UPDATE CAMERAS '.
               ' SET SERV_IP=%d, CHANGE_HOST=\'%s\', CHANGE_USER=\'%s\', CHANGE_TIME=NOW() '.
               ' WHERE SERV_IP=%d',
               $long, $remote_addr, $login_user, $long_old);
		mysql_query($query) or die(mysql_error().'<br />CAMERAS: '.$query);
		$query = sprintf('UPDATE MONITORS '.
               ' SET SERV_IP=%d, CHANGE_HOST=\'%s\', CHANGE_USER=\'%s\', CHANGE_TIME=NOW() '.
               ' WHERE SERV_IP=%d',
               $long,$remote_addr, $login_user, $long_old);
		mysql_query($query) or die(mysql_error().'<br />MONITORS: '.$query);
		$query = sprintf('UPDATE PARAMS_DEF '.
               ' SET SERV_IP=%d, CHANGE_HOST=\'%s\', CHANGE_USER=\'%s\', CHANGE_TIME=NOW() '.
               ' WHERE SERV_IP=%d',
               $long,$remote_addr, $login_user, $long_old);
		mysql_query($query) or die(mysql_error().'<br />PARAMS_DEF: '.$query);                        
    	$query = sprintf('UPDATE SERVERS '.
			 'SET IP=%d, NAMED=\'%s\', CHANGE_HOST=\'%s\', CHANGE_USER=\'%s\', CHANGE_TIME=NOW() '.
 			 'WHERE IP=%d',
			 $long, $named, $remote_addr, $login_user, $long_old);      
    }
	//print ($query);
	$sip = long2ip($long);
	if ( mysql_query($query) )
	{
		print '<font color="' . $warn_color . '"><b><p>' . sprintf ($fmtServerChanged, $named_old, $sip_old) . '</p></b></font>' ."\n";
        print '<br><br><center><a href="javascript:reset_to_list();">'.$l_servers_list.'</a><center>' ."\n";
	} else {
		print '<font color="' . $error_color . '"><b><p>' . sprintf ($fmtChangeServerErr3, $named_old, $sip_old, mysql_error() ) . '</p></b></font>' ."\n";
		print '<br><br><center><a href="javascript:window.history.back();" target="content"><img src="'.$conf['prefix'].'/img/undo_dark.png" alt="'.$strBack.'" width="24" hspace="24" border="0"></a><center>' ."\n";
	}
} else {

	echo '<h2>' . $r_servers_change . '</h2>' ."\n";

	print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
	print '<table cellspacing=0 border=1 cellpadding=5>'."\n";
	print '<tr>'."\n";
	print '<td>'.$strHostName.'</td>'."\n";
	if ( !isset($sip) ) $sip='';
    if ($localip === ip2long($sip))
    print '<td><input type="text" name="sip" value="'.$sip.'" size="19" maxlength="19" readonly>'."\n";
      else
	print '<td><input type="text" name="sip" value="'.$sip.'" size="19" maxlength="19">'."\n";

	print '</tr>'."\n";
	print '<tr>'."\n";
	print '<td>'.$strHostText.'</td>'."\n";
	if ( !isset($named) ) $named='';
	print '<td><input type="text" name="named" value="'.$named.'" size="20" maxlength="20">'."\n";
	print '</tr>'."\n";
	print '</table>'."\n";
	print '<br>'."\n";
	print '<input type="hidden" name="sip_old" value="'.$sip.'">'."\n";
	print '<input type="hidden" name="named_old" value="'.$named.'">'."\n";
	print '<input type="hidden" name="cmd" value="CHANGE_SERVER">'."\n";
	print '<input type="submit" name="submit_btn" value="'.$strSave.'">'."\n";
	print '<input type="reset" name="reset_btn" value="'.$strRevoke.'" onclick="reset_to_list();">'."\n";
	print '</form>'."\n";
}

// phpinfo ();
require ($wwwdir.'/lib/my_close.inc.php');
require ($wwwdir.'/foot.inc.php');
?>
