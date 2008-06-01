<?php
$lang_file='_admin_users.php';
require ('../head.inc.php');
require ('../lib/my_conn.inc.php');
?>

<script type="text/javascript" language="javascript">
<!--
function reset_to_list()
{
	window.open('<?php echo $conf['prefix']; ?>/admin/user-list.php', target='_self');
}
// -->
</script>

<?php

echo '<h1>' . sprintf($r_users, $named, $sip) . '</h1>' ."\n";

if ( isset($cmd) )
{
	if ( strcmp($u_name, $login_user) || strcmp($u_host, $login_host) ) die ('Crack or hack???');
	switch ( $cmd ) {
		case 'UPDATE_PASSWD':
        	//print "<p>old_pass='$u_pass'<br>user_passwd='$u_pass2'</p>\n";
			$u_pass = trim($u_pass);
			$u_pass2 = trim($u_pass2);
			if ( !preg_match ( $patternPasswd, $u_pass) )
            {
				print '<p class="HiLiteErr">' . sprintf ($fmtPasswdBadChar, $u_name, $u_host) .
                 '</p>' ."\n";
                print_go_back();
				require ('../foot.inc.php');
				exit;
            }
			if ( strcmp($u_pass,$u_pass2) ) {
				echo '<p class="HiLiteErr">' . $strPassNotPass2. '</p>' ."\n";
				print '<p class="HiLiteErr">' . $strAddUserErr1 . '</p>' ."\n";
                print_go_back();
			} else {
                            if ( $u_host === '127.0.0.1' || $u_host === 'localhost' )
                               $host_cond = '(HOST=\'127.0.0.1\' OR HOST=\'localhost\')';
                            else
                                $host_cond = sprintf('\HOST=\'%s\'',$u_host);
				$query = sprintf("SELECT PASSWD FROM USERS WHERE %s AND USER='%s'", $host_cond, $u_name);
				$result = mysql_query($query) or die('Query failed: `'. $query . "`\n");
				if ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
					$user_passwd = $row['PASSWD'];
				else die ("Error\n");
				mysql_free_result($result); $result = NULL;
				$z = crypt($old_pass, $user_passwd);
				//print "<p>old_pass='$old_pass'<br>user_passwd='$user_passwd'<br>crypt='$z'</p>\n";
				if ( ( $user_passwd == '' && $old_pass == '') ||
					 $z === $user_passwd )
				{
					if ( strcmp($old_pass, $u_pass ) )
					{
						$query = sprintf("UPDATE USERS SET PASSWD=ENCRYPT('%s') WHERE %s AND USER='%s'",
								 $u_pass,
								 $host_cond,
							 	$u_name);
						//print ($query);
						if ( mysql_query($query) ) {
							if ( 1 == mysql_affected_rows () )
							{
								print '<p class="HiLiteWarn">' . sprintf ($fmtPasswdUpdated, $u_name, $u_host) . '</p>' ."\n";
								print '<br><center><a href="'.$conf['prefix'].'/admin/user-list.php">'.$l_user_list.'</a><center>' ."\n";
							} else {
								print '<p class="HiLiteErr">' . sprintf ($fmtPasswdUpdated2, $u_name, $u_host) . '</p>' ."\n";
                                print_go_back();
							}
						} else {
								print '<p class="HiLiteErr">' . sprintf ($fmtPasswdUpdated3, $u_name, $u_host, mysql_error() ) . '</p>' ."\n";
                                print_go_back();
						}
                    } else {
						print '<p class="HiLiteWarn">' . sprintf ($fmtPasswdUpdated, $u_name, $u_host) . '</p>' ."\n";
						print '<br><center><a href="'.$conf['prefix'].'/admin/user-list.php">'.$l_user_list.'</a><center>' ."\n";
 					}
				} else {
					print '<p class="HiLiteErr">' . sprintf ($fmtPasswdUpdated2, $u_name, $u_host) . '</p>' ."\n";
                    print_go_back();
				}
			}
			require ('../foot.inc.php');
			exit;
			break; /**/
	}
}

echo '<h2>' . sprintf ($fmtUserPasswd, $login_user, $login_host, $login_user_name ) . '</h2>' ."\n";

print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
print '<table cellspacing=0 border=1 cellpadding=5>'."\n";
print '<tr>'."\n";
print '<td>'.$strOld.' '.$strPassword.'</td>'."\n";
print '<td><input type="password" name="old_pass" size="16" maxlength="16">'.'&nbsp;</td>'."\n";
print '</tr>'."\n";
print '<tr>'."\n";
print '<td>'.$strNew.' '.$strPassword.'</td>'."\n";
print '<td><input type="password" name="u_pass" size="16" maxlength="16">'.'&nbsp;</td>'."\n";
print '</tr>'."\n";
print '<tr>'."\n";
print '<td>'.$strNew.' '.$strPassword2.'</td>'."\n";
print '<td><input type="password" name="u_pass2" size="16" maxlength="16">'.'&nbsp;</td>'."\n";
print '</tr>'."\n";
print '</table>'."\n";
print '<br>'."\n";
print '<input type="hidden" name="cmd" value="UPDATE_PASSWD">'."\n";
print '<input type="hidden" name="u_name" value="'.$login_user.'">'."\n";
print '<input type="hidden" name="u_host" value="'.$login_host.'">'."\n";
print '<input type="submit" name="submit_btn" value="'.$strChange.'">'."\n";
print '<input type="reset" name="reset_btn" value="'.$strRevoke.'" onclick="reset_to_list();">'."\n";
print '</form>'."\n";

require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
