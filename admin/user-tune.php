<?php
require ('../head.inc.php');
DENY($admin_status);
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
  require('user-check.inc.php');
  switch ( $cmd )
  {
     case 'UPDATE_USER':
        $query = sprintf(
        'UPDATE USERS '.
        'SET HOST=\'%s\', USER=\'%s\', PASSWD=encrypt(\'%s\'), STATUS=%d, '.
        'LONGNAME=\'%s\', CHANGE_HOST=\'%s\', CHANGE_USER=\'%s\', CHANGE_TIME=NOW() '.
        'WHERE HOST=\'%s\' AND USER=\'%s\'',
        $u_host,$u_name,$u_pass,$groups,
        $u_longname,$remote_addr,$login_user,
        $old_u_host,$old_u_name);
        break;
     default:
        die('crack');
  }
  // print ($query);
  if ( mysql_query($query) )
  {
      print '<p class="HiLiteWarn">' . sprintf ($fmtUserUpdated, $u_name, $u_host) . '</p>' ."\n";
      print '<br><center><a href="'.$conf['prefix'].'/admin/user-list.php">'.$l_user_list.'</a><center>' ."\n";
  } else {
      print '<p class="HiLiteErr">'.sprintf ($fmtUserUpdated2, $u_name, $u_host, mysql_error() ).
            '</p>' ."\n";
      print '<br><center><a href="javascript:window.history.back();" title="'.$strBack.'">'.
            '<img src="'.$conf['prefix'].'/img/undo_dark.gif" alt="'.$strBack.
            '" width="24" hspace="24" border="0"></a></center>' ."\n";
  }
  unset($u_name);
}

if ( isset($u_name) && !empty($u_name) )
{
   if (isset($u_status))
   {
     if (!settype($u_status,'int'))
       die();
   } else {
      $u_status=-1;
   }

	echo '<h2>' . sprintf ($fmtUserTune,$u_name,$u_host) . '</h2>' ."\n";
	print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
	print '<table cellspacing=0 border=1 cellpadding=5>'."\n";
	print '<tr>'."\n";
 	print '<td>'.$strName1.'</td>'."\n";
	print '<td><input type="text" name="u_name" value="'.$u_name.'" size="16" maxlength="16">'."\n";
	print '</tr>'."\n";
	print '<tr>'."\n";
 	print '<td>'.$strAllowHost.'</td>'."\n";
	print '<td><input type="text" name="u_host" value="'.$u_host.'" size="40" maxlength="60">'."\n";
	print '</tr>'."\n";
	print '<tr>'."\n";
 	print '<td>'.$FIO.'</td>'."\n";
	print '<td><input type="text" name="u_longname" value="'.stripslashes (htmlspecialchars($u_longname)).'" size="40" maxlength="50">'."\n";
	print '</tr>'."\n";
	print '<tr>'."\n";
 	print '<td>'.$strPassword.'<br>'.$strPasswordAllowed.'</td>'."\n";
	print '<td><input type="password" name="u_pass" size="16" maxlength="16">'."\n";
	print '</tr>'."\n";
	print '<tr>'."\n";
 	print '<td>'.$strPassword2.'</td>'."\n";
	print '<td><input type="password" name="u_pass2" size="16" maxlength="16">'."\n";
	print '</tr>'."\n";
	print '<tr>'."\n";
 	print '<td>'.$str_group1.'</td>'."\n";
	print '<td>'."\n";
    reset($grp_ar);
	while (list ( $gr_status, $groups ) = each ($grp_ar) )
    {
       if ( $user_status > $gr_status ) 
          $addons='disabled';
       else if ($u_status === $gr_status)
          $addons='checked'; 
       else
          $addons='';
       print '<input type="radio" name="groups" '. $addons.
              ' value="'.$gr_status.'">'.$grp_ar[$gr_status]['grname'].'<br>'."\n";
	}
	print '</td>'."\n";
	print '</tr>'."\n";
	print '</table>'."\n";
	print '<br>'."\n";
	print '<input type="hidden" name="cmd" value="UPDATE_USER">'."\n";
	print '<input type="hidden" name="old_u_name" value="'.$u_name.'">'."\n";
	print '<input type="hidden" name="old_u_host" value="'.$u_host.'">'."\n";
	print '<input type="submit" name="submit_btn" value="'.$strSave.'">'."\n";
	print '<input type="reset" name="reset_btn" value="'.$strRevoke.'" onclick="reset_to_list();">'."\n";
	print '</form>'."\n";
}

// phpinfo ();
require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
