<?php
/* Кусок ВАШЕГО php-кода, выполняемый по событию получения
 * HTTP уведомления (HTTP notification).
 * Если вы хотите как-то реагировать на сработку дискретных входов
 * ip-камер или ip-видеосерверов, правьте и используйте этот код.
 * Естественно, для этого нужно настроить и сами устройства.
 * В камерах Axis, например, настройка HTTP notification находится на
 * веб-странице Setup->Event Configuration->Event Settings.
 * 
 * Некоторые "интересные" переменные php:
 * array $REQUEST - параметры, переданные в запросе GET/POST
 * array $_SERVER - см. документацию PHP,
 *                  $_SERVER["REMOTE_ADDR"] - ip адрес камеры
 * int   $CAM_NR  - номер камеры, если он известен
*/

/*
 * // посылаем сообщение на jabber или icq
 * if ( true ) {
 *   sendxmpp();
 * }
 *
 * // посылаем сообщение на мыло
 * if ( true ) {
 *   sendmail();
 * }
 *
 *   // посылаем сообщение на sms
 * if ( true ) {
 *   sendSMS();
 * }
 *
 */

/* to AVREG-MON */
if ( true && isset($CAM_NR)) {
   /* Если нужно, "передаём" тревогу в локальный просмотрщик камер avreg-mon
      делается это через http-запрос, подробности см. в описании
      параметров группы "Настройки удалённого управления"
      в секции avreg-mon {} конфигурационного файла /etc/avreg/avreg.conf */

   $res=confparse($conf, 'avreg-mon', '/etc/avreg/avreg.conf', array('remote-control'));

   if ( !$res ) {
      $err_s = 'avreg-mon does not use remote-control';
      die("<div>Received.</div><div>$err_s</div>\r\n</body></html>\r\n");
   }

   $avreg_mon_url = $res['remote-control'].'/camera';

   /* Примечание: если одновременно используется 2 avreg-mon-а,
      запущенные на левом $port и правом ($port + 1) дисплеях,
      то вам придётся самим определять $avreg_mon_url в зависимости
      от того, какая камера (по её номеру) где выводится */

   /* код тревоги по умолчанию 1 или должен передаваться параметром "alarm" */
   if ( isset($_REQUEST) && isset($_REQUEST['alarm']) )
     $alarm_code = (int)$_REQUEST['alarm'];
   else
     $alarm_code = 1;

   $get_query = "nr=$CAM_NR&param=alarm&action=set&value=$alarm_code";

   /* allow_url_fopen MUST set to "1" in php.ini */
   $file = fopen("$avreg_mon_url?$get_query", 'r');

   if (!$file) {
     $err_s = "fopen(\"$avreg_mon_url?$get_query\") failed";
     print_syslog(LOG_ERR, $err_s);
     die("<div>Received.</div><div>$err_s</div>\r\n</body></html>\r\n");
   }
   /* вычитываем ответ */
   $line='';
   while (!feof ($file))
      $line .= fgets ($file, 1024);
   fclose($file);
} /* to avreg-mon */

?>
