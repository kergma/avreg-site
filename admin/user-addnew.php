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

if ( isset($cmd) && isset($u_host) && isset($u_name) && isset($groups) )
{
   $limit_kbps = NULL; // FIXME if readonly
   require('user-check.inc.php');
   switch ( $cmd )
   {
      case 'ADD_NEW_USER':
         if ( empty($u_pass) )
            $passwd_f = '\'\',';
         else
            $passwd_f = sprintf('encrypt(\'%s\'), ', $u_pass);
         $query = sprintf('INSERT INTO USERS 
            ( HOST, USER, PASSWD, STATUS, ALLOW_CAMS, SESSIONS_PER_CAM,
            LIMIT_FPS, NONMOTION_FPS, LIMIT_KBPS,
            SESSION_TIME, SESSION_VOLUME,
            LONGNAME, CHANGE_HOST, CHANGE_USER, CHANGE_TIME) 
            VALUES ( %s, %s, %s %u, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW())',
               sql_format_str_val($u_host),
               sql_format_str_val($u_name),
               $passwd_f,
               $groups,
               sql_format_str_val($u_devacl),
               sql_format_int_val($sessions_per_cam),
               sql_format_int_val($limit_fps),
               sql_format_float_val($nonmotion_fps),
               sql_format_int_val($limit_kbps),
               sql_format_int_val($session_time),
               sql_format_int_val($session_volume),
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
      print '<div class="warn">'.$strOnUsersUpdateMsg."</div>\n";
      print '<br><center><a href="'.$conf['prefix'].'/admin/user-list.php">'.$l_user_list.'</a><center>' ."\n";
   } else {
      print '<div class="error">'.sprintf ($fmtUserAddErr2, $u_name, $u_host, mysql_error() ). "</div>\n";
      if ( $conf['debug'] ) tohtml($query);      
      print_go_back();
   }
   require ('../foot.inc.php');
   exit;
} else {
   print '<p class="HiLiteErr">'.$strInvalidFormParams.'</p>' ."\n";
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
   print '<form id="user_info_frm" action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
   print '<table cellspacing=0 border=1 cellpadding=5>'."\n";
   $u_status = &$status;
   $user2html = $host2html = $longname2html = $passwd2html = $u_devacl = NULL;
   $sessions_per_cam = $limit_fps = $nonmotion_fps = $limit_kbps = $session_time  = $session_volume = NULL; 
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
