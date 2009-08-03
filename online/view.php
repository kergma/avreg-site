<?php

if (!isset($_POST) || !isset($_POST['mon_type']) || !isset($_POST['cams']) || !is_array($_POST['cams']))
  die('you need start from /online/index.php');

$ccnt=count($_POST['cams']);
$ccnr_a=0;
for ($i=0;$i<$ccnt;$i++) 
   if (!empty($_POST['cams'][$i]))
      $ccnr_a++;
if ($ccnr_a===0) 
   die('not defined cams for view');

require ('../admin/mon-type.inc.php');
$mon_type=$_POST['mon_type'];
if (!isset($mon_type) || empty($mon_type) || !array_key_exists($mon_type, $layouts_defs) ) 
   MYDIE("not set ot invalid \$mon_type=\"$mon_type\"",__FILE__,__LINE__);
$l_defs = &$layouts_defs[$mon_type];
$wins_nr = $l_defs[0];

$сb = 'avreg_' . $mon_type . '_OpenInBlankPage'; 
$cnm  = 'avreg_' . $mon_type . '_PrintCamNames';
$ercnt = 'avreg_' . $mon_type . '_EnableReconnect';
$car = 'avreg_' . $mon_type . '_AspectRatio';
$expired = time()+5184000;
$ca=dirname($_SERVER['SCRIPT_NAME']).'/build_mon.php';
if (isset($_POST['OpenInBlankPage']))
   setcookie($сb,  '1', $expired,$ca);
else
   setcookie($сb,  '0', $expired,$ca);
if (isset($_POST['PrintCamNames']))
   setcookie($cnm,  '1', $expired,$ca);
else
   setcookie($cnm,  '0', $expired,$ca);
if (isset($_POST['EnableReconnect']))
   setcookie($ercnt,  '1', $expired,$ca);
else
   setcookie($ercnt,  '0', $expired,$ca);
if (isset($_POST['AspectRatio']))
   setcookie($car,  $_POST['AspectRatio'], $expired,$ca);

for ($i=0;$i<$wins_nr;$i++) 
  if (isset($_POST['cams'][$i]))
     setcookie('avreg_' . $mon_type.'_cams['.$i.']',$_POST['cams'][$i],$expired,$ca);


$pageTitle = 'WebCam';
$body_style='overflow: hidden;  overflow-y: hidden !important; padding: 0; margin: 0; width: 100%; height: 100%;';
$css_links=array('lib/js/jqModal.css');
$link_javascripts=array('lib/js/jquery-1.2.6.min.js', 'lib/js/jqModal.js');
$include_javascripts=array('online/view.js.php', 'online/view.js');
$body_addons='scroll="no"';
$ie6_quirks_mode = true;
$lang_file='_online.php';
require ('../head.inc.php');
if ( !isset($cams) || !is_array($cams)) 
   MYDIE('not set cams',__FILE__,__LINE__);

/*
print '<pre>';
var_dump($cams);
var_dump($camnames);
print '</pre>'."\n";
die();
 */
?>

<div id="toolbar" style="position:absolute; height:25px; width:100%; margin:0; padding:0; background-color:#003366; overflow:hidden; color:#E0E0E0;" >
<table width="100%" cellspacing="0" border="0" cellpadding="0">
<tr style="height: 25px; overflow: hidden;">
<td width="150px">
<a href="<?echo $conf['prefix']; ?>/online/index.php" title='Назад, к выбору камер.'>
<img src="<?echo $conf['prefix']; ?>/img/dvrlogo-134x25.png" width="134" height="25" border="0">
</a>
</td>
<td width="90%" style="color: #E0E0E0; text-align: right; height: 25px; overflow: hidden;">
<script type="text/javascript">br_spec_out();</script>
&nbsp;&nbsp;Нет изображений с камер?
</td>
<td style="color: #E0E0E0; text-align: right;" nowrap="true">
&nbsp;Читаем&nbsp;
<a title="HELP" class="jqModal" href="#" style="cursor: pointer; color:#FFA500; font-weight:bold;">справку!</a>&nbsp;
</td>
</tr>
</table>
</div>
<div id="canvas" style="position:absolute; -ms-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; top:25px; background-color:#000; width:100%; height:0px; overflow:hidden; margin:0; padding:0;"></div>
<div class="jqmWindow" id="dialog">
<div style="text-align: right;">
<span class="jqmClose" style="text-align: center; border: 1px solid #000; font-weight: bold; padding: 5px;"><a href="#">X</a></span>
</div>
<hr>
<p>Если вы <b>не видите изображения от видеокамер</b>,</br>то возможно:</p>
<ul>
<li>нет или остановлен видеозахват с этой камеры (попросите администратора проверить лог-файлы);</li>
<li>демон avregd не работает;</li>
<li>другие пользователи сейчас смотрят камеры и сработало ограничение по количеству одновременных просмотров (параметр wc_limit);</li>
<li>в другом окне браузера на вашем компьютере уже запущен просмотр камер;</li>
<li>камера не настроена должным образом для просмотра по сети;</li>
<li>вам не разрешено смотреть эту(и) камеру(ы);</li>
<li>проблемы в настройках интернет-браузера:
<ul>
<li>Firefox: в настройках браузера отключена опция &quot;загружать изображения&quot;.</li>
<li>Internet Explorer: настройки браузера не позволяют загружать и выполнять компоненты ActiveX. Cпросите у вашего системного администратора или у нас.;</li>
<li>вы НЕ пользуетесь браузерами Microsoft Internet Explorer, Firefox, Mozilla, Netscape.</li>
</ul>
<li>настройки сетевого экрана firewall на вашем компьютере блокируют запросы к камерам;</li>
<li><i>возможно просто нужно перезапустить браузер или обновить страницу;</i></li>
<li>ещё какая-нибудь причина которую мы пока не знаем :-)</li>
</ul>
<hr>
<div style="text-align: right;"><a href="#" class="jqmClose" >Закрыть</a></div>
</div>
<?php
if ( !empty($msie_addons_scripts) || is_array($msie_addons_scripts) )  {
    foreach ($msie_addons_scripts as $value)
        print "$value\n";
}

require ('../foot.inc.php');
?>
