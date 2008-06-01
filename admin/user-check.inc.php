<?php
require_once ('../lib/utils-inet.php');

if ($user_status > (int)$groups) die ('Crack or hack???');
// проверяем
$good = 0;

$u_name = trim($u_name);
if ( !preg_match ($patternUser, $u_name) ) {
   echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, $strUserName) . '</p>' ."\n";
} else $good++;

$u_host = trim($u_host);
$ua = avreg_inet_network($u_host);
if ( FALSE !== $ua ) {
      $need_check_duplicate = true;
   if ( isset($old_u_name) && isset($old_u_host) &&
         0 === strcmp($old_u_name, $u_name) &&
         0 === strcasecmp($old_u_host, $u_host))
      $need_check_duplicate = false;
      if ($need_check_duplicate)
   {

         $ui = avreg_find_user($ua['addr'], $ua['mask'], $u_name);
         if ( $ui !== FALSE ) {
            print '<p class="HiLiteErr">'.
               sprintf($fmtDuplicateUserHost, 
                     stripslashes (htmlspecialchars($u_name)),
                     stripslashes (htmlspecialchars($u_host)),
                     $ui['USER'], $ui['HOST'],
                     stripslashes (htmlspecialchars($ui['LONGNAME'])))
               . '</p>' ."\n";
         } else 
            $good++;
   } else 
         $good++;
} else {
   echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, $strHost) . '</p>' ."\n";
}


$u_longname = trim($u_longname);
if ( empty($u_longname) ) {
   echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, $FIO) . '</p>' ."\n";
} else $good++;

$u_pass = trim($u_pass);
$u_pass2 = trim($u_pass2);
if ( $u_pass[0] != '*' && !preg_match ( $patternPasswd , $u_pass) ) {
   print '<p class="HiLiteErr">' . sprintf ($fmtPasswdBadChar, $u_name, $u_host) . '</p>' ."\n";
} else $good++;

if ( strcmp($u_pass,$u_pass2) ) {
   echo '<p class="HiLiteErr">' . $strPassNotPass2. '</p>' ."\n";
} else $good++;

if ( !isset($groups) || !settype($groups,'int') ) {
   echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, $str_groups) . '</p>' ."\n";
} else $good++;

if ( FALSE === parse_dev_acl($u_devacl) ) {
   echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, 'DeviceACL') . '</p>' ."\n";
} else $good++;

if ( !empty($limit_fps) && !settype($limit_fps,'int') || $limit_fps < 1 || $limit_fps > 30) {
   echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, 'limit_fps') . '</p>' ."\n";
} else $good++;

if ( !empty($limit_kbps) && !settype($limit_kbps,'int') || $limit_kbps < 0 || $limit_kbps > 99999 ) {
   echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, 'limit_kbps') . '</p>' ."\n";
} else $good++;

if ($good<9) {
   print '<p class="HiLiteErr">' . $strAddUserErr1 . '</p>' ."\n";
   print_go_back();
   require ('../foot.inc.php');
   exit;
}
?>
