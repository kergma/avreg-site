<?php
require ('./head.inc.php');
$_SERVER['user_status']=$user_status;

echo '<h2 align="center">' . sprintf($fmtVidServ, $named, $sip) .'</h2>' ."\n";

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

if ( (width < 1280) || (height < 1024) )
{
 var res_warn = ReadCookie('avreg_resolution_warn');
 var warn_cnt=0;
 if (res_warn != "")
    warn_cnt = parseInt(res_warn);

 if ( warn_cnt < 3 ) {
   alert("\nВеб-интерфейс AVReg расчитан на разрешение экрана не менее 1280х1024,\nтак как размер даже одного записанного или передаваемого видео обычно составляет\n640х480 или 756х568 пикселей. А помимо кадров, на страницах нужно разместить и элементы управления\n\nУ Вас разрешение экрана " + width + " x " + height + " и работать будет не удобно.\n\nЕсли у Вас современный монитор и Вы работаете в " + width + "x" + height +" из-за мелкого шрифта, просто переключитесь в 1280х1024 и увеличьте системный шрифт.\n\nНормальные операционные системы делают эту операцию очень хорошо и дополнительно умеют сглаживать шрифты.\n\n\nЭто предупреждение будет показано ещё " + (2-warn_cnt) + " раз(а).");
    SetCookie('avreg_resolution_warn', ++warn_cnt, 30);
  }
}
// -->
</script>
<?php
print '<table width="600" cellspacing="20" border="0" cellpadding="0" align="center">'."\n";
print '<tbody>'."\n";
print '<tr>'."\n";
print '<td align="center" valign="top">'."\n";
// $href1 = sprintf('/online/index.php?sip=%s&named=%s', urlencode($sip), urlencode($named));
print '<a href="'.$conf['prefix'].'/online/index.php" title="'.$a_webcam.'"><img src="'.$conf['prefix'].'/img/online.jpg" width="206" height="165" border="0"></a>'."\n";
print '<p><a href="'.$conf['prefix'].'/online/index.php">'.$a_webcam.'</a></p>'."\n";
print '</td>'."\n";
print '<td align="center" valign="top">'."\n";
// $href2=sprintf('/offline/index.php?sip=%s&named=%s', urlencode($sip), urlencode($named));
print '<a href="'.$conf['prefix'].'/offline/index.php" title="'.$a_archive.'"><img src="'.$conf['prefix'].'/img/offline.jpg" width="251" height="165" border="0"></a>'."\n";
print '<p><a href="'.$conf['prefix'].'/offline/index.php">'.$a_archive.'</a></p>'."\n";
print '</td>'."\n";
print '</tr>'."\n";

print '<tr>'."\n";
print '<td align="center" valign="top">'."\n";
$href3=sprintf($conf['prefix'].'/admin/index.php?sip=%s&amp;named=%s',$sip,$named);
print '<a href="'.$href3.'" title="'.$a_adminv.'"><img src="'.$conf['prefix'].'/img/admin.jpg" width="206" height="158" border="0"></a>'."\n";
print '<p align="center"><a href="'.$href3.'">'.$a_adminv.'</a></p>'."\n";
print '</td>'."\n";
print '<td align="center" valign="middle">'."\n";
print '<a href="'.$conf['prefix'].'/offline/playlist.php"><img src="'.$conf['prefix'].'/img/offline_playlist.jpg" width="251" height="159" border="0"></a>'."\n";
print '<p align="center"><a href="'.$conf['prefix'].'/offline/playlist.php">'.$a_archive_playlist.'</a></p>'."\n";
print '</td>'."\n";
print '</tr>'."\n";

print '</tbody>'."\n";
print '</table>'."\n";

print '<p align="center" style="font-weight:bold;">'."\n";
print '<br />'.$DEV_FIRMS."\n";
print '<br /><a href="http://avreg.net/" target="_blank">http://avreg.net</a>'."\n";
print '</p>'."\n";

if ( $conf['debug'] )
   tohtml($conf);

require ($wwwdir.'/foot.inc.php');
?>
