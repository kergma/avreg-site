<?php
$pageTitle = 'tune_logo';
$MENU=1;
$BaseTarget='content';
require('../head.inc.php');
print '<div align="center"><a href="'.$conf['prefix'].'/" target="_parent">'.$MainPage.'</a></div>'."\n";
print '<br /><div>'. $strYou .': '.$login_user . '@' . $_SERVER['REMOTE_ADDR']. '</div>'."\n";
// print '<div align="center">'.$tune_logo.'</div>'."\n";
print '<hr noshade>'."\n";

//$the_a = sprintf (''.$conf['prefix'].'/admin/index.php?sip=%s&named=%s', urlencode($sip), urlencode ($named));
print '<div class="menu0"><a href="'.$conf['prefix'].'/admin/index.php" target="_parent">&lt;&lt;&nbsp;&nbsp;'.$strBackIn.' ' .$left_logo.'</a></div>'."\n";

if ($LDVR_VER !== false) {
   if ( $admin_user ) {
      if ( isset($tab) && ($tab == 'sys'))
         print '<div class="menu0active">-&nbsp;<a  href="'.$conf['prefix'].'/admin/menu-tune.php?tab=sys&#038;load='.$conf['prefix'].'/admin/systems-conf.php" target="menu">'.$left_system.'</a></div>'."\n";
      else
         print '<div class="menu0">+&nbsp;<a href="'.$conf['prefix'].'/admin/menu-tune.php?tab=sys&#038;load='.$conf['prefix'].'/admin/systems-conf.php" target="menu">'.$left_system.'</a></div>'."\n";
   }
}

if ( isset($tab) && ($tab == 'users')){
   // $the_a = sprintf (''.$conf['prefix'].'/admin/menu-tune.php?sip=%s&named=%s', urlencode($sip), urlencode ($named));
   print '<div class="menu0active">-&nbsp;<a href="'.$conf['prefix'].'/admin/menu-tune.php" target="menu">'.$left_users.'</a>'."\n";
   print '<div class="menu1">&nbsp;&nbsp;-&nbsp;<a   href="'.$conf['prefix'].'/admin/user-list.php">'.$l_user_list.'</a></div>'."\n";
   if ( $admin_user )
      print '<div class="menu1">&nbsp;&nbsp;-&nbsp;<a   href="'.$conf['prefix'].'/admin/user-addnew.php">'.$l_user_addnew.'</a></div>'."\n";
   print '<div class="menu1">&nbsp;&nbsp;-&nbsp;<a class="menu1"  href="'.$conf['prefix'].'/admin/user-passwd.php">'.$l_user_passwd.'</a></div>'."\n";

   print '</div>'."\n";
} else {
/*
   $the_a = sprintf ('/admin/menu-tune.php?sip=%s&named=%s#038;tab=users#038;load='.$conf['prefix'].'/admin/user-list.php',
     urlencode($sip), urlencode ($named));
 */
   print '<div class="menu0">+&nbsp;<a href="'.$conf['prefix'].'/admin/menu-tune.php?tab=users&#038;load='.$conf['prefix'].'/admin/user-list.php" target="menu">'.$left_users.'</a></div>'."\n";
}

if ( isset($tab) && ($tab == 'cameras')){
   print '<div class="menu0active">-&nbsp;<a href="'.$conf['prefix'].'/admin/menu-tune.php" target="menu">'.$left_tune.'</a><sup style="font-size:85%;">&nbsp;'.$videoserv.'</sup>'."\n";
   if ( $admin_user )
      print '<div class="menu1">&nbsp;&nbsp;-&nbsp;<a href="'.$conf['prefix'].'/admin/cam-tune.php?cam_nr=0">'.$l_cam_defaults.'</a></div>'."\n";
   print '<div class="menu1">&nbsp;&nbsp;-&nbsp;<a href="'.$conf['prefix'].'/admin/cam-list.php">'.$l_cam_list.'</a></div>'."\n";
   if ( $install_user )
      print '<div class="menu1">&nbsp;&nbsp;-&nbsp;<a href="'.$conf['prefix'].'/admin/cam-addnew.php">'.$l_cam_addnew.'</a></div>'."\n";
   print '</div>'."\n";
} else {
   print '<div class="menu0">+&nbsp;<a href="'.$conf['prefix'].'/admin/menu-tune.php?tab=cameras&#038;load='.$conf['prefix'].'/admin/cam-list.php" target="menu">'.$left_tune.'</a><sup style="font-size:85%;">&nbsp;'.$videoserv.'</sup></div>'."\n";
}

if ( isset($tab) && ($tab == 'monitors')){
   print '<div class="menu0active">-&nbsp;<a href="'.$conf['prefix'].'/admin/menu-tune.php?tab=monitors&#038;load='.$conf['prefix'].'/admin/mon-list.php" target="menu"  title="'.$left_monitors_title.'">'.$left_monitors.'</a><sup style="font-size:85%;">&nbsp;'.$local_player_name.'</sup></div>'."\n";
} else {
   print '<div class="menu0">+&nbsp;<a href="'.$conf['prefix'].'/admin/menu-tune.php?tab=monitors&#038;load='.$conf['prefix'].'/admin/mon-list.php" target="menu"  title="'.$left_monitors_title.'">'.$left_monitors.'</a><sup style="font-size:85%;">&nbsp;'.$local_player_name.'</sup></div>'."\n";
}

print '<br><br><br><hr noshade>'."\n";

require('menu-bottom.inc.php');

require('../foot.inc.php');
?>
