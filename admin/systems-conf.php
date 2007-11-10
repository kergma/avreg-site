<?php
require ('../head.inc.php');

$r_system='Системные настройки видеосервера &quot;%s&quot; [%s]';

$attention='<div class="warn">'.
'<ul>'.
'<li>Основные способы настройки системы описаны в стиле HOWTO (практического руководства - как сделать).</li>'.
'<li>Все действия, описанные ниже, нужно выполнять находясь в системе как суперпользователь <span class="HiLite">root</span>.</li>'.
'<li>Войти как root можно двумя способами:'.
'<ol>'.
'<li>локально в виртуальной консоли 3 (ALT-F3);</li>'.
'<li>по сети с помощью <span class="HiLite">telnet</span>. При этом Вы сначала входите как обычный пользователь ftptelnet и затем набираете команду <span class="cmd">su -l</span> и вводите пароль root-a;</li>'.
'</ol>'.
'<li>Процесс настройки тех или иных системных параметров или модулей осуществляется посредством:'.
'<ol>'.
'<li>Редактирования текстовых конфигурационных файлов (в некоторых случаях) в каталогах <span class="path">/etc</span> и <span class="path">/usr/local/etc</span>. Для этого можно воспользоваться встроенным редактором консольного менеджера MC (типа Far-а, запуск команда <span class="cmd">mc</span>) или редактором VI (для опытных пользователей, запуск команда <span class="cmd">vi</span>). Если в MC через telnet не работают функциональные клавиши F1-F5, то попробуйте сочетания: ESC-цифра.</li>'.
'<li>Выполнения команд (это могут быть штатные linux команды или написанные нами мастера настройки);</li>'.
'</ol>'.
'</li>'.
'</ul>'.
'</div>';

$dvrconf_h='изменить параметры в основном конфигурационном файле';
$date_h='установить системное время и настроить синхронизацию времени';
$network_h='измененить сетевые параметры протокола TCP/IP (IP,MASK,GATE...)';
$users_h='сменить пароли системных пользователей ftptelnet и root';
$v4l_h='настроить параметры video4linux драйверов PCI устройств видеозахвата';
$boot_h='установить загрузку с жесткого диска или USB-флэш диска';
$sendmail_h='настроить параметры для отправки EMAIL по SMTP';
$user_scripts_h='добавить в систему свои скрипты и дополнительное программное обеспечение';
$shutdown_h='перегрузить/остановить компьютер';
$rsync_h='настроить резервное копирование (backup) видеоархива на другую машину';
$cron_h='управлять выполнением заданий по расписанию';

echo '<h1>' . sprintf($r_system, $named, $sip) . '</h1>' ."\n";
echo $attention;

?>

<h2>Как сделать или настроить следующее:</h2>
<ul>
<li><a href="#dvrconf">dvr.conf</a> - <?php echo $dvrconf_h; ?>;</li>
<li><a href="#date">date</a> - <?php echo $date_h; ?>;</li>
<li><a href="#network">network</a> - <?php echo $network_h; ?>;</li>
<li><a href="#users">system users</a> - <?php echo $users_h; ?>;</li>
<li><a href="#v4l">capture card</a> - <?php echo $v4l_h; ?>;</li>
<li><a href="#boot">setup on HDD/USB</a> - <?php echo $boot_h; ?>;</li>
<li><a href="#rsync">rsync</a> - <?php echo $rsync_h; ?>;</li>
<li><a href="#sendmail">sendmail</a> - <?php echo $sendmail_h; ?>;</li>
<li><a href="#user_scripts">user_scripts</a> - <?php echo $user_scripts_h; ?>;</li>
<li><a href="#cron">cron</a> - <?php echo $cron_h; ?>;</li>
<li><a href="#shutdown">reboot/poweroff</a> - <?php echo $shutdown_h; ?>;</li>
</ul>

<hr size="1" noshade>

<h2><a name="dvrconf"></a><span class="HiLiteBig">dvr.conf</span> - <?php echo $dvrconf_h; ?></h2>

<p align="justify">
Основной конфигурационный файл <span class="path">/mnt/LinuxDVR/dvr.conf</span>
</p>

<textarea cols="90" rows="15" readonly class="listing">
<?php @readfile(/*'/mnt/LinuxDVR/dvr.conf'*/ '/etc/passwd'); ?>
</textarea>

<hr size="1" noshade>

<h2><a name="date"></a><span class="HiLiteBig">Date</span> - <?php echo $date_h; ?></h2>

<div class="HiLiteErr">
<img src="'.$conf['prefix'].'/img/warning-32.gif" width="32" height="32" align="middle" border="0">
Никогда не меняйте дату при работающем центральном модуле videoserv.
</div>
<br>
<p align="justify">
Команда для просмотра текущей даты и времени: <span class="cmd">date</span> (<a href="<?php  echo $conf['prefix']; ?>'/admin/exec.php?cmd=date" target="_blank">пример вывода</a>)
</p>


<p align="justify">
Команда для установки текущей даты и времени: <span class="cmd">date [MMDDhhmm[[CC]YY][.ss]]</span>
</p>

<p align="justify">
Настройка временной зоны и автоматической синхронизации времени: <span class="cmd">setup-clock.sh</span>
<br><br>
Конфигурационный файл <span class="path">/mnt/LinuxDVR/clock.conf</span>
</p>
<textarea cols="50" rows="7" readonly  class="listing">
<?php @readfile('/mnt/LinuxDVR/clock.conf'); ?>
</textarea>

<div align="center"><a href="#top"><?php echo $strUp; ?></a></div>
<hr size="1" noshade>

<h2><a name="network"></a><span class="HiLiteBig">Network</span> - <?php echo $network_h; ?></h2>

<div class="HiLiteErr">
<img src="'.$conf['prefix'].'/img/warning-32.gif" width="32" height="32" align="middle" border="0">
Конфигурация в базе данных привязана к IP адресу хоста, поэтому для изменения сетевых настроек нужно пользоваться командой <span class="cmd">chip.sh</span>.
</div>
<br>
<p align="justify">
Команда на изменение сетевых настроек TCP/IP: <span class="cmd">chip.sh</span>
<br><br>
Конфигурационный файл <span class="path">/mnt/LinuxDVR/net.conf</span>
</p>
<textarea cols="90" rows="15" readonly  class="listing">
<?php @readfile('/mnt/LinuxDVR/net.conf'); ?>
</textarea>

<p align="justify">
Команда для просмотра активных сетевых устройств: <span class="cmd">/sbin/ifconfig</span> (<a href="<?php  echo $conf['prefix']; ?>'/admin/exec.php?cmd=/sbin/ifconfig" target="_blank">пример вывода</a>)
</p>

<p align="justify">
Команда для просмотра соединений TCP: <span class="cmd">netstat -atnp</span> (<a href="<?php  echo $conf['prefix']; ?>'/admin/exec.php?cmd=netstat -atn" target="_blank">пример вывода</a>)
</p>

<div align="center"><a href="#top"><?php echo $strUp; ?></a></div>
<hr size="1" noshade>


<h2><a name="users"></a><span class="HiLiteBig">System users</span> - <?php echo $users_h; ?></h2>
<div class="HiLiteErr">
<img src="'.$conf['prefix'].'/img/warning-32.gif" width="32" height="32" align="middle" border="0">
Если забудете пароль суперпользователя root, то придется переустанавливать систему заново.
</div>

<p>
Пользователь <span class="HiLite">root</span> - главный пользователь в системе.
<br>
Пользователь <span class="HiLite">ftptelnet</span> используется для доступа по протоколам <span class="HiLite">telnet и ftp</span>.
</p>

<p>
Для изменения паролей используйте:
</p>
<ol>
<li>мастер - команда <span class="cmd">luser-cfg</span>;</li>
<li>команда <span class="cmd">passwd &lt;имя пользователя&gt;</span>;</li>
</ol>

<div align="center"><a href="#top"><?php echo $strUp; ?></a></div>
<hr size="1" noshade>


<h2><a name="v4l"></a><span class="HiLiteBig">Capture card</span> - <?php echo $v4l_h; ?></h2>
<p>
С платами видеозахвата работают драйвера из семейства video4linux.
</p>
<p>
Иногда необходимо подстройка драйверов, для чего используется мастер - команда  <span class="cmd">tuner-cfg.sh</span>
<br><br>
Конфигурационный файл <span class="path">/mnt/LinuxDVR/v4l.conf</span>
</p>
<textarea cols="90" rows="8" readonly  class="listing">
<?php @readfile('/mnt/LinuxDVR/v4l.conf'); ?>
</textarea>

<p>
Каждый видеодекодер (BT878 или CX2388x) отображен в системе в виде файла <span class="path">/dev/video[N]</span>.
<br><br>
Посмотреть его настройки можно командой <span class="cmd">v4l-info /dev/video[N]</span>
</p>

<div align="center"><a href="#top"><?php echo $strUp; ?></a></div>
<hr size="1" noshade>


<h2><a name="boot"></a><span class="HiLiteBig">Setup on HDD/USB</span> - <?php echo $boot_h; ?></h2>
<p>
Нужно загрузиться с LiveCD через меню [ ROOR SHELL/SULOGIN] 
и запускаем мастер установки загрузчика командой <span class="cmd">install-boot</span>
</p>

Используемый загрузчик - <span class="HiLite">GRUB</span>.<br><br>
HDD должен быть первым диском (C или /dev/[hs]da) или возможно потребуется дополнительная настройка GRUB.


<div align="center"><a href="#top"><?php echo $strUp; ?></a></div>

<hr size="1" noshade>
<h2><a name="rsync"></a><span class="HiLiteBig">Rsync</span> - <?php echo $rsync_h; ?></h2>

<p align="justify">В системе установлена распространенная программа для резервного копирования <span class="path">rsync</span> ( <a href="http://rsync.samba.org" target="_blank">http://rsync.samba.org</a> )</p>

<p>C неё помощью можно осуществлять резервное копирование на другой компьютер (возможно на Win).</p>

<p>Сама программа находится в каталоге <span class="path">/usr/local/sbin</span>.<br>
При загрузке системы <span class="path">rsync</span> запускается в режиме сервера (через <span class="path">xinetd</span>) и слушает запросы на порту TCP 873.<br>
Для настройки используются конфигурационный файл <span class="path">/etc/rsyncd.conf</span>.
</p>

<p>
Примеры использования (запуск с архивного сервера или Вашего компьютера):
</p>
<ul>
<li><span class="cmd">rsync -vhanW rsync://192.168.0.225/imgs MY_IMG_BACKUP_DIR</span> - просмотреть список новых файлов на видеосервере 192.168.0.225 не скачивая их;</li>
<li><span class="cmd">rsync -haW rsync://192.168.0.225/imgs MY_IMG_BACKUP_DIR</span> - синхронизировать каталоги: imgs(видеоархив) и  MY_IMG_BACKDIR(локальный);</li>
</ul>

<p align="justify">Информацию о использовании rsynс легко найти на сайте <a href="http://rsync.samba.org" target="_blank">http://rsync.samba.org</a> и на других ресурсах в сети интернет (в том числе и статьи на русском языке) или в документации MAN <span class="cmd">man rsync</span> и <span class="cmd">man 5 rsyncd.conf</span>.</p>

<div align="center"><a href="#top"><?php echo $strUp; ?></a></div>


<hr size="1" noshade>
<h2><a name="sendmail"></a><span class="HiLiteBig">Sendmail</span> - <?php echo $sendmail_h; ?></h2>

<p>
Необходимо отредактировать конфигурационный файл <span class="path">/etc/msmtprc</span>
</p>
<textarea cols="90" rows="8" readonly  class="listing">
<?php @readfile('/etc/msmtprc'); ?>
</textarea>

<p>
Для проверки используйте команду <span class="cmd">echo &quot;test&quot; | sendmail &lt;кому&gt;</span>
</p>

<div align="center"><a href="#top"><?php echo $strUp; ?></a></div>
<hr size="1" noshade>

<h2><a name="user_scripts"></a><span class="HiLiteBig">user scripts</span> - <?php echo $user_scripts_h; ?></h2>

<p align="justify">Ниже будет описан механизм внедрения в дистрибутив LinuxDVR дополнительного программного обеспечения.</p>

<p>В качестве дополнительного программного обеспечения могут выступать:</p>

<ol>
<li><span class="HiLite">исполняемые скрипты</span> (shell scripts) или программы (bin exec) - устанавливаются в <span class="path">/usr/local/sbin</span>;</li>
<li>целые <span class="HiLite">пакеты</span> или модули - устанавливаются в <span class="path">/usr/local</span></li>
</ol>

<p>Последовательность загрузки дистрибутива:</p>
<ol>
<li>...</li>
<li><span class="path">/etc/rc.d/rc.S</span> - первая фаза загрузки;</li>
<li><span class="path">/usr/local/sbin/rc.dvr</span> - вторая фаза фаза загрузки (это скрипт из штатного модуля sbin-xx.i386.tgz);</li>
</ol>

<p align="justify">Основная часть инициализации устройств и запуска программ находится в <span class="path">/usr/local/sbin/rc.dvr</span>. Этот же скрипт устанавливает и запускает пользовательское ПО. Происходит это в следующей последовательности:</p>

<ol>
<li>раздел с видеоархивом подключается в режиме RW к mount point <span class="path">/mnt/LinuxDVR</span>;</li>
<li>подгружаются пользовательские скрипты и пакеты из каталога <span class="path">/mnt/LinuxDVR/user-addons</span>;</li>
<li>вызывается пользовательский <span class="path">/usr/local/sbin/before-eth-up.sh</span>;</li>
<li>&quot;поднимаются&quot; сетевые интерфейсы Ethernet;</li>
<li>вызывается пользовательский <span class="path">/usr/local/sbin/after-eth-up.sh</span>;</li>
<li>запускаются основные демоны(сервисы)</span>;</li>
<li>вызывается пользовательский <span class="path">/usr/local/sbin/on-startup.sh</span>;</li>
</ol>

<h3>Установка скриптов</h3>
<ol>
<li>Ваши скрипты должны быть скопированы по ftp в каталог <span class="path">/mnt/LinuxDVR/user-addons</span>;</li>
<li>их имена не должны перекрываться с именами уже имеющихся скриптов в каталоге <span class="path">/usr/local/sbin</span> (из штатного модуля sbin-xx.i386.tgz) либо быть <span class="path">before-eth-up.sh</span>, <span class="path">after-eth-up.sh</span>, <span class="path">on-startup.sh</span>;</li>
<li>Права доступа должны быть 0750 <span class="cmd">chmod 0750 /mnt/LinuxDVR/user-addons/&lt;имя скрипта&gt;</span></li>
<li>Владелец/группа должны быть root:root <span class="cmd">chown root:root /mnt/LinuxDVR/user-addons/&lt;имя скрипта&gt;</span></li>
</ol>

<p>
Если соблюдены все условия выше, rc.dvr копирует скрипты в каталог <span class="path">/usr/local/sbin</span> и (если присутствуют) запускает <span class="path">before-eth-up.sh</span>, <span class="path">after-eth-up.sh</span>, <span class="path">on-startup.sh</span>.</p>


<h3>Установка пакетов/модулей</h3>
<ol>
<li>Пакеты должны быть упакованы в TGZ архивы <span class="cmd">tar czvf &lt;имя_пакета.tgz&gt; &lt;имя каталога с пакетом&gt;</span>. Причем без начального префикса /usr/local</li>
<li>Они должны быть скопированы по FTP в каталог <span class="path">/mnt/LinuxDVR/user-addons</span>. Если его нет, то нужно создать.</li>
<li>Права доступа должны быть 0640 <span class="cmd">chmod 0640 /mnt/LinuxDVR/user-addons/&lt;имя  пакета&gt;</span></li>
<li>Владелец/группа должны быть root:root <span class="cmd">chown root:root /mnt/LinuxDVR/user-addons/&lt;имя пакета&gt;</span></li>
</ol>

<p align="justify">
Если соблюдены все условия выше, rc.dvr распаковывает пакеты в каталог <span class="path">/usr/local</span> командой <span class="cmd">tar xzf &lt;имя  пакета&gt; -C /tmp/local
</span>.</p>


<p align="justify">
<img src="'.$conf['prefix'].'/img/warning-32.gif" width="32" height="32" align="middle" border="0">
Важно также знать что корневая файловая система в рабочем состоянии находиться в памяти ОЗУ (за исключением каталога /var/log, он монтируется на /mnt/LinuxDVR/log). Каталоги c конфигурационными файлами <span class="path">/etc</span> и <span class="path">/usr/local/etc</span> сохраняются на разделе с архивом и восстанавливаются при кажой загрузке, так что изменения не будет потеряны.
</p>

<p>Если затрудняетесь, спросите нас. По мере возможности мы сами будем готовить некоторые наиболее популярные пакеты.</p>


<div align="center"><a href="#top"><?php echo $strUp; ?></a></div>

<hr size="1" noshade>
<h2><a name="cron"></a><span class="HiLiteBig">Cron</span> - <?php echo $cron_h; ?></h2>

<p>В качестве планировщика задач используется стандартный <span class="path">crond</span></p>

<p>Его основные конфигурационные файлы:</p>
<ul>
<li><span class="path">/etc/crontab</span> - главный конфигурационный файл;</li>
<li><span class="path">/etc/cron.d/</span> - каждый файл в этом каталоге представляет собой одно задание;</li>
</ul>

<p>
Формат файлов смотрите в дoкументации MAN <span class="cmd">man 5 crontab</span> или в статьях в интернете. Например на <a href="http://ru.gentoo-wiki.com/Crontab" target="_blank">http://ru.gentoo-wiki.com/Crontab</a>
</p>

<div align="center"><a href="#top"><?php echo $strUp; ?></a></div>

<hr size="1" noshade>
<h2><a name="shutdown"></a><span class="HiLiteBig">Reboot/poweroff</span> - <?php echo $shutdown_h; ?></h2>
<ul>
<li><span class="cmd">reboot</span> - перегрузить</li>
<li><span class="cmd">poweroff</span> - выключить (или кнопку POWER нажать на системном блоке).</li>
</ul>

<div align="center"><a href="#top"><?php echo $strUp; ?></a></div>

<?php
require ('../foot.inc.php');
?>
