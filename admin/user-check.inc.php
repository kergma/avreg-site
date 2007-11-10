<?php
  if ($user_status > intval($groups)) die ('Crack or hack???');
  // проверяем
  $good = 0;
  
  $u_name = trim($u_name);
  if ( !preg_match ( $patternUser, $u_name) ) {
     echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, $strUserName) . '</p>' ."\n";
  } else $good++;
  
  $u_host = trim($u_host);
  if ( empty($u_host) ) {
     echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, $strHost) . '</p>' ."\n";
  } else if (!preg_match($patternAllowedIP, $u_host)) {
     echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, $strHost) . '</p>' ."\n";
  } else $good++;

  $u_longname = trim($u_longname);
  if ( empty($u_longname) ) {
    echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, $FIO) . '</p>' ."\n";
  } else $good++;

  $u_pass = trim($u_pass);
  $u_pass2 = trim($u_pass2);
  if ( !preg_match ( $patternPasswd , $u_pass) ) {
    print '<p class="HiLiteErr">' . sprintf ($fmtPasswdBadChar, $u_name, $u_host) . '</p>' ."\n";
  } else $good++;
  
  if ( strcmp($u_pass,$u_pass2) ) {
    echo '<p class="HiLiteErr">' . $strPassNotPass2. '</p>' ."\n";
  } else $good++;

  if ( !isset($groups) || !settype($groups,'int') ) {
    echo '<p class="HiLiteErr">' . sprintf ($fmtEmptyF, $str_groups) . '</p>' ."\n";
  } else $good++;
  
  if ($good<6) {
    print '<p class="HiLiteErr">' . $strAddUserErr1 . '</p>' ."\n";
    print_go_back();
    require ('../foot.inc.php');
    exit;
  }
?>
