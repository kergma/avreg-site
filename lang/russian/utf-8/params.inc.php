<?php

$F_IN_DEF   = 0x0001;
$F_IN_CAM   = 0x0002;
$F_RELOADED = 0x0004;

$F_BASEPAR  = 0x0100;
 
$vid_standarts = array ('PAL (цв.в/к)', 'NTSC (цв.в/к)', 'SECAM (не для в/к)', 'PAL NC (ч/б в/к)' );
$strCamType = array('netcam', 'v4l');
$strNetProto = array('http');
$strFileFmt = array('JPEG', 'MJPEG', 'MPEG4');
$str_audio_force_fmt = array(
'PCM_MULAW',
'PCM_ALAW',
'G726_32K',
'G726_24K',
'PCM_S8',
'PCM_U8',
'PCM_S16LE',
);

$str_audio_save_fmt = array(
'MP2',
'MP3',
'M4A',
'MOV',
);

$geometry = array(
'176x144',
'240x180',
'320x240',
'352x240',
'352x288',
'384x288',
'480x360',
'560x420',
'640x480',
'704x420',
'704x576',
'720x540',
'720x576',
'768x576',
'1280x720',
'1280x960',
'1280x1024',
'1600x1200',
'144x176',
'180x240',
'240x320',
'240x352',
'288x352',
'288x384',
'360x480',
'420x560',
'480x640',
'420x704',
'576x704',
'540x720',
'576x720',
'576x768',
'720x1280',
'960x1280',
'1024x1280',
'1200x1600',
);

$syslog_levels = array(
'EMERG',  /* system is unusable */
'ALERT',  /* action must be taken immediately */
'CRIT',   /* critical conditions */
'ERR',    /* error conditions */
'WARNING', /* warning conditions */
/*
'NOTICE'  normal but significant condition,
'INFO'  informational,
'DEBUG'  debug-level messages,
*/
);

$deinterlacers = array('LINE_DOUBLING','BILINEAR_METH','LINEAR_BLEND');

$Snap_Reconnect_array = array(2000, 1500, 1200, 1000, 900,800,700,600, 500, 400, 300, 200, 100);

$flip_type = array('зеркально', 'вращение 180');

$ScriptMayBeInstalled='Скрипт должен быть <nobr><a href="'.$conf['prefix'].'/admin/systems-conf.php#user_scripts">предварительно установлен &gt;&gt;</a></nobr>';

// $PAR_CATEGORY, $COMMENT, $VIEW_ON_DEF, $VIEW_ON_CAM, $MASTER_STATUS, $HELP_PAGE
$PAR_GROUPS = array(
array(
    'id'=>'1',
     'name'=>'Главное',
    'desc'=>'Вкл./Выкл. захвата и отладки',
    'flags'=>$F_BASEPAR | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=>NULL
    ),

array(
    'id'=>'3',
	 'name'=>'Камера',
    'desc'=>'Выбор и настройка типа камеры',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> $conf['docs-prefix'].'apps-quick-conf.html'
    ),
 
array(
    'id'=>'3.1',
	 'name'=>'ip-камеры',
    'desc'=>'Параметры доступа к сетевым IP-камерам и IP-видеосерверам',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> $conf['docs-prefix'].'apps-ipcam-capture.html'
    ),
    
array(
    'id'=>'3.1.1',
	 'name'=>'протокол &#171;http://&#187;',
    'desc'=>'Протокол доступа &#171;http://&#187; (форматы видео: jpeg/mjpeg, аудио: pcm,adpcm,G.72x,aac)',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),


array(
    'id'=>'3.2',
	 'name'=>'платы захвата',
    'desc'=>'Параметры захвата с аналоговых CCTV видеокамер',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),    

 array(
    'id'=>'5',
	 'name'=>'Обработка',
    'desc'=>'Различные алгоритмы обработки аудио/видео данных',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,    
    'mstatus'=> 2,
    'help_page'=> $conf['docs-prefix'].'apps-quick-conf.html'
    ),

 array(
    'id'=>'5.1',
    'name'=>'видео',
    'desc'=>'Алгоритмы обработки видео',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),

 array(
    'id'=>'5.1.1',
    'name'=>'наложение текста на кадр',
    'desc'=>'Текст, &#171;врезаемый&#187; в видеокадры',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),

 array(
    'id'=>'5.1.2',
    'name'=>'детектор',
    'desc'=>'Настройка детектора движения',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> $conf['docs-prefix'].'motion-detector.html'
    ),

 array(
    'id'=>'5.2',
    'name'=>'аудио',
    'desc'=>'Различные алгоритмы обработки аудиопотока',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),
 
 array(
    'id'=>'5.2.1',
    'name'=>'детектор',
    'desc'=>'Детектор звука VAD (voice audio detection)',
    'flags'=>$F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL,
    ),


array(
    'id'=>'11',
    'name'=>'Запись',
    'desc'=>'Запись на жёсткие диски (HDD)',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> $conf['docs-prefix'].'filefmt.html'
    ),

array(
    'id'=>'11.1',
    'name'=>'видео',
    'desc'=>'Только видео (без аудио)',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),
 
array(
    'id'=>'11.1.1',
    'name'=>'mjpeg',
    'desc'=>'Настройки кодека JPEG и записи видео в файлы JPEG ( .jpg ) и MJPEG ( .avi )',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),
array(
    'id'=>'11.1.2',
    'name'=>'mpeg4',
    'desc'=>'Настройки кодека MPEG4 и записи в видеофайлы MPEG4 ( .avi )',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),


array(
    'id'=>'11.2',
    'name'=>'аудио',
    'desc'=>'Только аудио (без видео)',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),

array(
    'id'=>'11.3',
    'name'=>'видео + аудио',
    'desc'=>'Совместно: видео + аудио',
    'flags'=>/* $F_BASEPAR | */ $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),
 
array(
    'id'=>'15',
    'name'=>'Наблюдение',
    'desc'=>'Наблюдение в реальном времени (ONLINE)',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),

array(
    'id'=>'15.1',
    'name'=>'локальное',
    'desc'=>'Локальный просмотр на сервере с помощью программы monitor (avreg-mon)',
    'flags'=>$F_BASEPAR | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> $conf['docs-prefix'].'work-monitor.html'
    ),

array(
    'id'=>'15.2',
    'name'=>'по сети',
    'desc'=>'Удаленный просмотр по сети (в интернет-браузере или &quot;вышестоящим&quot; видеосервером LinuxDVR или AVReg)',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ), 
        
array(
    'id'=>'20',
    'name'=>'События',
    'desc'=>'Реакции (beep, e-mail, sound, exec) на различные события',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),
array(
    'id'=>'20.1',
    'name'=>'OnVideoMotion - доп. параметры',
    'desc'=>'Реакции на события &quot;Начало/окончание серии движения&quot; (при Вкл. детекторе движения)',
    'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 2,
    'help_page'=> NULL
    ),
);

$PAR_GROUPS_NR=count($PAR_GROUPS);

// $VAL_TYPE, $DEF_VAL,$COMMENT, $RELOADED, $VIEW_ON_DEF, $VIEW_ON_CAM, $PAR_CATEGORY, $SUBCAT_SELECTOR, $MASTER_STATUS
$PARAMS = array(

array(
  'name'    => 'work',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Вкл./Выкл. <b>видеозахват</b>а с видеокамеры (читай: <b>работать с этой камерой или нет</b>).<br><br>По умолчанию: <b>Выкл</b>.',
  'flags'=>$F_BASEPAR | $F_IN_CAM,
  'cats'    => '1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'debug',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Вкл./Выкл. <b>режим отладки</b>.<br><br>Включение режима отладки <b>существенно замедляет работу системы</b>, так как при этом в системный журнал пишется много отладочных сообщений необходимых <b>для разбора нештатных ситуаций</b>.<br><br>По умолчанию: <b>Выкл</b>.',
  'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_CAM,
  'cats'    => '1',
  'subcats' => NULL,
  'mstatus' => 2,
),


array(
  'name'    => 'cam_type',
  'type'    => 'CHECK',
  'def_val' => 'netcam',
  'desc'    => '<b>Тип  видеокамеры</b>:<ul><li><b>netcam</b> - сетевые IP-камеры или видеосервера, работающие по протоколу MJPEG или JPEG;</li><li><b>v4l</b> - (video for linux) аналоговые видеокамеры, подключенные через PCI-платы видеозахвата или ТВ-тюнеры.</li></ul>По умолчанию: &quot;<b>netcam</b>&quot;.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3',
  'subcats' => '3.1;3.2',
  'mstatus' => 2,
),

array(
  'name'    => 'geometry',
  'type'    => 'CHECK',
  'def_val' => '384x288',
  'desc'    => 'Размер кадра  в пикселях (<b>ширина х высота</b>).
<ul><li><b>Для камер, подключенных через платы видеозахвата,</b> установите пропорционально разрешающей способности видеокамеры с учётом требуемого качества и ресурсов сервера. Для ВСЕХ каналов ОДНОГО устройства видеозахвата (более точно чипа-декодера) должно быть ОДНО значение:<br><b>384x288, 480x360, 560x420, 640x480, 720x540, 768x576</b>.</li><li><b>Для сетевых</b> камер выберите из списка размеры изображения <b>которое реально выдает ip-камера</b> или видеосервер.</li></ul>По умолчанию: <b>384x288</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3',
  'subcats' => '3.1;3.2',
  'mstatus' => 2,
),

array(
  'name'    => 'color',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => '<b>Цветная видеокамера</b> или нет. Важный параметр - <b>ставьте реальные значения</b>.<br><br>Для некоторых сетевых камер и видеошлюзов даже если кажется что они выдают монохромное изображение нужно ставить color=Вкл.<br><br>По умолчанию: <b>Выкл (ч/б в/к)</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3',
  'subcats' => '3.1;3.2',
  'mstatus' => 2,
),

array(
  'name'    => 'rotate',
  'type'    => 'CHECK',
  'def_val' => 0,
  'desc'    => '<b>Разворот кадра</b>.<br><br>Влияет на загрузку CPU, поэтому включайте только когда действительно необходимо. Большинство &quot;правильных&quot; сетевых камер могут делать поворот самостоятельно.<br><br>По умолчанию: <b>без поворота</b>.',
  'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3',
  'subcats' => '3.1;3.2',
  'mstatus' => 2,
),

/* настройки сетевых камер */

array(
  'name'    => 'InetCam_Proto',
  'type'    => 'CHECK',
  'def_val' => NULL,
  'desc'    => '<b>Протокол доступа</b> к сетевой видеокамере или видеосерверу: <b>http, rtsp, rtsp over http</b>.<br><br>По умолчанию: &quot;<b>http</b>&quot;',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'InetCam_IP',
  'type'    => 'STRING',
  'max_len' => 15,
  'def_val' => NULL,
  'desc'    => '<b>IP-адрес</b> сетевой видеокамеры или видеосерверов (например AXIS, Planet, D-Link,  Aviosys, Panasonic, Samsung, Pixord, Vivotek, Moxa).<br><br>По умолчанию: <b>не установлено</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'InetCam_USER',
  'type'    => 'STRING',
  'def_val' => NULL,
  'desc'    => '<b>Имя пользователя</b> для доступа к сетевой видеокамере (если необходимо). <br>По умолчанию: <b>не установлено</b>.',
  'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'InetCam_PASSWD',
  'type'    => 'PASSWORD',
  'def_val' => NULL,
  'desc'    => '<b>Пароль</b> пользователя для доступа к сетевой видеокамере (если необходимо).<br>По умолчанию: <b>не установлено</b>.',
  'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'InetCam_http_port',
  'type'    => 'INT',
  'def_val' => 80,
  'desc'    => '<b>Номер порта TCP/IP</b> на котором сетевая камера или видеосервер слушают запросы HTTP.<br />По умолчанию: &quot;<b>80</b>&quot;.',
  'flags'=> $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'nc_conn_tries_period',
  'type'    => 'INT',
  'def_val' => 5,
  'desc'    => '<b>Интервал (в сек.) между попытками подключения</b>. Прим: первый &#034;переконнект&#034; после разрыва потока - в половину меньше.<br />Диапазон: [2..60], по умолчанию: &quot;<b>5 сек.</b>&quot;.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'nc_wait_conn_timeout',
  'type'    => 'INT',
  'def_val' => 7,
  'desc'    => '<b>Таймаут (в сек.) ожидания установления соединения</b>.<br />Диапазон: [3..60], по умолчанию: &quot;<b>7 сек.</b>&quot;.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'nc_read_timeout',
  'type'    => 'INT',
  'def_val' => 5,
  'desc'    => '<b>Таймаут (в сек.) ожидания ожидания данных из  соединения</b>.<br />Диапазон: [2..30], по умолчанию: &quot;<b>5 сек.</b>&quot;.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'nc_max_http_stream_errors',
  'type'    => 'INT',
  'def_val' => 5,
  'desc'    => '<b>Количество логических ошибок в протоколе приводящее к принудительному разрыву соединения</b>. В некоторых случаях, например: на оч. медленных каналах или проблемных камерах, увеличения значения этого параметра позволяет всё же обеспечить непрерывный видеозахват.<br />Диапазон: [2..10], по умолчанию: &quot;<b>5</b>&quot;.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),


array(
  'name'    => 'V.http_get',
  'type'    => 'STRING_200',
  'def_val' => NULL,
  'desc'    => '<b>Строка запроса GET</b> протокола HTTP на получение потокового видео MJPEG (live) или одиночного кадра JPEG (snapshot).<br><br>Например для Axis:<br />'.
'mjpg: &quot;<b>/axis-cgi/mjpg/video.cgi?resolution=640x480&amp;color=1&amp;fps=5</b>&quot;'.
'<br />'.
'jpeg: &quot;<b>/axis-cgi/jpg/image.cgi?resolution=320x240&amp;camera=1&amp;compression=25</b>&quot;'.
'<br /><br />Не знаете запрос для Вашей камеры - читайте <a href="'.$conf['docs-prefix'].'apps-ipcam-capture.html" target="_blank">здесь &gt;&gt;</a>'.
'<br /><br />По умолчанию: <b>&quot;не установлено&quot; - не захватывать видео</b>',
  'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'jpeg_reconnect',
  'type'    => 'CHECK',
  'def_val' => 200,
  'desc'    => '<b>Если</b> на запрос video_http_get <b>камера выдает одиночный JPEG</b> и разрывает соединение (режим <b>snapshot</b> или still image), то значение параметра - <b>таймаут в милисекундах до следующего запроса</b> к камере. Такой режим менее предпочтителен, по сравнению с потоковым Motion JPEG.<br /><br />По умолчанию: &quot;<b>200 ms</b>&quot;.',
  'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'Aviosys9100_chan',
  'type'    => 'INT',
  'def_val' => NULL,
  'desc'    => '<b>Только для шлюзов Aviosys 9100 (B/RK/A) в режиме roundrobin</b>.<br><br><b>Номер камеры/канала [0,1,2,3]</b> на шлюзе при захвате в режиме roundrobin.<br><br>По умолчанию: <b>не установлено</b> - не Aviosys 9100 в roundrobin.',
  'flags'=> $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'A.http_get',
  'type'    => 'STRING_200',
  'def_val' => '/',
  'desc'    => '<b>Строка запроса GET</b> протокола HTTP на получение аудио-потока в форматах pcm G.5.2.1 mu-law 64kbit/s, adpcm G.726 32kbit/s или G.723 24kbit/s.<br><br>
Например для Axis: &quot;<b>/axis-cgi/audio/receive.cgi</b>&quot;
<br /><br />Не знаете запрос для Вашей камеры - читайте <a href="'.$conf['docs-prefix'].'apps-ipcam-capture.html" target="_blank">здесь &gt;&gt;</a>'.
'<br /><br />По умолчанию: <b>&quot;не установлено&quot; - не захватывать аудио</b>',
  'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'A.force_fmt',
  'type'    => 'CHECK',
  'def_val' => NULL,
  'desc'    => '<b>Принудительно использовать этот аудио формат</b> для камер, которые не передают информацию о формате или передают неправильно.<br />'.
'<ul>'.
'<li>PCM_MULAW - pcm mu-law 8bit 64kbit/s</li>'.
'<li>PCM_ALAW - pcm a-law 8bit 64kbit/s</li>'.
'<li>PCM_S8 - pcm signed linear (2`s complement) 8bit 64kbit/s</li>'.
'<li>PCM_U8 - pcm unsigned linear 8bit 64kbit/s</li>'.
'<li>G726_32K - adpcm g726 4bit 32kbit/s</li>'.
'<li>G726_24K - adpcm g726 3bit 24kbit/s</li>'.
'</ul>По умолчанию: &quot;<b>не установлено</b>&quot; - формат ожидается в заголовке',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'http_user_agent',
  'type'    => 'STRING_200',
  'def_val' => NULL,
  'desc'    => 'Поле <b>User-Agent</b> запроса HTTP. По умолчанию: &quot;<b>'.$conf['daemon-name'].'/$ver</b>&quot;.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'http_referer',
  'type'    => 'STRING_200',
  'def_val' => NULL,
  'desc'    => 'Поле <b>Referer</b> запроса HTTP. По умолчанию: <b>не передается</b>.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

/*
array(
  'name'    => 'http_boundary',
  'type'    => 'STRING',
  'def_val' => NULL,
  'desc'    => 'Строка <b>boundary</b> для сетевых видеокамер, имеющим отклонения при передаче потока multipart/mixed-replace от стандарта протокола HTTP.<br>По умолчанию: <b>не установлено</b>.',
  'reloaded'=> 1,
  'in_def'  => 1,
  'in_cam'  => 1,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),
*/


array(
  'name'    => 'v4l_dev',
  'type'    => 'CHECK',
  'def_val' => NULL,
  'desc'    => 'Спец. <b>файл устройства видеозахвата</b>.<br><br>Число в окончании - <b>порядковый номер</b> (<b>0</b>,1,..7, всего макс. - 8) <b>видеодекодеров BT878/SAA7134/CX2388x</b> на всех установленных PCI-платах видеоввода.<br><br>Например, 16-ти канальная плата с 4-мя чипами будет доступна через 4 файла /dev/video[0..3].<br><br>По умолчанию: <b>не установлено</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'norm',
  'type'    => 'CHECK',
  'def_val' => 0,
  'desc'    => '<b>Видеостандарт</b>: <ul><li><b>PAL</b> - для большинства <b>цветных</b> камер;</li><li>NTSC - для оригинальных американских или японских;</li><li>SECAM - только для телевизионного сигнала;</li><li><b>PAL NC</b> (no colour) - Для <b>ч/б</b> видеокамер.</li></ul>Для ВСЕХ каналов ОДНОГО декодера BT878/SAA7134/CX2388x должно быть ОДНО значение.<br><br>По умолчанию: <b>PAL (цв.)</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'input',
  'type'    => 'CHECK',
  'def_val' => 0,
  'desc'    => '<b>Номер канала</b> [ 0,1,2,3 ] на 4-х канальном чипе BT878/SAA7134/CX2388x.<br><br><b>v4l_dev + input - привязка к конкретному входу (разъему), к которому подкл. камера.</b>
<br><br>По умолчанию: <b>0</b>',
  'flags'=>$F_BASEPAR | $F_IN_CAM,
  'cats'    => '3.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'brightness',
  'type'    => 'INT',
  'def_val' => 5,
  'desc'    => '<b>Яркость</b>. Мин.: 1, макс.: 9. По умолчанию: <b>5</b> (среднее).',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'contrast',
  'type'    => 'INT',
  'def_val' => 5,
  'desc'    => '<b>Контраст</b>. Мин.: 1, макс.: 9.<br />Для плат на Connexant CX2388x оптимально значение 2 или 3 (по нашему мнению)<br />По умолчанию: <b>5</b> (среднее).',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'auto_brightness',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Режим <b>автоматической регулировки яркости</b> (не для видеокамер с автодиафрагмой). Подстройка происходит через каждые 5 секунд, когда нет движения.<br>По умолчанию: <b>Выкл</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.2',
  'subcats' => NULL,
  'mstatus' => 2,
),


array(
  'name'    => 'skip_frames',
  'type'    => 'INT',
  'def_val' => 1,
  'desc'    => 'При захвате пропускать это количество кадров с каждого канала.'.
'<br />Логика работы: переключились на канал (см. параметр input) - пропустили skip_frames кадров - захватили кадр - переключились на другой канал (другая камера)'.
'<br /><b>Влияет на fps</b>. Для <b>одной камеры на чипе fps=25/skip_frames</b>. <b>От 2 до 4 - fps=12/(skip_frames x кол-во камер)</b>.'.
'<br />При мультиплексировании каналов (к одному чипу BT878/SAA7134/CX2388x подключено несколько камер) не должно быть меньше 1.'.
'<br />По умолчанию: <b>1</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'deinterlacer',
  'type'    => 'CHECK',
  'def_val' => 0,
  'desc'    => 'Фильтр для устранения эффекта &quot;расчёски&quot; (deinterlacing) для кадров с размером по вертикали более 288. При LINE_DOUBLING происходит захват полукадра. При применении других фильтров происходит <b>захват полного кадра с отличным качеством</b>, но несколько повышается загрузка процессора системы. Читайте подробнее о фильтрах в документации.<br>По умолчанию: <b>LINE_DOUBLING</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'switch_filter',
  'type'    => 'INT',
  'def_val' => 10,
  'desc'    => 'Специальный параметр для режимов мультиплексирования. В сочетании с skip_frames используется для отсеивания &quot;битых&quot; или &quot;дрожащих&quot; кадров в режиме переключения каналов. Мин=1, Макс=20.<br>По умолчанию <b>10</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '3.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

/*
array(
  'name'    => 'frequency',
  'type'    => 'INT',
  'def_val' => 0,
  'desc'    => 'Частота тюнера (для ТВ-сигнала) в кГц. По умолчанию: 0 - не использовать ТВ-тюнер.',
  'reloaded'=> 1,
  'in_def'  => 0,
  'in_cam'  => 1,
  'cats'    => '3.2',
  'subcats' => NULL,
  'mstatus' => 1,
),
*/

/* обработка */

array(
  'name'    => 'text2img',
  'type'    => 'BOOL',
  'def_val' => 1,
  'desc'    => '&quot;Врезать&quot; <b>в кадр информационные строки</b> (название камеры, дата/время и др.).<br><br><b>Особенность по сетевым камерам</b>: если захват и запись в MJPEG, то выключено при записи. Рекомендум включить наложение текста на кадр на самой камере (обязательно даты и времени).<br><br>По умолчанию: <b>Вкл</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '5.1',
  'subcats' => '5.1.1',
  'mstatus' => 2,
),


array(
  'name'    => 'motion_detector',
  'type'    => 'BOOL',
  'def_val' => 1,
  'desc'    => '<b>Обнаруживать движение в кадре</b> с помощью <b>Программного Детектора Движения</b>.<br /><br />Ключевая функция для профессиональных систем. На HDD записываются только сеансы с движением. Существенно облегчает поиск в архиве видеозаписей.<br /><br />По умолчанию: <b>Вкл</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '5.1',
  'subcats' => '5.1.2',
  'mstatus' => 2,
),

array(
  'name'    => 'mask_file',
  'type'    => 'CHECK',
  'def_val' => NULL,
  'desc'    => 'Графический JPEG файл с <b>изображением-маской</b> кадра.<br> <br><b>Накладывается на кадр</b> от камеры. <b>Где в маске залито чёрным</b> цветом, там <b>движение игнорируется</b>. Размеры маски должны совпадать с размерами кадра. Имя файла должно содержать только латинские символы и не содержать пробелы.<br><br>По умолчанию: <b>не установлено</b> - не использовать маску.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_CAM,
  'cats'    => '5.1.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'noise_filter',
  'type'    => 'INT',
  'def_val' => 40,
  'desc'    => '<b>Шумовой фильтр.</b><br><br>Максимально допустимая разница между изменением яркости двух точек в одной позиции от последовательно полученных кадров, которая рассматривается как шум, помеха, дрожание изображения (<b>дождь, снег, некачественный видеосигнал</b> и т.п.). <br><br>Допустимые значения <b>от 0 до 255</b>. По умолчанию: <b>40</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '5.1.2',
  'subcats' => NULL,
  'mstatus' => 2,
),


array(
  'name'    => 'motion_sensor',
  'type'    => 'INT',
  'def_val' => 1000,
  'desc'    => '<b>Чуствительность детектора</b>. <b>Определение размера отслеживаемой цели</b>.<br><br>Число пикселей &quot;изменившихся&quot; в новом кадре ( вычисленный с учётом вышеописанных параметров) при котором срабатывает Программный Детектор Движения (ПДД). Такой кадр будет быть сохранён на диске (если же конечно включен режим записи на диск).<br><br>По умолчанию: <b>1000</b>. Более подробно по настройке читайте <a href="'.$conf['docs-prefix'].'motion-detector.html" target="_blank">здесь &gt;&gt;</a>',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '5.1.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'shake_filter',
  'type'    => 'BOOL',
  'def_val' => 1,
  'desc'    => '<b>Фильтрация</b> эффектов <b>быстрого кратковременного дрожания</b> в кадре <b>при мультиплексировании каналов</b> (один чип BT878/SAA7134/CX2388x на несколько камер). Для сетевых видеокамер не используется.<br><br>По умолчанию: <b>Вкл</b>.',
  'flags'=>$F_IN_DEF | $F_IN_CAM,
  'cats'    => '5.1.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'pre_record',
  'type'    => 'INT',
  'def_val' => 3,
  'desc'    => '<b>Предзапись</b>. Если включен режим записи на диск, будет также записано это количество &quot;спокойных&quot; кадров (режим ПРЕДЗАПИСИ), захваченных перед КАЖДЫМ кадром, на котором сработал детектор движения. <b>Существенно увеличивает требование к объёму оперативной памяти</b><br><br>Допустимые значения от <b>0 до 20</b>. По умолчанию: <b>3 кадра</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '5.1.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'post_record',
  'type'    => 'INT',
  'def_val' => 3,
  'desc'    => '<b>Послезапись</b>. Если включен режим записи на диск, будет также записано это количество &quot;спокойных&quot; кадров (режим ПОСЛЕЗАПИСИ), захваченных после КАЖДОГО кадра, на котором сработал детектор движения.<br><br>Допустимые значения от <b>0 до 20</b>. По умолчанию: <b>3 кадра</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '5.1.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'motion_series_end',
  'type'    => 'INT',
  'def_val' => 30,
  'desc'    => 'Мининимальный интервал в сек. &quot;cпокойствия&quot; детектора движения, после чего возникает <b>событие - &quot;ОКОНЧАНИЕ СЕРИИ ДВИЖЕНИЯ&quot;</b> и система переходит в состояние ожидания &quot;НАЧАЛА НОВОЙ СЕРИИ ДВИЖЕНИЯ&quot; в зоне наблюдения этой камеры.<br> <br>Допустимые значения от <b>10 до 600 сек</b>. По умолчанию: <b>30 сек</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '5.1.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'text_left',
  'type'    => 'STRING',
  'max_len' => 30,
  'def_val' => NULL,
  'desc'    => 'Текст в нижнем левом углу кадра. Также это и <b>название камеры</b> или зоны наблюдения.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_CAM,
  'cats'    => '5.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'text_right',
  'type'    => 'STRING',
  'max_len' => 30,
  'def_val' => NULL,
  'desc'    => 'Шаблон текста в правом нижнем углу кадра. По умолчанию: <b>%Y-%m-%d\n%H:%M:%S-%t</b> - временная отметка кадра.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '5.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'text_changes',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Рисовать <b>кол-во изменённых пикселов</b> в верхнем правом углу кадра. <br><br>Необходимо при <b>настройке параметров детектора движения</b>.<br><br>По умолчанию: <b>Выкл</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '5.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'save_video',
  'type'    => 'BOOL',
  'def_val' => 1,
  'desc'    => '<b>Записывать</b> видео <b>на жесткий диск HDD</b> в архив. По умолчанию: <b>Вкл</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'snapshot_interval',
  'type'    => 'INT',
  'def_val' => 0,
  'desc'    => '<b>Дополнительно</b>, без всяких условий, записывать кадры <b>JPEG каждые N секунд</b>.<br><br>По умолчанию: <b>0 - Выкл</b>.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'V.save_fmt',
  'type'    => 'CHECK',
  'def_val' => 'MJPEG',
  'desc'    => '<b>Кодек и формат файла</b> для записи видео. JPEG, MJPEG (.avi) или MPEG4 (.avi).<br>По умолчанию: <b>MJPEG</b> (.avi)',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'V.film_view_fps',
  'type'    => 'INT',
  'def_val' => 7,
  'desc'    => '<b>Скорость воспроизведения</b> - частота кадров в сек (!!! ДЛЯ ПРОСМОТРА, не для записи) <b>видеофильмов AVI</b>, сжатых кодеками MPEG4, MJPEG.<br><br>Допустимые значения: 1..30. По умолчанию: <b>7 кадров/сек</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'V.max_megabytes',
  'type'    => 'INT',
  'def_val' => 5,
  'desc'    => '<b>Маскимальный размер AVI</b> (MPEG4, MJPEG) файла в МегаБайтах.<br><br>Если Вы <b>хотите иметь огромные AVI файлы</b> (по аналогии с DVD, VIDEOCD и т.п.), <b>подумайте сначала</b>, а удобно ли будет с ними работать в режиме доступа к видеоархиву по сети. Также знайте, что реальный размер AVI файлов будет зависеть от настроек системы детектора движения. Если сеансы движения будут короткими по времени, соответственно будет записываться большее кол-во небольших AVI-шек, по одной на каждый сеанс.<br><br><b>А &quot;склеить&quot; несколько AVI-шек в один файл</b> легко можно и на строне клиента с помощью множества программ, например: (win) abcAVI, nero; (linux/unix) ffmpeg, mencoder.<br><br>Допустимые значения: от 1 до 1000(1Гб). &nbsp;По умолчанию: <b>5 Mb</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'V.max_minutes',
  'type'    => 'INT',
  'def_val' => 60,
  'desc'    => '<b>Маскимальная продолжительность</b> видеофильма в <b>минутах</b>. По достижению этого предела будет создан новый файл.<br>Допустимые значения: от 1 до 1440(24 часа) &nbsp;По умолчанию: <b>60 минут</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11.1',
  'subcats' => NULL,
  'mstatus' => 2,
),


array(
  'name'    => 'jpeg_quality',
  'type'    => 'INT',
  'def_val' => 5,
  'desc'    => '<b>Качество</b> записанного кадра в формате <b>JPEG</b> или <b>MJPEG</b> (VBR mode).<br><br>Максимальное качество (размер файла) - 2, минимальное - 30.<br><br>По умолчанию <b>5</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11.1.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'mpeg4_quality',
  'type'    => 'INT',
  'def_val' => 5,
  'desc'    => '<b>Качество</b> записанного кадра в формате <b>MPEG4</b> (VBR mode).<br><br>Максимальное качество (размер файла) - 2, минимальное - 30.<br><br>По умолчанию <b>5</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11.1.2',
  'subcats' => NULL,
  'mstatus' => 2,
),


/* AUDIO */
array(
  'name'    => 'save_audio',
  'type'    => 'BOOL',
  'def_val' => 1,
  'desc'    => '<b>Записывать</b> аудио <b>на жесткий диск HDD</b> в архив. По умолчанию: <b>Вкл</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11',
  'subcats' => NULL,
  'mstatus' => 2,
),


array(
  'name'    => 'A.save_fmt',
  'type'    => 'CHECK',
  'def_val' => NULL,
  'desc'    => '<b>Формат файла/аудиокодек</b> для записи аудиоданных.<br>'.
  'Параметры bitrate/sample_rate копируются с входного потока аудиозахвата (принцип: если исходное качество плохое, то никакими параметрами перекодирования его лучше не сделать) и могут быть немного скорректированы в зависимости от выбранного формата.'.
  '<ul>'.
  '<li>MP2 - MPEG audio layer 2 (.mp2);</li>'.
  '<li>MP3 - MPEG audio layer 3 (.mp3);</li>'.
  '<li>M4A - AAC Advanced Audio Codec MPEG4 part3 (.m4a)  ;</li>'.
  '<li>MOV - AAC Advanced Audio Codec формат файла QuickTime (.mov);</li>'.
  '</ul>'.
  'С невысокими требованиями к качеству (не музыку же пишите):<ul>'.
  '<li>размеры получаемых файлов у всех кодеков примерно одинаковы,</li>'.
  '<li>cкорость кодирования заметно больше при кодировании кодеком MP2 (соответственно нагрузка на CPU меньше),</li>'.
  '<li>качество несколько лучше при кодировании кодеком AAC.</li>'.
  '</ul>По умолчанию: <b>не установлено - запись в оригинальном формате аудиозахвата без преобразований.</b>',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11.2',
  'subcats' => NULL,
  'mstatus' => 2,
),


array(
  'name'    => 'A.max_megabytes',
  'type'    => 'INT',
  'def_val' => 5,
  'desc'    => '<b>Маскимальный размер</b> аудиофайла в <b>MегаБайтах</b>. По достижению этого предела будет создан новый файл.<br>Допустимые значения: от 1 до 1000(1Гб) &nbsp;По умолчанию: <b>5 Mb</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'A.max_minutes',
  'type'    => 'INT',
  'def_val' => 10,
  'desc'    => '<b>Маскимальная продолжительность</b> аудиофайла в <b>минутах</b>. По достижению этого предела будет создан новый файл.<br>Допустимые значения: от 1 до 1440(24 часа) &nbsp;По умолчанию: <b>60 минут</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11.2',
  'subcats' => NULL,
  'mstatus' => 2,
),


array(
  'name'    => 'save_dir_fmt',
  'type'    => 'STRING_200',
  'max_len' => 30,
  'def_val' => 0,
  'desc'    => '<b>Формат имени создаваемых подкаталогов</b> в едином каталоге '.$conf['media-alias'].'.'.
  '<br /><br />Обычные  символы [A-Za-z_-./] в строке форматирования копируются без преобразований. Символы, определяющие   преобразования, предваряются  символом  %, и их заменяют следующие выражения:<ul>'.
'<li>%V - номер камеры;</li>'.
'<li>%О - название камеры (параметр text_left);</li>'.
'<li>%W - последние две цифры текущей серии движения (при вкл. детекторе);</li>'.
'<li>%t - номер кадра в секунде(только для отдельных кадров JPEG);</li>'.
'<li>%a,%b,%d,%H,%m,%S,%w,%y,%Y - параметры <b>даты/времени создания файла</b> согласно функции языка С strftime (man 3 strftime);</li>'.
'<li>%L - используется для перехода на новые подкаталоги &quot;nextXXX&quot; при превышении кол-ва файлов в текущем каталогн значения, определенного параметром &quot;max_files_per_dir&quot;, должно быть последним параметром.</li>'.
  '</ul>'.
  'Недопустимые символы заменяются на символ &quot;_&quot;.'.
		  '<br /><br />По умолчанию: &quot;<b>%Y-%m/%d/%V-%O%L</b>&quot;, например: '.'2007-01/31/17-KORIDOR',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'max_files_per_dir',
  'type'    => 'INT',
  'def_val' => 500,
  'desc'    => '<b>Максимальное количество файлов в одном каталоге для одной камеры</b>.'.
  '<br /><br /><b>Важный параметр</b> влияющий на производительность системы в целом, скорость и удобство работы с архивом. Фактическое количество файлов в одном каталоге зависит от многих условий, в том числе от общего количества камер и значения параметра &quot;store_path_fmt&quot; (см. выше)'.
  '<br /><br />По умолчанию: <b>500</b>, мин.: 50, макс.: 1000',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'dirs_mode',
  'type'    => 'STRING',
  'max_len' => 4,
  'def_val' => NULL,
  'desc'    => '<b>Права доступа на создаваемые каталоги:</b>'.
  '<ul>'.
  '<li>действует на уровне файловой системы, то есть влияет на все способы доступа к архиву;</li>'.
  '<li>уставливается в восьмеричном коде согласно chmod(1);</li>'.
  '<li>действует с учетом маски 0022, см umask(1);</li>'.
  '<li>сброс битов чтения/поиска для &quot;остальных&quot; пользователей ( 0750 ) заблокирует работу с архивом через веб-интерфейс и по FTP;</li>'.
  '<li>параметром &quot;store_path_fmt&quot; нужно полностью разделить все части каталогов с отличными &quot;dirs_mode&quot;.</li>'.
  '</ul>'.
  '<br />По умолчанию: <b>0755</b> - просмотр/чтение для всех пользователей.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'dirs_group',
  'type'    => 'STRING',
  'max_len' => 16,
  'def_val' => NULL,
  'desc'    => '<b>Изменяет группу-владельца создаваемых каталогов:</b>'.
  '<ul>'.
  '<li>действует на уровне файловой системы, то есть влияет на все способы доступа к архиву;</li>'.
  '<li>уставливается как имя группы. См. chgrp(1) и /etc/groups;</li>'.
  '<li>пользователь &quot;daemon&quot; должен быть предварительно включен в группу &quot;dirs_group&quot;;</li>'.
  '<li>параметром &quot;store_path_fmt&quot; нужно полностью разделить все части каталогов с отличными &quot;dirs_group&quot;.</li>'.
  '</ul>'.
  '<br />По умолчанию: <b>не задано, то есть &quot;root&quot; </b>.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11',
  'subcats' => NULL,
  'mstatus' => 2,
),


array(
  'name'    => 'jpeg_name_fmt',
  'type'    => 'STRING_200',
   'max_len' => 30,
  'def_val' => 0,
  'desc'    => '<b>Формат имени файла для сохранения отдельных JPEG файлов</b>.'.
  '<br /><br />Допустимые модификаторы - те же, что и для параметра &quot;store_path_fmt&quot;.'.
  '<br /><br />По умолчанию: &quot;<b>%H_%M_%S-%t</b>&quot;, например: 24_59_59-25.jpg',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'AV.name_fmt',
  'type'    => 'STRING_200',
  'max_len' => 30,
  'def_val' => 0,
  'desc'    => '<b>Формат имени файла для сохранения аудио/видео последовательностей (фильмов)</b>.'.
  '<br /><br />Допустимые модификаторы - те же, что и для параметра &quot;store_path_fmt&quot;.'.
  '<br /><br />По умолчанию: &quot;<b>%H_%M_%S</b>&quot;, например: 24_59_59.avi или 24_59_59.mp3',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '11',
  'subcats' => NULL,
  'mstatus' => 2,
),



/*
'mysql_db~STRING~~Database name to save event~1~1~11~NULL~0',
'mysql_host~STRING~~Database host~1~1~11~NULL~0',
'mysql_password~STRING~~Database user password~1~1~11~NULL~0',
'mysql_user~STRING~~Database user~1~1~11~NULL~0',
*/

/*
* ONLINE ONLINE ONLINE ONLINE ONLINE ONLINE ONLINE ONLINE ONLINE
*/
array(
  'name'    => 'live_view',
  'type'    => 'BOOL',
  'def_val' => 1,
  'desc'    => 'Разрешить режим <b>наблюдения в реальном времени</b>. По умолчанию: <b>Вкл</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '15',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'monitor_live',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Разрешить <b>локальное</b> (с сервера) <b>наблюдение</b> в реальном времени за камерой в программе <b>monitor</b> (<b>mon</b>).<br><br>По умолчанию: <b>Выкл</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '15',
  'subcats' => '15.1',
  'mstatus' => 2,
),

array(
  'name'    => 'webcam_live',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Разрешить <b>наблюдение</b> по сети с <b>через браузер</b> (Motion JPEG over HTTP).<br><br>По умолчанию: <b>Выкл</b>.',
  'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '15',
  'subcats' => '15.2',
  'mstatus' => 2,
),

array(
  'name'    => 'v4l_pipe',
  'type'    => 'CHECK',
  'def_val' => NULL,
  'desc'    => 'Создать <b>виртуальный видеоканал</b>, на который будет <b>транслироваться видео</b> с этой камеры для локального вьювера <b>avreg-mon</b>.<br><br><b>Выбирать файлы</b> каналов нужно из предложенного списка последовательно, <b>без совпадений</b> c другими камерами. Если список пуст или не хватает каналов см. <a href="http://avreg.net/manual_install_avreg-mon.html" target="_blank">инструкцию по установке</a>.<br><br>По умолчанию: <b>не задано</b> - значит <b>локальный просмотр</b> для данной камеры <b>невозможен</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_CAM,
  'cats'    => '15.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'wc_port',
  'type'    => 'INT',
  'def_val' => 0,
  'desc'    => '<b>Номер порта TCP/IP мини-HTTP сервера для передачи потокового видео</b> Motion JPEG <b>по сети на удаленные компьютеры</b> (для реализации <b>распределенных систем видеонаблюдения</b> или простого <b>просмотра через интернет-браузеры</b>).<br><br><b>Нужно назначать без совпадений для каждой в/к, например: 8081,8082,8083 и т.д.</b><br><br>По умолчанию: <b>0 - запретить</b> webcam-сервер на этой в/к.',
  'flags'=>$F_BASEPAR | $F_IN_CAM,
  'cats'    => '15.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

/*
array(
  'name'    => 'wc_localhost',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Позволять смотреть видео с этой в/к по сети только с компьютера сервера(localhost). По умолчанию: Выкл. - разрешить подключение с удаленных компьютеров.',
  'reloaded'=> 0,
  'in_def'  => 1,
  'in_cam'  => 1,
  'cats'    => '15.2',
  'subcats' => NULL,
  'mstatus' => 2,
),
*/

array(
  'name'    => 'wc_maxrate',
  'type'    => 'INT',
  'def_val' => 100,
  'desc'    => 'Максимальная частота в кадрах в сек. для потока кадров, передаваемых по сети.<br><br>По умолчанию: <b>100 - не ограничивать</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '15.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'wc_motion',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => '<b>Передавать</b> по сети <b>только кадры с движением и дополнительно по одному кадру в секунду</b> (чтобы не прерывался поток в периоды спокойствия, когда картинка в кадре не меняется).<br><br>По умолчанию: <b>Выкл</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '15.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'wc_max_conn_per_cam',
  'type'    => 'INT',
  'def_val' => 2,
  'desc'    => 'Максимальное кол-во подключенных сетевых клиентов в одно время для этой камеры. <br><br>Допустимые значения от 1 до 10. По умолчанию: <b>3</b>.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '15.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'wc_one_stream_per_ipaddr',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => '<b>Запрещать множественные соединения</b> к каждой конкретной камере <b>с одного IP-адреса</b> или нет.<br><br>Если сервер и все сетевые клиенты системы видеонаблюдения подключены к одному ethernet коммутатору, то можно установить значение Вкл. В этом случае, даже если клиент запустит две копии(окна) браузера, просмотр будет возможен только в первой копии. То есть, будет действовать запрет множественных просмотров одной и той же камеры с одного компьютера.<br><br>Если в системе возможны &#034;внешние&#034; сетевые клиенты (за прокси/маршрутизаторами/nat) и(или) &#034;далёкие&#034; клиенты (в крупных сетях), то рекомендуем оставить значение Выкл.<br><br>По умолчанию: <b>Выкл.</b>',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '15.2',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'wc_limit',
  'type'    => 'INT',
  'def_val' => 0,
  'desc'    => 'Максимальное количество переданных кадров с момента подключения, после чего передача кадров с этой камеры прекращается. Для возобновления просмотра нажмите &quot;Обновить&quot; (F5) на Ваших браузерах.<br><br>По умолчанию: <b>0</b> - не останавливать передачу.',
  'flags'=>$F_IN_DEF | $F_IN_CAM,
  'cats'    => '15.2',
  'subcats' => NULL,
  'mstatus' => 2,
),



/* EVENTS */
array(
  'name'    => 'reactions',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Реагировать на <b>события</b>.<br><br>Будут выполняться (<b>fork</b>) одноимённые событиям ниже скрипты (<b>bash,perl,php,ruby,...</b>) из каталога &#171;<b>'.$conf['scripts-dir'].'</b>&#187;. Эти скрипты должны:<ol><li>принадлежать владельцу/группе root:avreg (chown root:avreg имя_скрипта);</li><li>быть исполняемыми (chmod 0754 имя_скрипта);</li><li>возвращать "успешный" код 0 при запуске без параметров.</li></ol>В начале запуска(start/restart) или при обновлении некоторых параметров(reload) демон &#171;'.$videoserv.'&#187; пробует вызвать все настроенные (Вкл.) скрипты без параметров. Если скрипт не доступен или возвратил ошибку, то он помечается как сбойный и не будет вызываться, вплоть до следующей успешной проверки. <br><br>Прим: многие события доступны только при вкл. детекторе движения/звука.<br><br>По умолчанию: <b>Выкл</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '20',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'OnAlarm',
  'type'    => 'CHECK',
  'def_val' => NULL,
  'desc'    => 'Если &#171;Вкл.&#187;, то при возникновении нештатных событий   вызывается внешний скрипт &#171;<b>'.$conf['scripts-dir'].'/OnAlarm</b>&#187; с параметрами:<ul><li>--date=&#039;дата_время&#039;, в формате формате RFC2822, например: Thu, 23 Aug 2007 18:02:59 +0400;</li><li>--cams=число, порядковый номер камеры, если 0 - то общесистемное событие;</li><li>--level=число, уровень серьёзности, см. syslog levels;</li><li>--msg="строка", краткое описание проблемы;</li></ul>По умолчанию: &#060;пусто&#062; - не использовать.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '20',
  'subcats' => NULL,
  'mstatus' => 2,
),


array(
  'name'    => 'OnVideoMotion',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Разрешить <b>обработчики</b> на событии &quot;<b>Начало серии движения</b>&quot; (не путать с одиночными срабатываниями детектора движения).<br /><br />Если &#171;Вкл.&#187;, то вызывается внешний скрипт &#171;<b>'.$conf['scripts-dir'].'/OnVideoMotion</b>&#187; с параметрами:<ul><li>--date=&#039;дата_время&#039;, формат см. выше, аналогично для OnAlarm;</li><li>--cams=число, порядковый номер камеры;</li><li>--series=число, порядковый номер серии;</li><li>--evt-id=число, тип события: 13 - начало серии, 14 - окончание;</li><li>--first-jpeg - абсолютное имя jpeg-файла, первого кадра с движением;</li></ul>По умолчанию: <b>Выкл.</b>',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '20',
  'subcats' => NULL,
  'mstatus' => 2,
),

/*
array(
  'name'    => 'OnVoiceDetection',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Разрешить <b>обработчики</b> на событии &quot;<b>Окончание серии движения</b>&quot;.<br /><br />Если поставили Вкл., то ещё нужно настроить каждый обработчик в отдельности.<br /><br />По умолчанию: <b>Выкл</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '20',
  'subcats' => NULL,
  'mstatus' => 2,
),
*/

array(
  'name'    => 'OnFilesSave',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => 'Разрешить <b>обработчики</b> по событиям &quot;<b>Запись файлов на жесткий диск HDD</b>&quot;.
<br><br>Если &#171;Вкл.&#187;, то вызывается внешний скрипт &#171;<b>'.$conf['scripts-dir'].'/OnVideoMotion</b>&#187; с параметрами:
<ul>
<li>--date=&#039;дата_время&#039;, формат см. выше, аналогично для OnAlarm;</li>
<li>--cams=число, порядковый номер камеры;</li>
<li>--series=число, порядковый номер серии;</li>
<li>--file-id=число, определяет тип файла:
<ul>
<li>[15..21] - jpeg-и: forced,first,motion,pre,post,nomotion;</li>
<li>23 - видеофильм;</li>
</ul>
</li>
<li>--fname=&#039;строка&#039;, абсолютное имя файла;</li>
<li>--fsizeKb=число, размер файла в КилоБайтах;</li>
</ul>
По умолчанию: <b>Выкл</b>.',
  'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '20',
  'subcats' => NULL,
  'mstatus' => 2,
),

/*
array(
  'name'    => 'beep_on_first',
  'type'    => 'BOOL',
  'def_val' => 0,
  'desc'    => '<b>Пищать</b> коротким писком <b>системного динамика</b><br><br>По умолчанию: <b>нет</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '20.1',
  'subcats' => NULL,
  'mstatus' => 2,
),
*/

array(
  'name'    => 'play-on-first',
  'type'    => 'CHECK',
  'def_val' => NULL,
  'desc'    => 'Передать скрипту имя предварительно загруженного звукового файла (<b>.wav</b>).<br><br>--play-on-first=&#039;строка&#039;, абсолютное имя файла.<br><br>По умолчанию: <b>нет</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '20.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'play-on-end',
  'type'    => 'CHECK',
  'def_val' => NULL,
  'desc'    => 'Передать скрипту имя предварительно загруженного звукового файла (<b>.wav</b>).<br><br>--play-on-end=&#039;строка&#039;, абсолютное имя файла.<br><br>По умолчанию: <b>нет</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '20.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

array(
  'name'    => 'mailto',
  'type'    => 'STRING',
  'def_val' => NULL,
  'desc'    => 'Передать скрипту этот e-mail адрес.<br><br>--mailto=&#039;e-mail&#039; .<br><br>По умолчанию: <b>нет</b>.',
  'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
  'cats'    => '20.1',
  'subcats' => NULL,
  'mstatus' => 2,
),

);

$PARAMS_NR=count($PARAMS);

?>
