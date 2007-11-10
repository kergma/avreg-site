<?php
$pageTitle = 'PrName';
require ('./head.inc.php');
require ('./lib/my_conn.inc.php');

echo '<h1 align="center">' . $r_servers . '</h1>' ."\n";

if ( isset($cmd) )
{
	if ( !$install_user || empty($sip) ) DENY();
    $long = ip2long($sip);
    if ( $localip === $long ) DENY();
	switch ( $cmd ) {
		case 'DEL':
			echo '<p><font color="' . $warn_color . '">' . sprintf ($fmtDeleteServerConfirm, $named, $sip) . '</font></p>' ."\n";
			print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
			print '<input type="hidden" name="cmd" value="DEL_OK">'."\n";
    		print '<input type="hidden" name="sip" value="'.$sip.'">'."\n";
			print '<input type="hidden" name="named" value="'.$named.'">'."\n";
			print '<input type="submit" name="mult_btn" value="'.$strYes.'">'."\n";
			print '<input type="submit" name="mult_btn" value="'.$strNo.'">'."\n";
 			print '</form>'."\n";
			require ('./lib/my_close.inc.php');
			require ('./foot.inc.php');
			exit;
			break; /**/
		case 'DEL_OK':
			if ( ($mult_btn === $strYes) && isset($sip) && !empty($sip))
			{
				$long = ip2long($sip);
				$query = sprintf('DELETE FROM ARCH_CLEAN WHERE SERV_IP=%d', $long);
				mysql_query($query) or die("Query failed");
				$query = sprintf('DELETE FROM CAMERAS WHERE SERV_IP=%d', $long);
				mysql_query($query) or die("Query failed");
				$query = sprintf('DELETE FROM MONITORS WHERE SERV_IP=%d', $long);
				mysql_query($query) or die("Query failed");
				$query = sprintf('DELETE FROM PARAMS_DEF WHERE SERV_IP=%d', $long);
				mysql_query($query) or die("Query failed");
				$query = sprintf('DELETE FROM SERVERS WHERE IP=%d', $long);
				mysql_query($query) or die("Query failed");                
				echo '<p><font color="' . $warn_color . '">' . sprintf ($fmtServerDeleted, $named, $sip) . '</font></p>' ."\n";
			}
			unset ($sip);
		break;
	}
}

echo '<center><div class="help" style="width:500px;text-align:left">'.$r_servers_hlp.'</div></center>'."\n";

$query = 'SELECT IP, NAMED, CHANGE_HOST, CHANGE_USER, CHANGE_TIME '.
		 'FROM SERVERS';
$result = mysql_query($query) or die('Query failed: `'. $query . '`');
$num_rows = mysql_num_rows($result);
if ( $num_rows > 0 )
{
	if ( $install_user )
		print '<p align="center"><a href="'.$conf['prefix'].'/" target="_parent">'.$MainPage.'</a></p>'."\n";
	print '<table cellspacing="0" border="1" cellpadding="3" align="center">' . "\n";
	print '<tr bgcolor="'.$header_color.'">'."\n";
	print '<th>&nbsp;</th>'."\n";
	if ( $install_user )
	{
		print '<th>&nbsp;</th>'."\n";
		print '<th>&nbsp;</th>'."\n";
	}
	print '<th nowrap>'.$strIpAddr.'</th>'."\n";
	print '<th>'.$strName.'</th>'."\n";
	print '<th>'.$strUpdateControl.'</th>'."\n";
	print '</tr>'."\n";
	$r_count = 0;
	while ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
	{
		$ip = long2ip($row['IP']);
		if ( $localip === (int)$row['IP'] )
			print '<tr bgcolor="'.$rowHiLight.'">'."\n";
		else
			print '<tr>'."\n";
		if ( $install_user )
		{
           if ( $localip !== intval($row['IP']) ) {
			 $a_del = sprintf ('%s?cmd=DEL&sip=%s&named=%s', 
                       $_SERVER['PHP_SELF'], urlencode ($ip), urlencode ($row['NAMED']));
		     print '<td><a href="'.$a_del.'">'. $strDelete . '</a></td>' . "\n";
           } else {
             print '<td>&nbsp;</td>' . "\n";
           }
           
           $a_change = sprintf ('/servers-tune.php?sip=%s&named=%s',
                                   urlencode ($ip), urlencode ($row['NAMED']));
           print '<td><a href="'.$a_change.'">'. $strChange . '</a></td>' . "\n";
		} else {
			print '<td>&nbsp;</td>' . "\n";
			print '<td>&nbsp;</td>' . "\n";
        }
        $a_enter = sprintf ('/admin/index.php?sip=%s&named=%s', urlencode ($ip), urlencode ($row['NAMED']));
/*
        if ( preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',$_SERVER["SERVER_NAME"]) )
			$a_enter = sprintf ('http://%s/admin/index.php', $ip);
		else
			$a_enter = sprintf ('http://%s/admin/index.php', urlencode(gethostbyaddr($ip)));
*/
		if ( $localip === (int)$row['IP'] )
			print "\t\t" . '<td align="center"><a href="'.$a_enter.'"><img src="'.$conf['prefix'].'/img/comps/local-server.gif" TITLE="'.$strServerEnter.'" width="48" height="48" border="0"><br>'.$strServerEnter.'</a></td>' . "\n";
		else
			print "\t\t" . '<td align="center"><a href="'.$a_enter.'"><img src="'.$conf['prefix'].'/img/comps/network-server.gif" TITLE="'.$strServerEnter.'" width="48" height="48" border="0"><br>'.$strServerEnter.'</a></td>' . "\n";
		print "\t\t" . '<td valign="center" nowrap><b>'. $ip . '</b></td>' . "\n";
		print "\t\t" . '<td valign="center" nowrap><b>'. $row['NAMED'] . '</b></td>' . "\n";
		$ts = $row['CHANGE_TIME'];
		$ut = mktime (substr($ts,8,2),substr($ts,10,2),substr($ts,12,2), substr($ts,4,2), substr($ts,6,2) ,substr($ts,0,4));
		print "\t\t" . '<td>'. $row['CHANGE_HOST'] . '<br>' .$row['CHANGE_USER'] . '<br>' . strftime ( "%d.%m.%y %H:%M" , $ut) .'</td>' . "\n";
		print "\t</tr>\n";
	}
	print '</table><br>'."\n";
	if ( $install_user )
		print '<p align="center"><a href="servers-addnew.php">'.$l_servers_addnew.'</a></p>'."\n";
} else {
	print '<p><b>' . $strNotServersDef . '</b></p>' . "\n";
	print '<p align="center"><a href="servers-addnew.php">'.$l_servers_addnew.'</a></p>'."\n";
}
mysql_free_result($result); $result = null;

//phpinfo ();
require ('./lib/my_close.inc.php');
require ('./foot.inc.php');
?>
