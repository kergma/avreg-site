<?php
$lang_file='_admin_users.php';
require ('../head.inc.php');
DENY($admin_status);
require ('../lib/my_conn.inc.php');
require_once ('../lib/utils-inet.php');
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

if ( isset($cmd) ) {
if ( isset($u_host) && isset($u_name) && isset($groups))
{
require('user-check.inc.php');
switch ( $cmd )
{
   case 'ADD_NEW_USER':
      if ( 0 === strcmp($u_pass, $old_u_passwd) )
         $passwd_changed = '';
      else
         $passwd_changed = sprintf('encrypt(\'%s\'), ', $u_pass);
      $query = sprintf('INSERT INTO USERS 
      ( HOST, USER, PASSWD, STATUS, ALLOW_CAMS,
      LIMIT_FPS, LIMIT_KBPS, LONGNAME,
      CHANGE_HOST, CHANGE_USER, CHANGE_TIME) 
      VALUES ( %s, %s, %s %u, %s, %s, %s, %s, %s, %s, NOW())',
      sql_format_str_val($u_host), sql_format_str_val($u_name),
      $passwd_changed,
      $groups,
      sql_format_str_val($u_devacl),
      sql_format_int_val($limit_fps),
      sql_format_int_val($limit_kbps),
      sql_format_str_val($u_longname),
      sql_format_str_val($remote_addr),
      sql_format_str_val($login_user));
      break;
   default:
      die('crack');
}
// print ($query);
if ( mysql_query($query) )
{
      print '<p class="HiLiteWarn">' . sprintf ($fmtUserAdded, $u_name, $u_host) . '</p>' ."\n";
      print '<br><center><a href="'.$conf['prefix'].'/admin/user-list.php">'.$l_user_list.'</a><center>' ."\n";
} else {
      print '<p class="HiLiteErr">'.sprintf ($fmtUserAddErr2, $u_name, $u_host, mysql_error() ).
            '</p>' ."\n";
      print_go_back();
}
require ('../foot.inc.php');
exit;
} else {
      print '<p class="HiLiteErr">'.$strInvalidFormParams.'</p>' ."\n";
}
}

echo '<h2>' . $r_user_add . '</h2>' ."\n";

if (isset($status)) {
if (!settype($status,'int'))
   die();
} else {
$status=-1;
}

if ( !isset($u_name) || empty($u_name) )
{
      print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
      print '<table cellspacing=0 border=1 cellpadding=5>'."\n";
      $u_status = &$status;
      require '_user_data_tbl.inc.php';
      print '<br>'."\n";
      print '<input type="hidden" name="cmd" value="ADD_NEW_USER">'."\n";
      print '<input type="submit" name="submit_btn" value="'.$strAddUser.'">'."\n";
      print '<input type="reset" name="reset_btn" value="'.$strRevoke.'" onclick="reset_to_list();">'."\n";
      print '</form>'."\n";
}

// phpinfo ();
require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
