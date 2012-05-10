<?php

/**
 * 
 * @mainpage AvregSite 
 * 
 * Веб-интерфейс
 * 
 * Состоит из следующих частей:
 * - @ref lib 
 * - @ref admin 
 * - @ref lang 
 * - @ref offline 
 * - @ref online 
 * - @ref pda 
 */

$NO_OB_END_FLUSH = true;
require ('./head.inc.php');
while (@ob_end_flush());

print '<table width="600" cellspacing="20" border="0" cellpadding="0" align="center">'."\n";
print '<tbody>'."\n";
print '<tr>'."\n";
print '<td align="center" valign="middle" rowspan="2" nowrap>'."\n";
print '<a href="'.$conf['prefix'].'/pda/"><img src="'.$conf['prefix'].'/img/pda.gif" border="0px" /></a>'."\n";
print '<p><a href="'.$conf['prefix'].'/pda/">PDA-версия</a></p>'."\n";
print '</td>'."\n";
print '<td align="center" valign="top">'."\n";
// $href1 = sprintf('/online/index.php?sip=%s&named=%s', urlencode($sip), urlencode($named));
print '<a href="'.$conf['prefix'].'/online/index.php" title="'.$a_webcam.'"><img src="'.$conf['prefix'].'/img/online.jpg" width="251" height="165" border="0"></a>'."\n";
print '<p><a href="'.$conf['prefix'].'/online/index.php">'.$a_webcam.'</a></p>'."\n";
print '</td>'."\n";

print '<td align="center" valign="top">'."\n";
if ( $admin_user /* config.inc.php */ ) {
   $href3=sprintf($conf['prefix'].'/admin/index.php?sip=%s&amp;named=%s',$sip,$named);
   print '<a href="'.$href3.'" title="'.$a_adminv.'"><img src="'.$conf['prefix'].'/img/admin.jpg" width="251" height="165" border="0"></a>'."\n";
   print '<p align="center"><a href="'.$href3.'">'.$a_adminv.'</a></p>'."\n";
} else {
   print "&nbsp;\n";
}
print '</td>'."\n";
print '</tr>'."\n";

if ( $arch_user /* config.inc.php */) {
   print '<tr>'."\n";
   print '<td align="center" valign="top">'."\n";
   // $href2=sprintf('/offline/index.php?sip=%s&named=%s', urlencode($sip), urlencode($named));
   print '<a href="'.$conf['prefix'].'/offline/index.php" title="'.$a_archive.'"><img src="'.$conf['prefix'].'/img/offline.jpg" width="251" height="165" border="0"></a>'."\n";
   print '<p><a href="'.$conf['prefix'].'/offline/index.php">'.$a_archive.'</a></p>'."\n";
   print '</td>'."\n";
   print '<td align="center" valign="middle">'."\n";
   print '<a href="'.$conf['prefix'].'/offline/playlist.php"><img src="'.$conf['prefix'].'/img/offline_playlist.jpg" width="251" height="165" border="0"></a>'."\n";
   print '<p align="center"><a href="'.$conf['prefix'].'/offline/playlist.php">'.$a_archive_playlist.'</a></p>'."\n";
   print '</td>'."\n";
   print '</tr>'."\n";
}
print '</tbody>'."\n";
print '</table>'."\n";

if ( $conf['debug'] ) {
   tohtml($conf);
   // phpinfo(INFO_VARIABLES);
}
require ($wwwdir.'/foot.inc.php');
?>
