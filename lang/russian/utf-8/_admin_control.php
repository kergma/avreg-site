<?php

/* CONTROL START */
$r_control = 'Управление основной программой сервера - демоном &#171;'.$conf['daemon-name'].'&#187;';
$r_conrol_control = 'Подать команду демону';


$strRun = 'запустить';
$strStop = 'остановить';
$strRestart = 'полный перезапуск, перечитываются ВСЕ параметры';
$strCondRestart = 'Condrestart (перечитываются настройки)';
$strSnapshot = 'записать по одному кадру Jpeg с каждой подключенной видеокамеры';
$strReload = 'перечитать конфигурацию. Перечитываются ТОЛЬКО НЕКОТОРЫЕ параметры, отмеченные ';

$strRunW = 'запустить';
$strStopW = 'остановить';
$strRestartW = 'перезапустить';
$strCondRestartW = 'condrestart';
$strReloadW = 'Перечитать измененные <img src="'.$conf['prefix'].'/img/hotsync.gif" align="middle" border="0" height="22" width="22"> настройки?';
$strSnapshotW = 'Записать по кадру Jpeg с каждой камеры ( snapshot/отметки )?';
$mon_exit = 'Внимание! Закройте все работающие программы просмотра видео - локальный МОНИТОР и браузеры!';

$runVservWarn1='Команда &quot;%s&quot; не допустима для уже работающего '.$conf['daemon-name'];
$runVservWarn2='Команда &quot;%s&quot; не допустима для неработающего '.$conf['daemon-name'];

$fnmWarnControl = 'Вы уверены, что хотите %s сервер?';
$strRunA = 'Запускается сервер. Ждите...';
$strStopA = 'Сервер останавливается. Ждите...';
$strRestartA = 'Сервер перезапускается. Ждите...';
$strCondRestartA = 'condrestart. Ждите...';
$strReloadA = 'Сервер перечитывает настройки и обновляет конфигурацию. Ждите...';
$strSnapshotA = 'Записываются кадры. Ждите...';
$strCheckLog = 'Ошибки и предупреждения смотрите в части лога ниже.';

/* CONTROL END */

?>