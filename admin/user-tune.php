<?php
$lang_file='_admin_users.php';
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
         if ( 0 === strcmp($u_pass, $old_u_passwd) )
            $passwd_changed = '';
         else
            $passwd_changed = sprintf('PASSWD=encrypt(\'%s\'), ', $u_pass);
      $query = sprintf(
      'UPDATE USERS '.
      'SET HOST=%s, USER=%s, %s STATUS=%d, '.
      'ALLOW_CAMS=%s, LIMIT_FPS=%s, LIMIT_KBPS=%s, '.
      'LONGNAME=%s, CHANGE_HOST=%s, CHANGE_USER=%s, CHANGE_TIME=NOW() '.
      'WHERE HOST=%s AND USER=%s',
      sql_format_str_val($u_host), sql_format_str_val($u_name),
      $passwd_changed,
      $groups,
      sql_format_str_val($u_devacl),
      sql_format_int_val($limit_fps),
      sql_format_int_val($limit_kbps),
      sql_format_str_val($u_longname),sql_format_str_val($remote_addr),sql_format_str_val($login_user),
      sql_format_str_val($old_u_host),sql_format_str_val($old_u_name));
      break;
   default:
      die('crack?');
}
// print ($query);
if ( mysql_query($query) )
{
      print '<p class="HiLiteWarn">' . sprintf ($fmtUserUpdated, $u_name, $u_host) . '</p>' ."\n";
      print '<div class="warn">'.$strOnUsersUpdateMsg."</div>\n";
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
   $ui = get_user_info($u_host, $u_name);
   if ( $ui === FALSE )
      die('crack?');
      //tohtml($ui);
      $user2html = stripslashes (htmlspecialchars($ui['USER'], ENT_QUOTES, $chset));
      $host2html = stripslashes (htmlspecialchars($ui['HOST'], ENT_QUOTES, $chset));
      $longname2html = stripslashes (htmlspecialchars($ui['LONGNAME'], ENT_QUOTES, $chset));
      $passwd2html = stripslashes (htmlspecialchars($ui['PASSWD'], ENT_QUOTES, $chset));
      $u_devacl = stripslashes (htmlspecialchars($ui['ALLOW_CAMS'], ENT_QUOTES, $chset));
      $u_status = $ui['STATUS'];
      $limit_fps = $ui['LIMIT_FPS'];
      $limit_kbps = $ui['LIMIT_KBPS'];
      echo '<h2>' . sprintf ($fmtUserTune,$ui['USER'],$ui['HOST']) . '</h2>' ."\n";
      print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
      require '_user_data_tbl.inc.php';
      print '<br>'."\n";
      print '<input type="hidden" name="cmd" value="UPDATE_USER">'."\n";
      print '<input type="hidden" name="old_u_name" value="'.$user2html.'">'."\n";
      print '<input type="hidden" name="old_u_host" value="'.$host2html.'">'."\n";
      print '<input type="hidden" name="old_u_passwd" value="'.$passwd2html.'">'."\n";
      print '<input type="submit" name="submit_btn" value="'.$strSave.'">'."\n";
      print '<input type="reset" name="reset_btn" value="'.$strRevoke.'" onclick="reset_to_list();">'."\n";
      print '</form>'."\n";
}

// phpinfo ();
require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
