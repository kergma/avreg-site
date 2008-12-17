<?php
$include_javascripts = array('offline/view.js');
$link_javascripts=array('lib/js/jquery-1.2.6.min.js');
$ie6_quirks_mode = true;
$body_style='margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; ';
require ('../head.inc.php');
require_once ('../lib/my_conn.inc.php');
DENY($arch_status);
?>

<script type="text/javascript" language="JavaScript1.2">
<!--
if (ie||ns6)
  tipobj=document.all? 
     document.all['tooltip'] :
     document.getElementById? document.getElementById('tooltip') : '';

document.onmousemove=positiontip;

<?php
/* вычисляем протокол доступа к медиа-файлам 
   на основании адреса клиента и правил в конфиге */

if ( 0 === strpos($_SERVER['SERVER_ADDR'], $_SERVER['REMOTE_ADDR']) ) {
   /* считаем (?) что такое может быть только для локального клиента 
    * а вот и не так, также будет если проксик на том же сервере 
    * и клиенты через него тупо(в той же подсети) работают */
   print 'var MediaUrlPref = \'file:\/\/\' + StorageDir + \'\/\';'."\n";
} else {
   require_once($wwwdir . 'lib/utils-inet.php');
   $remote_ip = ip2long($_SERVER['REMOTE_ADDR']);
   /* удалённый клиент */
   $ua = strtolower($_SERVER['HTTP_USER_AGENT']);

   if ( $MSIE /* only win */ || false !== strpos($ua, 'windows') )
      $platform = 'win'; 
   else if ( false !== strpos($ua, 'linux') ||
      false !== strpos($ua, 'solaris') ||
      false !== strpos($ua, 'bsd') ||
      false !== strpos($ua, 'mac os x') )
      $platform = 'nix';
   else 
      $platform = 'other';

   $_done = false;
   $_ipacl_list = array_keys(&$conf["murl-pre-$platform"]);
   foreach ( $_ipacl_list as &$_ipacl_str ) {
      $_ipacl = avreg_inet_network($_ipacl_str);
      if ( $_ipacl === false ) {
         /* неправильно задан IP ACL */
         printf("alert(\"invalid IP ACL [%s] for murl-pre-$platform param (see avreg.conf)\");\n",
           addcslashes($_ipacl_str, '\'"/\\'));
         break;
      }
      if ( true === avreg_ipv4_cmp( $remote_ip, -1, $_ipacl['addr'],  $_ipacl['mask']) ) {
         printf('var MediaUrlPref = \'%s/\';'."\n",
            addcslashes($conf["murl-pre-$platform"][$_ipacl_str], '\'"/\\'));
         $_done = true;
         break;
      }
   }

   if ( !$_done /* не нашли совпадения по ACL */ )
      print 'var MediaUrlPref = location.protocol + \'\/\/\' + location.host + WwwPrefix + MediaAlias + \'\/\';'."\n";
}
?>
// -->
</script>

<div id="content" style="position:absolute;width:100%;height:100%;"
 onmouseover="ddrivetip();" onmouseout="hideddrivetip();">

<?php
if (!isset ($src) || empty($src))
{
	print '<h4 align="center"><br><br><br><br><br><br>'.$strViewFrame.'</h4>'."\n";
} 
print  '</div>'."\n";

require_once ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
