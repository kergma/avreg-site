<?php
$check_redirect = true;
require ('./head.inc.php');
$_SERVER['user_status']=$user_status;
echo '<h2 align="center">' . sprintf($fmtVidServ, $named, $sip) .
   '&nbsp;&nbsp;(<a style="font-size: 85%;" href="'.sprintf($conf['prefix'].'/admin/key.php?sip=%s&amp;named=%s',$sip,$named).'">'.$license.'</a>)</h2>' ."\n";
print "<div align='center'>$strYou: <a href='index.php?logout=1' title='$strLogout'>$login_user@$remote_addr</a></div>\n";
?>
<?php
/*
if (empty($_SERVER['SSL_PROTO']))
  echo '<h2 align="center">' . sprintf($fmtVidServ, $named, $sip) . 
  '.&nbsp;&nbsp;&nbsp;<a href="https://'.$_SERVER['SERVER_NAME'].'">Войти безопасно (OpenSSL) &gt;&gt;</a></h2>' ."\n";
else
   echo '<h2 align="center">' . sprintf($fmtVidServ, $named, $sip) . 
      '&nbsp;&nbsp;'. $_SERVER['SSL_PROTO'] .'/'. $_SERVER['SSL_CHIPHER'].'</h2>' ."\n";
*/

?>
<script type="text/javascript" language="javascript">
<!--
if (self.screen)
{
  width  = screen.width;
  height = screen.height;
} else if (self.java) {
  var jkit = java.awt.Toolkit.getDefaultToolkit();
  var scrsize = jkit.getScreenSize();
  width = scrsize.width;
  height = scrsize.height;
}else{
  width = '1024';
  height = '768';
}

if ( (width < 1024) || (height < 768) )
{
 var res_warn = ReadCookie('avreg_resolution_warn');
 var warn_cnt=0;
 if (res_warn != "")
    warn_cnt = parseInt(res_warn);

 if ( warn_cnt < 3 ) {
   alert("\nВеб-интерфейс AVReg расчитан на разрешение экрана не менее 1024x768,\nтак как размер даже одного записанного или передаваемого видео обычно составляет\n640х480 или 756х568 пикселей. А помимо кадров, на страницах нужно разместить и элементы управления\n\nУ Вас разрешение экрана " + width + " x " + height + " и работать будет неудобно.\n\nЕсли у Вас современный монитор и Вы работаете в " + width + "x" + height +" из-за мелкого шрифта, просто переключитесь в 1280х1024 и увеличьте системный шрифт.\n\nНормальные операционные системы делают эту операцию очень хорошо и дополнительно умеют сглаживать шрифты.\n\n\nЭто предупреждение будет показано ещё " + (2-warn_cnt) + " раз(а).");
    SetCookie('avreg_resolution_warn', ++warn_cnt, 30);
  }
}
// -->
</script>
<?php
print '<table width="600" cellspacing="20" border="0" cellpadding="0" align="center">'."\n";
print '<tbody>'."\n";
print '<tr>'."\n";
?>
<td align="center" valign="middle" rowspan="2" nowrap>
<a href="pda/"><img src="img/pda.gif" border="0px" /></a>
<p><a href="pda/">PDA-версия</a></p>
</td>
<?php
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

print '<p align="center" style="font-weight:bold;">'."\n";
print $DEV_FIRMS."\n";
print '<br /><a href="http://avreg.net/" target="_blank">http://avreg.net</a>'."\n";
print '</p>'."\n";

if ( $conf['debug'] ) {
   tohtml($conf);
   // phpinfo(INFO_VARIABLES);
}
require ($wwwdir.'/foot.inc.php');
?>
