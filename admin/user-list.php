<?php
$lang_file='_admin_users.php';
require ('../head.inc.php');
require ('../lib/my_conn.inc.php');

echo '<h1>' . sprintf($r_users, $named, $sip) . '</h1>' ."\n";

if ( isset($cmd) )
{
   $ui = get_user_info($u_host, $u_name);
   if ( $ui === FALSE )
      die('crack?');
   $u_status = $ui['STATUS'];
   if ( !$admin_user || !($user_status < $u_status) ) die ('Crack or hack???');
   switch ( $cmd ) {
	case 'DEL':
		echo '<p class="HiLiteBigWarn">' . sprintf ($fmtDeleteUserConfirm, $u_name,$u_host,$grp_ar[$u_status]['grname']) . '</p>' ."\n";
		print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
		print '<input type="hidden" name="cmd" value="DEL_OK">'."\n";
    		print '<input type="hidden" name="u_host" value="'.$u_host.'">'."\n";
		print '<input type="hidden" name="u_name" value="'.$u_name.'">'."\n";
		print '<input type="submit" name="mult_btn" value="'.$strYes.'">'."\n";
		print '<input type="submit" name="mult_btn" value="'.$strNo.'">'."\n";
 		print '</form>'."\n";
		require ('../foot.inc.php');
		exit;
		break; /**/
	case 'DEL_OK':
		if ( ($mult_btn == $strYes) && isset($u_host) && isset($u_name) && isset($u_status))
		{
			$query = sprintf('DELETE FROM USERS WHERE USER="%s" AND HOST="%s" AND STATUS=%u',
			 $u_name, $u_host, $u_status);
	        	mysql_query($query) or die("Query failed");
		       echo '<p class="HiLiteBigWarn">' . sprintf ($fmtDeleteUser, $u_name,$u_host) . 
                  '</p>' ."\n";
		}
		unset ($u_name);
	break;
   }
}

if ( !isset($u_name) || empty($u_name) )
{
	/* Performing new SQL query */
    
	/* Printing results in HTML */
	echo '<h3>' . $r_user_groups . '</h3>' ."\n";
	print '<table cellspacing=0 border=1 cellpadding=5>' . "\n";
	print '<tr bgcolor="'.$header_color.'">'."\n";
    reset($grp_ar);
	while ( list ( $grp_status, $groups ) = each ($grp_ar)  )
      print '<th><a href="#'.$grp_status.'">'.$groups['grname'].'</th>'."\n";
	print '</tr></table>'."\n";

	reset($grp_ar);
	while (list ( $grp_status, $groups ) = each ($grp_ar) )
	{
		// next($grps);
		// print '<pre>'.$grp_status.'/'.$groups.'</pre>'."\n";
		$query = 'SELECT HOST, USER, LONGNAME, CHANGE_HOST, CHANGE_USER, CHANGE_TIME '.
				 'FROM USERS '.
				 "WHERE STATUS = $grp_status ".
				 'ORDER BY HOST, USER';
		$result = mysql_query($query) or die('Query failed: `'. $query . '`');
		$num_rows = mysql_num_rows($result);
		switch ($grp_status)
		{
			case 1: $bashnia = '<img src="'.$conf['prefix'].'/img/mozilla-icon.gif" border=0>'; break;
			case 2: $bashnia = '<img src="'.$conf['prefix'].'/img/gnome-xbill.gif" border=0>'; break;
			case 3: $bashnia = '<img src="'.$conf['prefix'].'/img/gnome-gnobots2.gif" border=0>'; break;
			case 4: $bashnia = '<img src="'.$conf['prefix'].'/img/gnome-eyes.gif" border=0>'; break;
			default:
				$bashnia = '<img src="'.$conf['prefix'].'/img/nobody.gif" border=0>';
		}
		echo '<h4><a name="'.$grp_status.'" class="HiLite">'
             .$groups['grname'].'</a>.  '.$groups['grdesc']. '</h4>' ."\n";
		if ( $num_rows > 0 )
		{
			print '<table cellspacing=0 border=1 cellpadding=3>' . "\n";
			print '<tr bgcolor="'.$header_color.'">'."\n";
			print '<th>&nbsp;</th>'."\n";
		    print '<th>&nbsp;</th>'."\n";
			print '<th>&nbsp;</th>'."\n";
			print '<th nowrap>'.$strLoginName.'</th>'."\n";
			print '<th>'.$strHost.'</th>'."\n";
			print '<th>'.$FIO.'</th>'."\n";
			print '<th>'.$strUpdateControl.'</th>'."\n";
			print '</tr>'."\n";
			$r_count = 0;
			while ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
			{
				print '<tr>'."\n";
				print '<td>'.$bashnia.'</td>' . "\n";
				$a_del = sprintf ('%s?cmd=DEL&u_name=%s&u_host=%s',
							$_SERVER['PHP_SELF'],
							urlencode ($row['USER']),
							urlencode ($row['HOST']));
				$a_change = sprintf('./user-tune.php?u_name=%s&u_host=%s',
                                urlencode($row['USER']), urlencode($row['HOST']));
                if ( $row['USER'] == $login_user )
                {
                    print '<td>&nbsp;</td>';
			        print '<td><a href="'.$a_change.'">'. $strChange . '</a></td>' . "\n";
                } else {
                	if ( $admin_user && $user_status < $grp_status)
                    {
				        print '<td><a href="'.$a_del.'">'. $strDelete . '</a></td>' . "\n";
                        print '<td><a href="'.$a_change.'">'. $strChange . '</a></td>' . "\n";                              } else {
                       print ('<td>&nbsp;</td>');
                       print ('<td>&nbsp;</td>');
                    }
				}
                
				print '<td valign="center" nowrap><b>'. $row['USER'] . '</b></td>' . "\n";
				print '<td valign="center" nowrap><b>'. $row['HOST'] . '</b></td>' . "\n";
				print '<td>'. htmlspecialchars( $row['LONGNAME'] ) . '</td>' . "\n";
				$ts = $row['CHANGE_TIME'];
				$ut = mktime (substr($ts,8,2),substr($ts,10,2),substr($ts,12,2), substr($ts,4,2), substr($ts,6,2) ,substr($ts,0,4));
				print '<td>'. $row['CHANGE_HOST'] . '<br>' .$row['CHANGE_USER'] . '<br>' . strftime ( "%d.%m.%y %H:%M" , $ut) .'</td>' . "\n";
				print "</tr>\n";
			}
			print '</table><br>'."\n";
		} else {
			print '<p><b>' . $strNotUserDef . '</b></p>' . "\n";
		}
        if ( $admin_user && $user_status < $grp_status )
           print '<div><a href="'.$conf['prefix'].'/admin/user-addnew.php?status='.$grp_status.
                 '">+ '.$l_user_addnew.' +</a></div>'."\n";
		mysql_free_result($result); $result = null;
		print '<div align="center"><a href="#top">'.$strUp.'</a></div>'."\n";
		print '<hr align="center" noshade>'."\n";
	} // while (list ( $grp_status, $groups ) = $grps )
}

//phpinfo ();
require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
