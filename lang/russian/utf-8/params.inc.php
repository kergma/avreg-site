<?php
/**
 * @file lang/russian/utf-8/params.inc.php
 * @brief Содержит элементы и папаметры ренерации страниц администраторских настроек
 * камер и т.п. 
 */


$F_IN_DEF   = 0x0001;
$F_IN_CAM   = 0x0002;
$F_RELOADED = 0x0004;

$F_BASEPAR  = 0x0100;

$vid_standarts = array ('PAL (цв.в/к)', 'NTSC (цв.в/к)', 'SECAM (не для в/к)', 'PAL NC (ч/б в/к)' );
$strCamType = array('netcam', 'v4l');
$strNetProto = array('http');
$strFileFmt = array('jpeg', 'avi/mjpeg', 'avi/mpeg4', 'mov', 'flv');

$v4l_hacks = array('v4lver1', 'v4lver1+block');

$str_audio_force_fmt = array(
   'pcm_mulaw',
   'pcm_alaw',
   'g726_32k',
   'g726_24k',
   'pcm_s8',
   'pcm_u8',
);

$str_audio_save_fmt = array('wav','mp2','ogg/flac','mov','m4a');

/*
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
'800x600',
'1024x768',
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
'600x800',
'768x1024',
'720x1280',
'960x1280',
'1024x1280',
'1200x1600',
);
 */

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

$flip_type = array('зеркально', 'вращение 180');

$v4l_int_cntrl='<p>Допустимые значения: 0 или &#171;пусто&#187; - не&nbsp;устанавливать или не&nbsp;подстраивать значение этого параметра; или установить значение  [1(мин.)..5(средн.)..9(макс.)].</p>По умолчанию: &#171;<b>пусто</b>&#187; (не&nbsp;подстраивать).';

$recording_mode = array('Без записи', 'Выборочно', 'Всё подряд');
$strOnlySelDet='Доступно <i>только при выборочном режиме записи</i> (<i>recording</i>=&laquo;Выборочно&raquo;) и включенном детекторе движения (<i>motion_detector</i>=&laquo;Вкл.&raquo;).';

$_rate_lim_info = 'Ограничить этим значением скорость отдаваемого видеопотока, в кадрах в секунду.<br><br>Допустимые значения: [1..60]; по-умолчанию: <b>60 - не ограничивать</b>.';

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
      'name'=>'Захват',
      'desc'=>'Выбор типа устройства и настройка параметров аудио/видео захвата',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> $conf['docs-prefix'].'apps-quick-conf.html'
   ),

   array(
      'id'=>'3.1',
      'name'=>'ip-камеры',
      'desc'=>'Параметры доступа к сетевым IP-камерам и IP-видеосерверам',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> $conf['docs-prefix'].'apps-ipcam-capture.html'
   ),

   array(
      'id'=>'3.1.1',
      'name'=>'протокол &#171;http://&#187;',
      'desc'=>'захват по протоколу &#171;http://&#187;',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> NULL
   ),

   array(
      'id'=>'3.1.1.1',
      'name'=>'видео',
      'desc'=>'захват в форматах mjpeg или jpeg по &#171;http://&#187;',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> NULL
   ),

   array(
      'id'=>'3.1.1.2',
      'name'=>'аудио',
      'desc'=>'захват в форматах pcm,adpcm,G.72x или aac (Axis) по &#171;http://&#187;',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> NULL
   ),

   array(
      'id'=>'3.2',
      'name'=>'video4linux',
      'desc'=>'Настройки PCI-плат видеозахвата и USB-камер',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> 'http://avreg.net/howto_linux-capture-cards.html'
   ),

   array(
      'id'=>'5',
      'name'=>'Обработка',
      'desc'=>'Алгоритмы обработки аудио/видео данных',
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
      'mstatus'=> 1,
      'help_page'=> NULL
   ),
   array(
      'id'=>'5.1.2',
      'name'=>'контроль яркости',
      'desc'=>'Контроль средней яркости изображения (засветка, затемнение)',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> NULL
   ),
   array(
      'id'=>'5.1.3',
      'name'=>'детектор',
      'desc'=>'Настройка детектора движения',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> NULL
   ),

/*
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
 */

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
      'mstatus'=> 1,
      'help_page'=> NULL
   ),

   array(
      'id'=>'11.2',
      'name'=>'аудио',
      'desc'=>'Только аудио (без видео)',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> NULL
   ),

/*
array(
    'id'=>'11.3',
    'name'=>'видео + аудио',
    'desc'=>'Совместно: видео + аудио',
    'flags'=> $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
    'mstatus'=> 1,
    'help_page'=> NULL
    ),
 */

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
      'mstatus'=> 1,
      'help_page'=> $conf['docs-prefix'].'work-monitor.html'
   ),

   array(
      'id'=>'15.2',
      'name'=>'по сети',
      'desc'=>'Удаленный просмотр по сети (в интернет-браузере или &quot;вышестоящим&quot; видеосервером AVReg или другим DVR)',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> NULL
   ), 

   array(
      'id'=>'20',
      'name'=>'События',
      'desc'=>'Внешние обработчики событий',
      'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'mstatus'=> 1,
      'help_page'=> 'http://avreg.net/manual_applications_avregd-event-collector.html'
   ),
);

$PAR_GROUPS_NR=count($PAR_GROUPS);

// $VAL_TYPE, $DEF_VAL,$COMMENT, $RELOADED, $VIEW_ON_DEF, $VIEW_ON_CAM, $PAR_CATEGORY, $SUBCAT_SELECTOR, $MASTER_STATUS
$PARAMS = array(

   array(
      'name'    => 'work',
      'type'    => $BOOL_VAL,
      'def_val' => 0,
      'desc'    => 'Вкл./Выкл. <b>видеозахват</b>а с видеокамеры (читай: <b>работать с этой камерой или нет</b>).<br><br>По умолчанию: <b>Выкл</b>.',
      'flags'=>$F_BASEPAR | $F_IN_CAM,
      'cats'    => '1',
      'subcats' => NULL,
      'mstatus' => 2,
   ),

   array(
      'name'    => 'debug',
      'type'    => $BOOL_VAL,
      'def_val' => 0,
      'desc'    => 'Вкл./Выкл. <b>режим отладки</b>.<br><br>Включение режима отладки <b>существенно замедляет работу системы</b>, так как при этом в системный журнал пишется много отладочных сообщений необходимых <b>для разбора нештатных ситуаций</b>.<br><br>По умолчанию: <b>Выкл</b>.',
      'flags'   => $F_RELOADED | $F_IN_CAM,
      'cats'    => '1',
      'subcats' => NULL,
      'mstatus' => 1,
   ),

   array(
      'name'    => 'text_left',
      'type'    => $STRING_VAL,
      'max_len' => 30,
      'def_val' => NULL,
      'desc'    => 'Название камеры или зоны наблюдения.',
      'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_CAM,
      'cats'    => '3',
      'subcats' => '3.1;3.2',
      'mstatus' => 1,
   ),

   array(
      'name'    => 'cam_type',
      'type'    => $CHECK_VAL,
      'def_val' => 'netcam',
      'desc'    => '<b>Тип  видеокамеры</b>:<ul><li><b>netcam</b> - сетевые IP-камеры или видеосервера;</li><li><b>v4l</b> - (<b>video4linux</b> - video for linux) устройства: аналоговые видеокамеры, подключаемые к PCI-платам видеозахвата или ТВ-тюнерам, а также USB-камеры.</li></ul>По умолчанию: &quot;<b>netcam</b>&quot;.',
      'flags'   => $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
      'cats'    => '3',
      'subcats' => '3.1;3.2',
      'mstatus' => 1,
   ),

   array(
      'name'    => 'fps',
      'type'    => $STRING_VAL,
      'valid_preg' => '/\A\d+(\s*[x:\/]\s*\d+)*\Z/',
      'def_val' => NULL,
      'desc'    => '<b>Желаемая (не фактическая) скорость видеозахвата в кадрах в секунду</b>.
      <br>Допустимые значения:
      <ul>
      <li><b>&laquo;пусто&raquo;</b> или 0 - не ограничивать, т.е. видеозахват на максимально возможной скорости устройства;</li>
      <li><b>[1-60] или дробь период_в_сек/кадров_за_период</b>  - ограничить скорость видеозахвата, если это позволяет драйвер или способ доступа к камере.</li>
      </ul>
      Примечания:
      <ul>
      <li>при захвате <i>с сетевых устройств (&laquo;cam_type=netcam&raquo;)</i>:
      <ul>
      <li>потоковым способом (mjpeg,mpeg4) - настройка скорости в кадрах в секунду (framerate) регулируется (ограничивается) настройками самих ip-камер/видеосерверов, а в продвинутых моделях может указываться в параметрах запроса к устройствам (например, <nobr>V.http_get=/axis-cgi/mjpg/video.cgi?fps=15</nobr>);</li>
      <li>циклическим захватом одиночных кадров JPEG (режим snapshot или still image) - framerate захвата регулируется демоном avregd согласно заданного fps, но с учётом возможностей камеры и пропускной способности сети.
      </li>
      </ul>
      </li>
      <li>при захвате <i>с video4linux устройств (&laquo;cam_type=v4l&raquo;)</i>:
      <ul>
      <li>при использовании PCI плат видеозахвата без режима мультиплексирования (каждая  камера подключена на свой отдельный видеокодер) или USB-камер, скорость видеозахвата ограничивается используемым телевизионным стандартом и возможностями устройства. Если необходимо скорость понизить, то можно указывать fps в диапазоне [1..15].</li>
      <li>скорость видеозахвата с PCI плат видеозахвата в режиме мультиплексирования регулируется только количеством видеокамер, подключенных к одному видеокодеру (устройству video4linux, параметр v4l_dev) и составляет [4..6] fps на камеру при 2-камерах на видеокодер и ещё меньше при бОльшем кол-ве камер на один видеокодер.
      </li>
      </ul>
      </li>
      </ul>
      </ul>
      По умолчанию: &laquo;<b>пусто</b>&raquo; - <b>не ограничивать</b>.',
      'flags'   => $F_BASEPAR | $F_IN_DEF | $F_IN_CAM | $F_RELOADED,
      'cats'    => '3',
      'subcats' => '3.1;3.2',
      'mstatus' => 1,
   ),

   array(
      'name'    => 'geometry',
      'type'    => $STRING_VAL,
      'valid_preg' => '/\A\d+\s*[x:\/]\s*\d+\Z/',
      'def_val' => '640x480',
      'desc'    => 'Размер кадра  в пикселях (<b>ширина х высота</b>).
      <ul><li><b>Для video4linux камер</b> установите пропорционально разрешающей способности видеокамеры в ТВЛ с учётом требуемого качества и ресурсов видеосервера. Для ВСЕХ каналов ОДНОГО устройства видеозахвата (более точно - для одной микросхемы видеокодера bt878a, saa7130,...) должно быть ОДНО значение:<br>384x288<sup>*</sup>, 480x360<sup>*</sup>, 560x420<sup>*</sup>, 640x480<sup>*</sup>, 720x540<sup>*</sup>, 720x576 (макс.&nbsp;saa713x), 768x576<sup>*</sup> (макс.&nbsp;bt878a). Прим.: [*] - оптимально при просмотре на мониторе с соотношением сторон 4:3</li><li><b>Для сетевых</b> ip-камер - установите значение размеров кадра, которые определены в настройках самой камеры или заданы в параметрах запроса к камере.</li></ul>По умолчанию: <b>640x480</b>.',
         'flags'   => $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3',
         'subcats' => '3.1;3.2',
         'mstatus' => 1,
      ),

      array(
         'name'    => 'Hx2',
         'type'    => $BOOL_VAL,
         'def_val' => 0,
         'desc'    => '<b>Программное масштабирование при захвате полукадрами</b>. Увеличить значение разрешения по вертикали в 2 раза при просмотре видео в реальном времени, а также записывать увеличенное значение (вместо реального) в базу и передавать в event-collector скрипт. Установите, если для снижения нагрузки на видеосервер вы используете  <b>захват полукадрами</b>, т.е. обычно тогда, когда разрешение по вертикали <nobr>&lt;= 288(pal)/240(secam)</nobr>, например: 720х288 или 640х240.<br><br>По умолчанию: <b>Выкл. - не масштабировать.</b>.',
         'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3',
         'subcats' => '3.1;3.2',
         'mstatus' => 1,
      ),

      array(
         'name'    => 'color',
         'type'    => $BOOL_VAL,
         'def_val' => TRUE,
         'desc'    => 'Какой кадр <b>ожидает</b> получить программа от видеокамеры: <b>цветной или монохромный</b>. Важный параметр, <b>ставьте реальные значения</b>. Для video4linux камер смотрите также описание параметра &#171;<b>norm</b>&#187;.<br><br>По умолчанию: <b>Вкл. (цветная в/к)</b>.',
         'flags'   => $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3',
         'subcats' => '3.1;3.2',
         'mstatus' => 1,
      ),

      array(
         'name'    => 'rotate',
         'type'    => $CHECK_VAL,
         'def_val' => 0,
         'desc'    => 'Программный <b>разворот кадра</b>.<br><br>Увеличивает нагрузку на CPU сервера, поэтому используйте эту возможность только в случае существенной необходимодимости. Большинство &quot;правильных&quot; сетевых камер могут делать поворот самостоятельно.<br><br>По умолчанию: <b>без поворота</b>.',
         'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3',
         'subcats' => '3.1;3.2',
         'mstatus' => 1,
      ),

      array(
         'name'    => 'deinterlacer',
         'type'    => $BOOL_VAL,
         'def_val' => 0,
         'desc'    => 'Фильтр для <b>устранения эффекта &quot;расчёски&quot или &quotгребёнки&quot;</b> на движущихся объектах.
         <p>Если значение параметра не установлено явно, то программа будет применять фильтр, только при захвате interlaced кадров (обычно при захвате с video4linux устройств с разрешением захвата по вертикали более 288(PAL)/240(NTSC) пикселей).
         </p>По умолчанию: <b>не определено - авто</b>.',
         'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3',
         'subcats' => '3.1;3.2',
         'mstatus' => 1,
      ),

      /* настройки сетевых камер */

      array(
         'name'    => 'InetCam_Proto',
         'type'    => $CHECK_VAL,
         'def_val' => NULL,
         'desc'    => '<b>Протокол доступа</b> к сетевой видеокамере или видеосерверу: <b>http, rtsp, rtsp over http</b>.<br><br>По умолчанию: &quot;<b>http</b>&quot;',
         'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'InetCam_IP',
         'type'    => $STRING_VAL,
         'def_val' => NULL,
         'desc'    => '<b>IP-адрес</b> сетевой видеокамеры или видеосерверов (например Axis, Planet, D-Link, Panasonic, Beward, Aviosys и т.п. ).<br><br>По умолчанию: <b>не установлено</b>.',
         'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'InetCam_USER',
         'type'    => $STRING_VAL,
         'def_val' => NULL,
         'desc'    => '<b>Имя пользователя</b> для доступа к сетевой видеокамере (если необходимо). <br>По умолчанию: <b>не установлено</b>.',
         'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'InetCam_PASSWD',
         'type'    => $PASSWORD_VAL,
         'def_val' => NULL,
         'desc'    => '<b>Пароль</b> пользователя для доступа к сетевой видеокамере (если необходимо).<br>По умолчанию: <b>не установлено</b>.',
         'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'InetCam_http_port',
         'type'    => $INT_VAL,
         'def_val' => 80,
         'desc'    => '<b>Номер порта TCP/IP</b> на котором сетевая камера или видеосервер слушают запросы HTTP.<br />По умолчанию: &quot;<b>80</b>&quot;.',
         'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'http/1.0',
         'type'    => $BOOL_VAL,
         'def_val' => FALSE,
         'desc'    => '<b>Использовать устаревшую версию 1.0 протокола HTTP для исходящих соединений.</b> В частности, может быть полезно при работе с ip-камерами с некорректной реализацией протокола HTTP в режиме захвата одиночных кадров (snapshot mode).<br />По умолчанию: &quot;<b>Выкл.</b>&quot; - используется версия http/1.1 c поддержкой persistent connection.',
         'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'nc_conn_tries_period',
         'type'    => $INT_VAL,
         'def_val' => 5,
         'desc'    => '<b>Интервал (в сек.) между попытками подключения</b>. Прим: первый &#034;переконнект&#034; после разрыва потока - в половину меньше.<br />Диапазон: [2..60], по умолчанию: &quot;<b>5 сек.</b>&quot;.',
         'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'nc_wait_conn_timeout',
         'type'    => $INT_VAL,
         'def_val' => 7,
         'desc'    => '<b>Таймаут (в сек.) ожидания установления соединения</b>.<br />Диапазон: [3..60], по умолчанию: &quot;<b>7 сек.</b>&quot;.',
         'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'nc_read_timeout',
         'type'    => $INT_VAL,
         'def_val' => 5,
         'desc'    => '<b>Таймаут (в сек.) ожидания ожидания данных из  соединения</b>.<br />Диапазон: [2..30], по умолчанию: &quot;<b>5 сек.</b>&quot;.',
         'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'nc_max_http_stream_errors',
         'type'    => $INT_VAL,
         'def_val' => 5,
         'desc'    => '<b>Количество логических ошибок в протоколе приводящее к принудительному разрыву соединения</b>. В некоторых случаях, например: на оч. медленных каналах или проблемных камерах, увеличения значения этого параметра позволяет всё же обеспечить непрерывный видеозахват.<br />Диапазон: [2..10], по умолчанию: &quot;<b>5</b>&quot;.',
         'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),


      array(
         'name'    => 'V.http_get',
         'type'    => $STRING200_VAL,
         'def_val' => NULL,
         'desc'    => '<b>Строка запроса GET</b> протокола HTTP на получение потокового видео MJPEG (live) или одиночного кадра JPEG (snapshot).<br><br>Например для Axis:<br />
         mjpg: <b>/axis-cgi/mjpg/video.cgi?resolution=640x480&amp;color=1&amp;fps=5</b>
         <br />
         jpeg: <b>/axis-cgi/jpg/image.cgi?resolution=320x240&amp;camera=1&amp;compression=25</b>
         <br><br>для удалённого AVReg:<br />
         mjpg: <b>/avreg-cgi/mjpg/video.cgi?camera=5&fps=5</b>
         <br />
         jpeg: <b>/avreg-cgi/jpg/image.cgi?camera=1</b>
         <br /><br />Не знаете запрос для вашей камеры - читайте <a href="'.$conf['docs-prefix'].'apps-ipcam-capture.html" target="_blank">здесь &gt;&gt;</a>
         <br /><br />По умолчанию: <b>&quot;не установлено&quot; - не захватывать видео</b>',
         'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
         'cats'    => '3.1.1.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'Aviosys9100_chan',
         'type'    => $INT_VAL,
         'def_val' => NULL,
         'desc'    => '<b>Только для шлюзов Aviosys 9100 (B/RK/A) в режиме roundrobin</b>.<br><br><b>Номер камеры/канала [0,1,2,3]</b> на шлюзе при захвате в режиме roundrobin.<br><br>По умолчанию: <b>не установлено</b> - не Aviosys 9100 в roundrobin.',
         'flags'=> $F_IN_CAM,
         'cats'    => '3.1.1.1',
         'subcats' => NULL,
         'mstatus' => 1,
      ),

      array(
         'name'    => 'A.http_get',
         'type'    => $STRING200_VAL,
         'def_val' => NULL,
         'desc'    => '<b>Строка запроса GET</b> протокола HTTP на получение аудио-потока в форматах pcm G.711 64kbit/s, adpcm G.726 32kbit/s и G.723 24kbit/s или AAC (rtp over http, Axis).<br><br>
         Например для Axis: &quot;<b>/axis-cgi/audio/receive.cgi</b>&quot;
<br /><br />Не знаете запрос для вашей камеры - читайте <a href="'.$conf['docs-prefix'].'apps-ipcam-capture.html" target="_blank">здесь &gt;&gt;</a>'.
   '<br /><br />По умолчанию: <b>&quot;не установлено&quot; - не захватывать аудио</b>',
   'flags'=>$F_BASEPAR | $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.1.1.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'A.force_fmt',
   'type'    => $CHECK_VAL,
   'def_val' => NULL,
   'desc'    => '<b>Принудительно использовать этот аудио формат для входящего аудиопотока</b> с камер, которые не передают информацию о формате и способе кодирования аудио или передают её неправильно.<br />'.
   '<ul>'.
   '<li>pcm_mulaw - pcm mu-law 8bit 64kbit/s (audio/basic);</li>'.
   '<li>pcm_alaw - pcm a-law 8bit 64kbit/s;</li>'.
   '<li>pcm_s8 - pcm signed linear (2`s complement) 8bit 64kbit/s;</li>'.
   '<li>pcm_u8 - pcm unsigned linear 8bit 64kbit/s;</li>'.
   '<li>g726_32k - adpcm g726 4bit 32kbit/s (audio/32ADPCM);</li>'.
   '<li>g726_24k - adpcm g726 3bit 24kbit/s (audio/G723).</li>'.
   '</ul>По умолчанию: &quot;<b>не установлено</b>&quot; - формат ожидается в заголовке ответа сервера',
   'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.1.1.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'http_user_agent',
   'type'    => $STRING200_VAL,
   'def_val' => NULL,
   'desc'    => 'Поле <b>User-Agent</b> запроса HTTP. По умолчанию: &quot;<b>'.$conf['daemon-name'].'/$ver</b>&quot;.',
   'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.1.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'http_referer',
   'type'    => $STRING200_VAL,
   'def_val' => NULL,
   'desc'    => 'Поле <b>Referer</b> запроса HTTP. По умолчанию: <b>не передается</b>.',
   'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.1.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

/*
array(
  'name'    => 'http_boundary',
  'type'    => $STRING_VAL,
  'def_val' => NULL,
  'desc'    => 'Строка <b>boundary</b> для сетевых видеокамер, имеющим отклонения при передаче потока multipart/mixed-replace от стандарта протокола HTTP.<br>По умолчанию: <b>не установлено</b>.',
  'reloaded'=> 1,
  'in_def'  => 1,
  'in_cam'  => 1,
  'cats'    => '3.1.1',
  'subcats' => NULL,
  'mstatus' => 1,
),
 */


array(
   'name'    => 'v4l_dev',
   'type'    => $CHECK_VAL,
   'def_val' => NULL,
   'desc'    => 'Спец. <b>файл video4linux устройства видеозахвата</b>.
   <p>Обычно video4linux устройство указывает на свой конкретный видеокодер BT878/SAA71xx/CX2388x, которых на одной плате может быть и несколько. Например, большинство 16 канальных плат с 4 видеокодерами будут представлены в системе как 4 отдельных устройства /dev/video[0..3]. Встречаются и исключения, например, плата Kodikom 4400R, которая представлена драйвером как одно 16-канальное video4linux устройство.</p>По умолчанию: <b>не установлено</b>.',
   'flags'   => $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'v4l_hack',
   'type'    => $CHECK_VAL,
   'def_val' => 0,
   'desc'    => '<p><b>&laquo;v4lver1&raquo;</b> - принудительное использование устаревшего API video4linux1 (версия 1). Может оказаться полезным при захвате с устройств (часто USB-камеры) с некачественными (сырыми) драйверами video4linux2 (версия  2).</p>
   <p><b>&laquo;v4lver1&#043;block&raquo;</b> дополнительно к &laquo;v4lver1&raquo; использовать <b>блокирущий режим доступа</b> к устройству. Иногда помогает с сырыми драйверами USB-камер. Может <b>серъёзно повредить захвату с других нормальных устройств</b>.
   </p>
   По умолчанию: <b>не установлено</b> - поддерживаемую версию API сообщает драйвер, работа с устройством в неблокируещем режиме.',
   'flags'   => $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'norm',
   'type'    => $CHECK_VAL,
   'def_val' => 0,
   'desc'    => '<b>Видеостандарт</b>: <ul><li><b>PAL</b> - для большинства <b>цветных</b> камер;</li><li>NTSC - для оригинальных американских или японских;</li><li>SECAM - только для телевизионного сигнала;</li><li><b>PAL NC</b> (no colour) - Для <b>ч/б</b> видеокамер.</li></ul>Для ВСЕХ каналов (input) одного конкретного video4linux устройства &#171;<span class="param">v4l_dev</span>&#187; может быть установлено только один стандарт, т.е. подключены камеры только одного видеостандарта цветности.<br><br>По умолчанию: <b>PAL (цв.)</b>.',
   'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'input',
   'type'    => $CHECK_VAL,
   'def_val' => 0,
   'desc'    => '<b>Номер канала</b> (начиная с 0) video4linux устройства &#171;<span class="param">v4l_dev</span>&#187;, к которому физически подключена камера.
   <p><b>Сочетание значений v4l_dev и input</b> фактически <b>указывают на номер разъёма</b>. Определить состав каналов поможет утилита &#171;<span class="cmd">v4l-info</span>&#187; или любая ТВ-смотрелка (xawtv, tvtime, ...).
   </p>По умолчанию: <b>0</b>. Допустимые значения: обычно [0..3], редко [0..15].',
   'flags'   => $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'auto_brightness',
   'type'    => $BOOL_VAL,
   'def_val' => 0,
   'desc'    => 'Режим <b>автоматической регулировки яркости</b>.<p>Подстройка осуществляется каждые 5 секунд, только при включенном &#171;<span class="param">brightness_control</span>&#187; и только когда не фиксируется движение.</p>По умолчанию: <b>Выкл</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'brightness',
   'type'    => $INT_VAL,
   'def_val' => NULL,
   'desc'    => '<b>Яркость</b> ' . $v4l_int_cntrl . ' <br><br>Прим.: другие многочисленные (обычно менее применимые) video4linux-параметры можно устанавливать до запуска демона &#171;'.$conf['daemon-name'].'&#187;, определяя параметры запуска модулей ядра устройств видеозахвата или с помощью таких утилит, как &#171;<span class="cmd">v4lctl</span>&#187;.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'contrast',
   'type'    => $INT_VAL,
   'def_val' => NULL,
   'desc'    => "<b>Контраст</b>. $v4l_int_cntrl",
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'saturation',
   'type'    => $INT_VAL,
   'def_val' => NULL,
   'desc'    => "<b>Насыщенность цвета</b>. $v4l_int_cntrl",
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '3.2',
   'subcats' => NULL,
   'mstatus' => 1,
),


/*
array(
  'name'    => 'frequency',
  'type'    => $INT_VAL,
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
   'type'    => $BOOL_VAL,
   'def_val' => 1,
   'desc'    => '<b>&quot;Врезать&quot; в кадр информационные строки</b> (название камеры, дата/время и др.).
   <br><br>Замечания:
   <ul>
   <li><b>Особенность по сетевым ip-камерам</b>: если и захват и запись в MJPEG, то для записи и для передачи сетевым клиентам используются оригинальные JPEG-кадры, полученные с ip-камер. В этом конкретном случае, &quot;врезка&quot; текстовой информации будет видна только при локальном просмотре в программе avreg-mon для задачи настройки детектора движения. Для ip-камер настоятельно рекомендуем включить наложение даты/времени и, желательно, названия камеры на кадр в настройках камеры (часто называют &quot;text overlay&quot;).</li>
   <li><b>Области &quot;врезки&quot; текста исключаются из анализа детектором движения</b>, т.к при &quot;врезке&quot; модифицируются оригинальные видеокадры с камеры.</li>
   </ul>По умолчанию: <b>Вкл</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1',
   'subcats' => '5.1.1',
   'mstatus' => 2,
),


array(
   'name'    => 'brightness_control',
   'type'    => $BOOL_VAL,
   'def_val' => 1,
   'desc'    => '<b>Контролировать среднее значение яркости в кадре или нет</b>.<p>Используется для получения событий засветки и затемнения камеры, автоподстройки яркости аналоговых плат видеозахвата, а также может быть использовано в алгоритмах программного детектора движения.</p>По умолчанию: <b>Вкл</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1',
   'subcats' => '5.1.2',
   'mstatus' => 2,
),

array(
   'name'    => 'motion_detector',
   'type'    => $BOOL_VAL,
   'def_val' => 1,
   'desc'    => '<b>Обнаруживать движение в кадре</b> с помощью <b>Программного Детектора Движения</b>.<br /><br />Ключевая функция для профессиональных систем. На HDD записываются только сеансы с движением. Существенно облегчает поиск в архиве видеозаписей.<br /><br />По умолчанию: <b>Вкл</b>.',
   'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1',
   'subcats' => '5.1.3',
   'mstatus' => 2,
),

array(
   'name'    => 'dazzle_threshold',
   'type'    => $INT_VAL,
   'def_val' =>  200,
   'desc'    => '<b>Максимальный порог</b> среднего значения яркости в кадре,
   <br />при котором <b>вы считаете</b>, что камера подверглась <b>засветке</b>.
   <br /><br />По умолчанию: <b>200</b>, допустимые значения [180..255].',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'darkness_threshold',
   'type'    => $INT_VAL,
   'def_val' =>  50,
   'desc'    => '<b>Минимальный порог</b> среднего значения яркости в кадре,
   <br />при котором <b>вы считаете</b>, что камера подверглась <b>затемнению</b>.
   <br /><br />По умолчанию: <b>50</b>, допустимые значения [0..80].',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'bright_hysteresis',
   'type'    => $INT_VAL,
   'def_val' =>  5,
   'desc'    => '<b>Гистерезиз (в секундах) принятия решения о засветке/затемнении кадра</b>.
   <br />Интервал, в течении которого, среднее значение яркости в кадре стабильно превышает или не достигает порогов &#171;<span class="param">dazzle_threshold</span>&#187; и &#171;<span class="param">darkness_threshold</span>&#187;, соответственно.
   <p>Определяя значение &#171;<span class="param">bright_hysteresis</span>&#187;, кроме всего прочего, следует учитывать:</p>
   <ul>
   <li>значение средней яркости измеряется и контролируется не чаще 1 раза в секунду (<= 1 fps);</li>
   <li>большинство видеокамер (и аналоговых и сетевых) пытаются самостоятельно выравнивать яркость в некоторых пределах.</li>
   </ul>
   По умолчанию: <b>5 сек.</b>, допустимые значения [1..60].',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'mask_file',
   'type'    => $CHECK_VAL,
   'def_val' => NULL,
   'desc'    => 'Графический JPEG файл с <b>изображением-маской</b> кадра, который <b>&quot;накладывается&quot; на кадр</b> от камеры. <b>На областях, залитых в маске чёрным</b> цветом, <b>движение игнорируется</b>. Обязательно <b>исключите области</b>, попадающие в поле зрения камеры из анализа детектором, подобные этим:
   <ul>
   <li>&quot;<b>неинтересные</b>&quot; для вас, чужая территория, например, или большая область неба (если конечно не боитесь диверсантов-дельтапланеристов),</li>
   <li><b>сильные источники шума</b> (области засветки камеры: прямой солнечный свет или приборы освещения; качающееся на ветру дерево; и т.п.).</li>
   </ul>
   <p>Размер изображения маски должен совпадать с размерами кадров, захватываемых с камеры. Для создания маски, возьмите из архива любой сохранённый JPEG-кадр с камеры и залейте &quot;ненужные&quot; области чёрным цветом в любом графическом редакторе (GIMP, например). Имя файла маски должно содержать только латинские символы и не содержать пробелы и спец. символы. После установки или изменения маски потребуется подстройка параметров &#171;<span class="param">diff_pxls_threshold</span>&#187; и, возможно, &#171;<span class="param">noise_threshold</span>&#187; и &#171;<span class="param">adjust_noise_threshold</span>&#187; , поэтому, если планируете использовать маску, её нужно сделать и установить в первую очередь.</p>По умолчанию: <b>не установлено</b> - не использовать маску.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_CAM,
   'cats'    => '5.1.3',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'noise_threshold',
   'type'    => $INT_VAL,
   'def_val' => NULL,
   'desc'    => '<p><b>Порог шума</b> - допустимая разница между изменением яркости двух точек в одной позиции от последовательно полученных кадров, которая рассматривается как шум или помеха (лёгкое дрожание камеры, дождь, снег, электрический шум видеосигнала и т.п.).</p>
   Допустимые значения:
   <ul>
   <li><b>пусто или 0</b> - <b>автоматическая регулировка</b> самой программой, см. ниже параметр &#171;<span class="param">adjust_noise_threshold</span>&#187;;</li>
   <li><b>[10..50]</b> - <b>&quot;ручная&quot;</b> точная статическая настройка (совет: начните с 30 если не устраивает авто-регулировка).</li>
   </ul>
   Если автоматическая регулировка вас не устраивает, то при определении оптимального значения постарайтесь добиться 2 целей:
   <ul>
   <li>стабилизации diff<sup>*</sup> или %diff<sup>**</sup> на спокойных кадрах и при любой освещённости, например, c отклонением от среднего не более чем на 5%;</li>
   <li>существенного изменения diff<sup>*</sup> или %diff<sup>**</sup> на неспокойных кадрах (при движении) при самом медленном движении самого малого объекта в кадре, интересующего вас.</li>
   </ul>
   <p>Примечания:
   <br><sup>*</sup>&nbsp;&nbsp;&nbsp;diff = 1000 + количество пикселей, &quot;прошедших&quot; порог &#171;<span class="param">noise_threshold</span>&#187;;
<br><sup>**</sup>&nbsp;&nbsp;%diff - относительное изменение diff в процентах.
   <br>Оба значения отображаются в правом верхнем углу кадра при просмотре в локальном вьювере &#171;avreg-mon&#187; (при &quot;включенных&quot; &#171;<span class="param">text2img</span>&#187; и &#171;<span class="param">text_changes</span>&#187;) и могут выводиться в системый журнал syslog (при запуске avregd с ключом -v).
   </p>
   По умолчанию: <b>не заполнено, т.е. авто-регулировка</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1.3',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'adjust_noise_threshold',
   'type'    => $INT_VAL,
   'def_val' => 0,
   'desc'    => '<b>Дополнительная коррекция режима автоматической порога шума</b> (при пустом или нулевом значении &#171;<span class="param">noise_threshold</span>&#187;).
   <p>Допустимые значения: [-5 .. +5], 0 - с нулевой (или без) коррекции, положительные значения - увеличить порог (&quot;загрубить&quot;), отрицательные - уменьшить.</p>
   По умолчанию: <b>0</b> - с нулевой (или без) коррекции.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1.3',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'diff_pxls_threshold',
   'type'    => $INTPROC_VAL,
   'def_val' => '10%',
   'desc'    => '<b>Порог срабатывания детектора</b> - число &quot;изменившихся&quot; в новом кадре пикселей, вычисленное с учётом вышеописанных параметров, при котором срабатывает Программный Детектор Движения (ПДД). Такой кадр гарантированно будет сохранён на диске (если же конечно включен режим записи на диск).
   <p>Значение &#171;<span class="param">diff_pxls_threshold</span>&#187; должно определяться в зависимости от <b>мин. размера и мин. скорости отслеживаемых объектов</b>. Допустимо указывать как относительное изменение значения diff в процентах % (со знаком % в конце числа, например 15%), так и точное абсолютное значение порога (без знака %, например 2000). Значения 0% или 0 отключают детекцию.</p>
   По умолчанию: <b>10%</b>, т.е. в пределах %diff [-10%..+10%] - &quot;<i>в Багдаде всё спокойно</i>&quot;.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1.3',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'shake_filter',
   'type'    => $BOOL_VAL,
   'def_val' => NULL,
   'desc'    => '<b>Фильтрация</b> эффектов <b>быстрого кратковременного дрожания</b> в кадре. Особенно важен при аналоговом видеозахвате с <b>мультиплексируемых каналов</b> (когда к одному видеокодеру  BT878/SAA71xx/CX2388x подключено сразу несколько камер).
   <br>Допустимые значения:
   <ul>
   <li><b>пусто или не установлено</b> - программа принимает решение самостоятельно, включая фильтр только для мультиплексируемых аналоговых каналов (камер);</li>
   <li><b>Вкл.</b> или <b>Выкл.</b> - безусловное включение или отключение фильтра.</li>
   </ul>По умолчанию: <b>не установлено</b>, т.е. &#171;авто&#187;.',
   'flags'=>$F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1.3',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'motion_session_end',
   'type'    => $INT_VAL,
   'def_val' => 30,
   'desc'    => 'Минимальный интервал в сек. &quot;спокойствия&quot; детектора движения, после чего возникает <b>событие - &quot;ОКОНЧАНИЕ СЕССИИ ДВИЖЕНИЯ&quot;</b> и система переходит в состояние ожидания &quot;НАЧАЛА НОВОЙ СЕССИИ ДВИЖЕНИЯ&quot; в зоне наблюдения этой камеры. Примечание: по этому событию безусловно закрывается открытый  медиа-файл, независимо от пределов V.max_megabytes и V.max_minutes.<br><br>Допустимые значения от <b>10 до 600 сек</b>. По умолчанию: <b>30 сек</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1.3',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'text_left',
   'type'    => $STRING_VAL,
   'max_len' => 30,
   'def_val' => NULL,
   'desc'    => 'Текст в нижнем левом углу кадра. Также это и <b>название камеры</b> или зоны наблюдения.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_CAM,
   'cats'    => '5.1.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'text_right',
   'type'    => $STRING_VAL,
   'max_len' => 30,
   'def_val' => NULL,
   'desc'    => 'Шаблон для <b>временной отметка кадра</b> в правом нижнем углу кадра.
   <br><br>По умолчанию: <b>%Y-%m-%d\n%H:%M:%S-%t</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'text_changes',
   'type'    => $BOOL_VAL,
   'def_val' => 0,
   'desc'    => 'Текстовая строка в верхнем правом углу кадра, необходимая при <b>настройке параметров детектора движения</b>. Формат строки: &#171;<b>msg&nbsp;diff(%diff)/br.avg</b>&#187;, где:
   <ul>
   <li><b>msg</b> - флаг &quot;сработки&quot; детектора движения на этом кадре, &quot;ALRM&quot; (запись на диск отключена) или &quot;REC&quot; (кадр будет записан).</li>
   <li><b>diff</b> и <b>%diff</b> - см. описание параметра &#171;<span class="param">noise_threshold</span>&#187;.</li>
   <li><b>br.avg</b> - среднее значение яркости (при вкл. &#171;<span class="param">brightness_control</span>&#187;).</li>
   </ul>
   По умолчанию: <b>Выкл</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '5.1.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'recording',
   'type'    => $CHECK_VAL,
   'def_val' => 1,
   'desc'    => '<b>Режимы записи на жесткий диск</b>:
   <ul>
   <li>&#171;<b>Без записи</b>&#187; - запись на диски заблокирована;</li>
   <li>&#171;<b>Выборочный</b>&#187; (по-умолчанию) режим, при котором запись управляется событиями <i>любых</i> из следующих подсистем:</p>
   <ol>
   <li><i>детектор движения</i> (если включен и настроен);</li>
   <li><i>детектор звука</i> (пока не реализовано);</li>
   <li><i>внешние команды</i> (avregd HTTP CGI-интерфейс).</li>
   </li></ol>
   </li>
   <li>&#171;<b>Всё подряд</b>&#187; - &#171;сплошной&#187; (непрерывный, безусловный) режим записи, при котором <i>всегда и абсолютно все</i> захваченные с устройств видео-кадры и аудио-фреймы записываются на диск.</li>
   </ul>
   <p>По умолчанию: &#171;<b>Выборочный&#187.</p>',
   'flags'   => $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'paranoid_snap',
   'type'    => $INT_VAL,
   'def_val' => 0,
   'desc'    => '<b>Дополнительно</b>, абсолютно без каких-либо условий,
   записывать кадры-отметки <b>JPEG через каждые paranoid_snap минут</b>. По умолчанию: <b>0 - не записывать</b>.',
   'flags'=>$F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'V.save_fmt',
   'type'    => $CHECK_VAL,
   'def_val' => '',
   'desc'    => 'Формат файла/видеокодек</b> для записи видео на жесткий диск в архив.
   <ul>
   <li><b>Если значение параметра не установлено</b>, то программа <b>автоматически</b> самостоятельно выберет формат и видеокодек:
   <ul><li><b>&#171;avi/mpeg4</b>&#187; - при захвате с PCI-плат видеозахвата (аналоговые  видеокамеры);<li>
   <li><b>&#171;avi/mjpeg</b>&#187; - при захвате с MJPEG сетевых ip-камер и видеосерверов.</li></ul>
   <li>Если вы хотите явно установить формат/кодек файла для записи, то имейте ввиду, что для снижения нагрузки на CPU желательно выбирать или подбирать единый кодек для всех используемых модулей:<ul><li>видеозахвата с сетевых устройств (кроме плат в-захвата),</li><li>записи на диск,</li><li>&#171;раздачи&#187; видео сетевым клиентам.</li></ul>
   </li>
   <li>На невысоких скоростях видеозахвата (примерно до 7fps) и при записи по детектору движения, выигрыш от применения кодеков семейства mpeg4 неочевиден в сравнении с mjpeg.</li>
   <li><span style="color:Red;">Запись одиночными кадрами jpeg осуждается</span>, т.к. почти всегда приводит к замедлению работы файловой системы и, как следствие, системы в целом. Эта возможность будет исключена в следующих версиях.</li>
   </ul>
   По умолчанию: <b>не установлено - &#171;авто.&#187;</b>',
   'flags'   => $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'pre_record',
   'type'    => $INT_VAL,
   'def_val' => 3,
   'desc'    => '<b>Предзапись</b>. Будет также записано это количество &quot;спокойных&quot; кадров, захваченных перед КАЖДЫМ кадром, на котором сработал детектор движения. <b>Существенно увеличивает требование к объёму оперативной памяти</b>.<p>'.$strOnlySelDet.'</p>Допустимые значения: [0..125]. По умолчанию: <b>3 кадра</b>.',
   'flags'   => $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'post_record',
   'type'    => $INT_VAL,
   'def_val' => 3,
   'desc'    => '<b>Послезапись</b>.Будет также записано это количество &quot;спокойных&quot; кадров, захваченных после КАЖДОГО кадра, на котором сработал детектор движения.<p>'.$strOnlySelDet.'</p>Допустимые значения: [0..250]. По умолчанию: <b>3 кадра</b>.',
   'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'save_only_motion',
   'type'    => $BOOL_VAL,
   'def_val' => true,
   'desc'    => 'Какие видеокадры записывать <b>внутри каждой сессии движения</b>:
   <ul>
   <li>&laquo;Выкл.&raquo; - записывать любые кадры (все подряд, со скоростью захвата). Включение этого режима существенно увеличивает объём сохраняемого видео на диск.</li>
   <li>&laquo;Вкл.&raquo; (по-умолчанию) - записывать только кадры &quot;с движением&quot;.</li>
   </ul>
   <p>'.$strOnlySelDet.'</p>
   По умолчанию: &laquo;<b>Вкл.</b>&raquo;',
   'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'extra_snap_period1',
   'type'    => $INT_VAL,
   'def_val' => 0,
   'desc'    => '<em>Длительность в секундах 1-го спапшотного периода</em> с момента <i>начала выборочной записи</i> (например, с начала каждого сеанса движения), в котором (периоде) на диск со скоростью не более чем 1 fps (<em>1 кадр в секунду</em>) сохраняются кадры-отметки в формате jpeg, <em>дополнительно к записи в основной формат</em> (см. описание <em>V.save_fmt</em> выше). На диск записываются любые кадры,  как &quot;c движением&quot; так и &quot;спокойные&quot;, т.е. без учёта детектора движения.
   <p>'.$strOnlySelDet.'</p>Допустимые значения: [0..30] секунд (макс. 30 кадров).
   <br>По умолчанию: <b>0 - не записывать отметки в этом интервале</b>.',
   'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'extra_snap_period2',
   'type'    => $INT_VAL,
   'def_val' => 0,
   'desc'    => '<em>Длительность в секундах 2-го спапшотного периода</em> с момента окончания 1-го периода (см. <em>extra_snap_period1</em> выше), в котором (периоде) на диск со скоростью не более чем 0.2 fps (<em>1 кадр каждые 5 секунд</em>) сохраняются кадры-отметки в формате jpeg, <em>дополнительно к записи в основной формат</em> (см. описание <em>V.save_fmt</em> выше). На диск записываются любые кадры,  как &quot;c движением&quot; так и &quot;спокойные&quot;, т.е. без учёта детектора движения.
   <p>'.$strOnlySelDet.'<br>По умолчанию: <b>0 - не записывать отметки в этом интервале</b>.',
   'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'V.save_vtag',
   'type'    => $STRING_VAL,
   'max_len' => 4,
   'def_val' => NULL,
   'desc'    => '<b>FOURCC тег</b>.
   Например, установив fourcc тег как &#171;DIVX&#187; при записи в формате &#171;avi/mpeg4&#187;,
   записанные видеофайлы можно будет смотреть divx-кодеками и, возможно, на бытовых DVD-проигрывателях (примечание: по-умолчанию, для &#171;avi/mpeg4&#187; используется тег &#171;FMP4&#187;, который понимают только медиа-проигрыватели, использующие ffmpeg(*nix)/ffdshow(win).
   <br><br>По умолчанию: <b>не установлен</b>, т.е. будет установлен <b>автоматически</b>, библиотеками ffmpeg.',
   'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'V.save_qscale',
   'type'    => $INT_VAL,
   'def_val' => 5,
   'desc'    => '<b>Уровень сжатия</b> видеокадров или некий коэффициент, <b>обратный  качеству изображения</b>. Параметр соответствует ffmpeg-параметру qscale для режима VBR (переменный битрейт с целью удержания постоянного качества изображения для каждого кадра).<br><br>Прим.: при захвате с ip-камер в mjpeg и записи/трансляции в mjpeg программа не кодирует заново полученные видеокадры jpeg, а использует оригинальные jpeg-кадры c камер, т.е. уровень сжатия нужно задавать непосредственно на ip-камере.
   <br><br>Допустимые значения: <b>2</b>(лучшее качество и макс. размер файла) - <b>30</b>(хуже, но меньше).<br/>По умолчанию <b>5</b>(оптимально).',
   'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'V.film_view_fps',
   'type'    => $INT_VAL,
   'def_val' => 7,
   'desc'    => '<b>Нормальная скорость воспроизведения видеофильма в видеопроигрывателе при просмотре</b>  (т.е. специально  не ускоренная и не замедленная пользователем), в кадрах в секунду (<b>!!! не скорость записи</b>). Рекомендуется устанавливать равной или несколько более фактической скорости захвата с видеокамеры.<br><br>Допустимые значения: 1..30. По умолчанию: <b>7 кадров в секунду</b>.',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'V.max_megabytes',
   'type'    => $INT_VAL,
   'def_val' => 5,
   'desc'    => '<b>Максимальный размер видеофильма</b> в Мегабайтах.<br><br>По достижению любого, этого или V.max_minutes (см. ниже) пределов, запись продолжится в уже в новый файл. При включенном детекторе движения, событие &#171;окончание сессии движения&#187; закроет файл независимо от любых установленных пределов на размер и продолжительность.
   <br><br>Если Вы <b>хотите иметь огромные видеофильмы, например, в AVI-файлах</b> (по аналогии с DVD, VIDEOCD и т.п.), <b>подумайте сначала</b>, а удобно ли будет с ними работать в режиме доступа к видеоархиву по сети?<br><br>Допустимые значения: от 1 до 1000(1Гб). &nbsp;По умолчанию: <b>5 Mb</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'V.max_minutes',
   'type'    => $INT_VAL,
   'def_val' => 60,
   'desc'    => '<b>Максимальная продолжительность</b> видеофильма в <b>минутах</b>.<br><br>По достижению любого, этого или V.max_megabytes (см. выше) пределов, запись продолжится уже в новый файл.
   При включенном детекторе движения, событие &#171;окончание сессии движения&#187; закроет файл независимо от любых установленных пределов на размер и продолжительность.
   <br><br>Допустимые значения: от 1 до 1440(24 часа) &nbsp;По умолчанию: <b>60 минут</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'A.save_fmt',
   'type'    => $CHECK_VAL,
   'def_val' => NULL,
   'desc'    => '<b>Формат файла/аудиокодек</b> для записи аудиоданных на жесткий диск в архив.<br><br><b>Если значение параметра не установлено</b>, то программа <b>самостоятельно   выберет оптимальный формат</b> файла и аудиокодек:
   <ul><li><b>&#171;m4a/aac(без перекодирования)</b>&#187; - при захвате аудиопотока сжатого AAC (MPEG4 part3, обычно от &ldquo;продвинутых&rdquo; ip-камер типа AXIS);<li>
   <li><b>&#171;wav/без перекодирования</b>&#187; - при захвате PCM/ADPCM аудиопотоков от &ldquo;проcтых&rdquo; ip-камер.</li>
   </ul>
   Примечания:
   <ul>
   <li>любое перекодирование (несовподение аудиокодеков, входного и используемого при записи в файлы на диск) аудиоданных приведёт к некоторому ухудшению качества, а неоптимальный выбор аудиокодека может привести даже к увеличению размера записываемых аудиоданных над размером входного аудиопотока;</li>
   <li>на низких битрейтах размеры получаемых файлов у всех кодеков примерно одинаковы;</li>
   <li>скорость кодирования заметно выше, а нагрузка на CPU ниже, при кодировании кодеком MPEG audio layer 2 (mp2);</li>
   <li>скорость кодирования заметно ниже, а нагрузка на CPU выше, при кодировании кодеком AAC (MPEG4 part3).</li>
   </ul>
   По умолчанию: <b>не установлено - &#171;авто.&#187;</b>',
   'flags'=>$F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.2',
   'subcats' => NULL,
   'mstatus' => 1,
),


array(
   'name'    => 'A.max_megabytes',
   'type'    => $INT_VAL,
   'def_val' => 5,
   'desc'    => '<b>Максимальный размер</b> аудиофайла в <b>Мегабайтах</b>. По достижению этого предела будет создан новый файл.<br>Допустимые значения: от 1 до 1000(1Гб) &nbsp;По умолчанию: <b>5 Mb</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'A.max_minutes',
   'type'    => $INT_VAL,
   'def_val' => 10,
   'desc'    => '<b>Максимальная продолжительность</b> аудиофайла в <b>минутах</b>. По достижению этого предела будет создан новый файл.<br>Допустимые значения: от 1 до 1440(24 часа) &nbsp;По умолчанию: <b>60 минут</b>.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '11.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'forced_saving_limit',
   'type'    => $INT_VAL,
   'max_len' => 4,
   'def_val' => 5,
   'desc'    => '<b>Максимальная длительность принудительной записи (по команде) в минутах</b>.
   Параметр служит для предотвращения бесполезного расходования дискового пространства в случае, когда оператор, включив запись (командой), забывает отключать её.
   На уровне пользователей предусмотрен одноимённый параметр, действующий подобным образом.
   При определении значения ограничения принудительной записи, программа использует минимальное из двух значений параметров forced_saving_limit: "камерного" и пользователя, подавшего команду.
   <br /><br />
   Если же вы планируете использовать сеансовую принудительную запись как основной режим записи
   (например, <b>по расписанию или по событиям, датчикам</b>, и т.п.),
заведите специального пользователя в группе "Операторы", от которого будут подаваться команды на вкл./выкл. записи,
и <b>установите нулевое</b> (0 - не ограничивать) значение forced_saving_limit для камер(ы) и для этого пользователя.
<br /><br />Допустимые значения: 0 - не ограничивать или [1..4320] минут, по умолчанию: &quot;<b>5 минут</b>&quot;. ',
'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
'cats'    => '11',
'subcats' => NULL,
'mstatus' => 1,
),

/*
 * ONLINE ONLINE ONLINE ONLINE ONLINE ONLINE ONLINE ONLINE ONLINE
 */

array(
   'name'    => 'allow_local',
   'type'    => $BOOL_VAL,
   'def_val' => false,
   'desc'    => 'Разрешить <b>локальное</b> (на сервере) <b>наблюдение</b> в реальном времени за этой камерой в программе <b>avreg-mon</b>.<br>Прим.: для включения режима также необходимо определить параметры вложенного подраздела "локальное" в персональных (не групповых) настройках камеры.<br><br>По умолчанию: <b>Выкл</b>.',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '15',
   'subcats' => '15.1',
   'mstatus' => 2,
),

array(
   'name'    => 'allow_networks',
   'type'    => $BOOL_VAL,
   'def_val' => true,
   'desc'    => 'Разрешить <b>наблюдение по сети</b>.<br><br>По умолчанию: <b>Вкл</b>.',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '15',
   'subcats' => '15.2',
   'mstatus' => 2,
),

array(
   'name'    => 'v4l_pipe',
   'type'    => $CHECK_VAL,
   'def_val' => NULL,
   'desc'    => 'Создать <b>виртуальный видеоканал</b>, на который будет <b>транслироваться видео</b> с этой камеры для локального вьювера <b>avreg-mon</b>.<br><br><b>Выбирать файлы</b> каналов нужно из предложенного списка последовательно, <b>без совпадений</b> c другими камерами. Если список пуст или не хватает каналов см. <a href="http://avreg.net/manual_install_avreg-mon.html" target="_blank">инструкцию по установке</a>.<br><br>По умолчанию: <b>не задано</b> - значит <b>локальный просмотр</b> для данной камеры <b>невозможен</b>.',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_CAM,
   'cats'    => '15.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'v4l_pipe_maxrate',
   'type'    => $INT_VAL,
   'def_val' => 60,
   'desc'    => 'Ограничить этим значением (в кадрах в секунду) скорость видеопотока, отдаваемого в сквозной video4linux-канал (обычно для локального просмотра avreg-mon-ом)<br>Допустимые значения: [1..60]; по-умолчанию: 60 - не ограничивать.',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '15.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'v4l_pipe_nonmotion_maxrate',
   'type'    => $INT_VAL,
   'def_val' => 0,
   'desc'    => 'Ограничивает скорость потока "спокойных" кадров в отсутствии движения до установленного вами значения, естественно, только при включенном детекторе движения. <br>Допустимые значения: [1..60]; по-умолчанию: 60 - не ограничивать.',
   'flags'   => $F_RELOADED | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '15.1',
   'subcats' => NULL,
   'mstatus' => 1,
),

//'cats'    => '15.2' -> настройка сетевых камер
array(
   'name'    => 'wc_maxrate',
   'type'    => $INT_VAL,
   'def_val' => 60,
   'desc'    => $_rate_lim_info,
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '15.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

array(
   'name'    => 'wc_max_conn_per_cam',
   'type'    => $INT_VAL,
   'def_val' => 2,
   'desc'    => '<b>Максимальное кол-во</b> подключенных сетевых <b>клиентов</b>, одновременно просматривающих эту камеру в каждый момент времени.<br>Устанавливайте разумные значения с учётом реальных максимального количества пользователей и ресурсов сервера и сетевого оборудования. Иначе возможны перегрузка сервера и даже аварийный останов демона &#171;'.$conf['daemon-name'].'&#187;.<br><br>Допустимые значения от 1 до 1000. По умолчанию: <b>5</b>.',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '15.2',
   'subcats' => NULL,
   'mstatus' => 1,
),

//Алтернативные адреса веб-камер
array(
   'name'    => 'fs_url_alt_1',
   'type'    => $STRING_URL_VAL,
   'def_val' => "",
   'desc'    => '<b>Алтернативный источник № 1 для полноэкранного отображения <br /> </b>  ',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '15.2',
   'subcats' => NULL,
   'mstatus' => 1,
),


array(
   'name'    => 'cell_url_alt_1',
   'type'    => $STRING_URL_VAL,
   'def_val' => "",
   'desc'    => '<b>Алтернативный источник № 1 для отображения в ячейке раскладки <br /> </b>  ',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '15.2',
   'subcats' => NULL,
   'mstatus' => 1,
),


array(
   'name'    => 'fs_url_alt_2',
   'type'    => $STRING_URL_VAL,
   'def_val' => "",
   'desc'    => '<b>Алтернативный источник № 2 для полноэкранного отображения <br /> </b>  ',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '15.2',
   'subcats' => NULL,
   'mstatus' => 1,
),


array(
   'name'    => 'cell_url_alt_2',
   'type'    => $STRING_URL_VAL,
   'def_val' => "",
   'desc'    => '<b>Алтернативный источник № 2 для отображения в ячейке раскладки <br /> </b>  ',
   'flags'   => $F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM ,
   'cats'    => '15.2',
   'subcats' => NULL,
   'mstatus' => 1,
),






/* EVENTS */
array(
   'name'    => 'events2pipe',
   'type'    => $BOOL_VAL,
   'def_val' => 0,
   'desc'    => '<b>Сообщать вашему &#171;<span class="param">event-collector</span>&#187; скрипту о событиях на этой камере</b>.
   <p>
   Под скриптом понимается любое исполняемое приложение, которое может быть написано вами на любом языке программирования (shell, python, perl, ruby, php, java и даже на С).
   </p>
   <p>
   Кроме установки параметра &#171;<span class="param">events2pipe</span>&#187; в &#171;Вкл.&#187;, вы должны поместить свой скрипт в каталог &#171;<span class="path">/etc/avreg/scripts/</span>&#187; и прописать полный путь до него в конфигурационном файле &#171;<span class="path">avreg.conf</span>&#187; в параметре &#171;<span class="param">event-collector</span>&#187;.
   </p>
   Поддерживаются такие события, как:
   <ul>
   <li>запуск/остановка/сбой аудио/видео захвата с устройства;</li>
   <li>подключение/отключение сетевых клиентов;</li>
   <li>запись файлов на диск;</li>
   <li>изменение качества захватываемого изображения (засветка/затемнение, при вкл. контроле яркости);</li>
   <li>начало/окончание сессии движения (при вкл. детекторе движения).</li>
   </ul>
   <p>
   Полный перечень событий, способ их получения (чтения), а также передаваемые параметры,
   смотрите в нашем примере скрипта в каталоге &#171;<span class="path">/usr/share/doc/avregd/examples/scripts/</span>&#187;.
   </p>
   <div>
   Дополнительно, ознакомьтесь с:
   <ul>
   <li><span class="cmd">man avregd</span>,</li>
   <li>инструкцией по написанию скриптов на сайте проекта.</li>
   </ul>
   </div>
   По умолчанию: <b>Выкл.</b> - не сообщать о событиях на этой камере.',
   'flags'=>$F_RELOADED | $F_BASEPAR | $F_IN_DEF | $F_IN_CAM,
   'cats'    => '20',
   'subcats' => NULL,
   'mstatus' => 1,
),


);

$PARAMS_NR=count($PARAMS);

?>
