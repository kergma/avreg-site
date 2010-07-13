<?php
echo '<h2 align="center">' . sprintf($fmtVidServ, $named, $sip) .
'&nbsp;&nbsp;(<a style="font-size: 85%;" href="'.sprintf($conf['prefix'].'/admin/key.php?sip=%s&amp;named=%s',$sip,$named).'">'.$license.'</a>)</h2>' ."\n";
print "<div align='center'>$strYou: <a href='index.php?logout=1' title='$strLogout'>$login_user@$remote_addr</a></div>\n";

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

