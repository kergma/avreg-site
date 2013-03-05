Для подключения flowplayer flash player необходимо:

1) Включить использование flowplayer flash для проигрывания mp4/flv.
Для этого, в секции настройки avreg-site конфигурационного файла avreg.conf
поместите 2 строки, например:
avreg-site {
...
   aplayerConfig['*']['*']['*']['http']['mp4'] = 'flowplayer'
   aplayerConfig['*']['*']['*']['http']['flv'] = 'flowplayer'
...
}

2) Настоить пути к рабочим файлам flowplayer-а.
Настройки по умолчанию предполагают загрузку файлов непосредственно
из сети интернет с сайта flowplayer.org,
содержатся они (настройки) в /etc/avreg/site-defauls.php
и могут меняться от релиза к релизу.
Например,

/* Flowplayer configuration */
$conf['flowplayer-path'] = 'http://releases.flowplayer.org';
$conf['flowplayer-js'] = 'js/flowplayer-3.2.12.min.js';
$conf['flowplayer-swf'] = 'swf/flowplayer-3.2.16.swf';
$conf['flowplayer-pseaudiostreaming'] = 'swf/flowplayer.pseudostreaming-3.2.12.swf';

Если вы хотите использовать новую версию flowplayer и/или
скопировать и использовать локальные версии файлов flowplayer
(для клиентов, без использования постоянного подключения к сети),
переопределите значения определённых параметров в файле avreg.conf.
Например, если вы скопировали файлы в /var/www/flowplayer, то
добавьте в avreg.conf 4 строки
avreg-site {
...
   flowplayer-path = 'http://AVReg_hostname/flowplayer'
   flowplayer-js   = 'flowplayer-3.2.12.min.js'
   flowplayer-swf  = 'flowplayer-3.2.16.swf'
   flowplayer-pseaudiostreaming = 'flowplayer.pseudostreaming-3.2.12.swf'
...
}

Прим.: AVReg_hostname нужно заменить на dns-имя сервера avreg.

