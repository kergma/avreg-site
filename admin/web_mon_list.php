<?php
/**
 * @file admin/web_mon_list.php
 * @brief Список определённых раскладок для web
 */

/**
*
* Функция выводит информацию о раскладке монитора в таблицу создания раскладок
* @param string $display правый или левый монитор
* @param int $l_nr Номер монитора
* @param array $l_def Настройки монитора
* @param bool $is_admin Админ
*/
function prt_l ($display, $l_nr, $l_def, $is_admin, $layout_word, $counter, $AspectRatio, $PrintCamNames, $isDefault)
{
	if($PrintCamNames==1 || $PrintCamNames=="t") $PCN = true;
	else $PCN=false;

	print "<div>\n";
	print "<span style=\"font-size:20px; font-weight:bold\"> $layout_word №".$counter."</span>\n<br />\n<span style=\"font-size:16px; font-weight:bold\">";
	if ( !empty($l_def['layout_name']))
	print $l_def['layout_name']."</span>\n";
	else
	print $l_def['layout_type']."</span>\n";
	 
	if ( !empty($l_def['CHANGE_TIME']) )
	print '<br>'.$l_def['CHANGE_USER'] . '@' .$l_def['CHANGE_HOST'] . '<br>' . $l_def['CHANGE_TIME'];

	require('../lang/russian/utf-8/_online.php');
	//сохранять пропорции/ на весь экран ?
	print '<br /><div style="float:left;" ><span style="font-weight:bold;">'.$strAspectRatio.":</span>&nbsp;".$AspectRatioArray[$AspectRatio]."</div>\n";
	//Выводить имена камер ?
	print '<br /><div style="float:left;" ><span style="font-weight:bold;">'.$strPrintCamNames.":</span>&nbsp;".($PCN?$strCamName_Yes:$strCamName_No)."</div>\n";

	//Установить по умолчанию
	$strSetByDefault = "Установить по умолчанию";
	print '<br /><div style="float:left;" ><span style="font-weight:bold;">'.$strSetByDefault.":</span>";
	print "<input type=\"radio\" name=\"ByDefault\" ".($isDefault?'checked="checked"':'')."\" onchange=\"SetByDefault($l_nr)\" /></div>\n";
	
	print '</div> <br /><br />' . "\n";
}

//Генерация страницы

$USE_JQUERY=true;
require ('../head.inc.php');
require ('./mon-type.inc.php');


//JS-для установки раскладки по умолчанию
print '<script type="text/javascript">';

print 'function SetByDefault(layoutNum){';

print '$.ajax({"url":"web_set_def.php?layout="+layoutNum+" "})';
print '.done(function(data){ ';
print 'if(data!=\'NULL\'){ ';
print '$("<div style=\"position:absolute; top:300px; left:300px; z-index:100; color:Yellow; background-color:DarkRed; border:3px solid black; cursor:default; \">';
print '<div style=\"font-weight:bold; color:Yellow; border:2px solid black; padding:2px; float:right;\">X</div>';
print '<span style=\" font-size:14pt;  \">ERROR</span><br />';
print '"+data+"</div>")';
print '.appendTo("body").click(function(){ $(this).remove(); }) ;  } ';

print '});';
print '}';

print '</script>';




//Заголовок окна ($named,$sip - название и IP сервера; $r_mons - в lang/russian/utf-8/common.inc.php)
echo '<h1>' . sprintf($web_r_mons,$named,$sip) . '</h1>' ."\n";

//Пользуемся только одним левым монитором
$display ='L';

$counter = $GLOBALS['counter'];
//загружается если нажато удаление раскладки
if ( isset($cmd) )
{
	
   DENY($admin_status);
   switch ( $cmd )	{
   case 'DEL':
      echo '<p class="HiLiteBigWarn">' . sprintf ($fmtDeleteMonConfirm, $counter, $mon_name, $display=='R'?$sRightDisplay1:$sLeftDisplay1) . '</p>' ."\n";
      print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
      print '<input type="hidden" name="cmd" value="DEL_OK">'."\n";
      print '<input type="hidden" name="display" value="'.$display.'">'."\n";
      print '<input type="hidden" name="mon_nr" value="'.$mon_nr.'">'."\n";
      print '<input type="hidden" name="counter" value="'.$counter.'">'."\n";
      print '<input type="hidden" name="mon_name" value="'.$mon_name.'">'."\n";
      print '<input type="submit" name="mult_btn" value="'.$strYes.'">'."\n";
      print '<input type="submit" name="mult_btn" value="'.$strNo.'">'."\n";
      print '</form>'."\n";
      require ('../foot.inc.php');
      exit;
      break; 
   case 'DEL_OK': //Удаление раскладки
      if ( ($mult_btn == $strYes) && isset($mon_nr) )
      {
      	
      	$adb->web_delete_monitors($display, $mon_nr);
 
         echo '<p><font color="' . $warn_color . '">' . sprintf ($strDeleteMon, $counter, $mon_name, $display=='R'?$sRightDisplay1:$sLeftDisplay1) . '</font></p>' ."\n";
      }
      unset($mon_nr);
      break;
   }
}
//готовые раскладки
echo '<h2>' . $r_mon_list . '</h2>' ."\n";


if ( !isset($mon_nr) || $mon_nr =='')
{
   /* Performing new SQL query */
	//Загрузка установленных раскладок
    $result = $adb->web_get_monitors(); 
    
   $LD = array();
   $RD = array();
   foreach ( $result as $row)	{
      if ( $row['DISPLAY'] == 'R' )
         $D = &$RD;
      else
         $D = &$LD;
      $D[(int)$row['MON_NR']] = 	array(
         'layout_type' => $row['MON_TYPE'],
         'layout_name' => $row['SHORT_NAME'],
         'CHANGE_TIME' => $row['CHANGE_TIME'],
         'CHANGE_USER' => $row['CHANGE_USER'],
         'CHANGE_HOST' => $row['CHANGE_HOST'],
      	 'PrintCamNames' => $row['PRINT_CAM_NAME'],
      	 'AspectRatio' => $row['PROPORTION'],
      	 'isDefault' => $row['IS_DEFAULT'],
         'wins' => array ($row['WIN1'],  $row['WIN2'],  $row['WIN3'],  $row['WIN4'], $row['WIN5'],  $row['WIN6'],  $row['WIN7'],  $row['WIN8'], $row['WIN9'],  $row['WIN10'], $row['WIN11'], $row['WIN12'], $row['WIN13'], $row['WIN14'], $row['WIN15'], $row['WIN16'], $row['WIN17'],  $row['WIN18'], $row['WIN19'], $row['WIN20'], $row['WIN21'], $row['WIN22'], $row['WIN23'], $row['WIN24'], $row['WIN25']),
      );
   }
   
   //Создание перечня готовых раскладок
   $mon_nr=0;
   $counter = 1;
   print "<div>";
   
   //Если нет ни одной готовой раскладки
   if(!count($LD)){
   	$mon_nr=-1;
   	print '<div> &nbsp;'.$no_any_layout."</div>\n";
   }
   
   //Вывод готовых раскладок
   foreach ($LD as $mon_nr=>$res_val){
	print "<div style=\"border: 1px solid black; padding: 5px; height:290px; width: 240px; text-align:center; float:left; margin:10px; \">\n";
    if ( array_key_exists ( $mon_nr, $LD ) ) {
	//левый монитор (правый вообще не используем)
    	prt_l('L', $mon_nr, $LD[$mon_nr], $admin_user, $layout_word, $counter, $LD[$mon_nr]['AspectRatio'], $LD[$mon_nr]['PrintCamNames'], $LD[$mon_nr]['isDefault']);
        print '<div class=\'camlayout\' >'; 
        layout2table ( $LD[$mon_nr]['layout_type'], 160, $LD[$mon_nr]['wins'] ); 
        
        print '</div>'. "\n";
        if ( $admin_user ) {
        	print '<br><a href="'.$_SERVER['PHP_SELF'].'?cmd=DEL&display='.$display.'&mon_nr='.$mon_nr.'&mon_name='.$LD[$mon_nr]['layout_name'].'&counter='.$counter.'">'. $GLOBALS['strDelete'] . '</a>&nbsp;/&nbsp;';
        	print '<a href="'.$GLOBALS['conf']['prefix'].'/admin/web_mon_tune.php?display='.$display.'&mon_nr='.$mon_nr.'&mon_name='.$LD[$mon_nr]['layout_name'].'&mon_type='.$LD[$mon_nr]['layout_type'].'&counter='.$counter.'">'. $GLOBALS['strEdit'] . '</a>' . "\n";
        }
    }
      print "</div>\n";
      $counter++;
   }
   print "</div>\n";
   //Выравниваем таблицы раскладок по центру элемента
   print '<script type="text/javascript"> $(".camlayout table").attr("align", "center");  </script>';
   
   $mon_nr++;
   //Создать новую раскладку
   	echo '<div style="clear:left;"><br /><h2>' . sprintf($r_mon_addnew, $counter, '1'). '</h2></div>' ."\n";
//   	if($mon_nr<0) $mon_nr=0;
   	print "<div>\n";
	if ( $admin_user)
   	print '<a href="'.$conf['prefix'].'/admin/web_mon_addnew?display=L&mon_nr='.$mon_nr.'&counter='.$counter.'">'.$l_mon_addnew.'</a>'."\n";
   	else
   	print '&nbsp;'. $l_mon_admin_only ."\n";
   	print "</div>\n";
   
}

require ('../foot.inc.php');
?>
