<?php
/**
 * @file pda/files.php
 * @brief 
 */
$USE_JQUERY = true;

//масштаб изображений
$scale=0; 
if(isset($_GET['scl']))$scale = $_GET['scl'];

$pageTitle = sprintf('Камера №%u - файлы сеанса №%u', $_GET['camera'], $_GET['ser_nr']);
$lang_file = '_admin_cams.php';
require ('head_pda.inc.php');
// phpinfo(INFO_VARIABLES);
if ( !isset($camera) || !settype($camera, 'int'))
   die('should use "camera" cgi param');
if ( !isset($ser_nr) || !settype($ser_nr, 'int'))
   die('should use "ser_nr" cgi param');
if ( !isset($s) && !isset($f) )
   die('invalid cgi params');
$files_sess_var_name = sprintf('files_%u_%u_%u_%u', $camera, $ser_nr, $s, $f);

$files = null; // массив - ссылок на файлы, вычитанные с базы
$recsess_list_url = null; // url, откуда пришли - со списка серий/записи движения
if ( !empty($_SERVER['HTTP_REFERER']) && false != strpos($_SERVER['HTTP_REFERER'], '/offline.php') )
{
   $_SESSION['recsess_list_url'] = $_SERVER['HTTP_REFERER'];
   $recsess_list_url = &$_SESSION['recsess_list_url'];
} else if ( !empty($_SESSION['recsess_list_url']) )
   $recsess_list_url = &$_SESSION['recsess_list_url'];

if ( isset($_SESSION[$files_sess_var_name]) ) {
   $files = &$_SESSION[$files_sess_var_name];
} else {
   $timebegin = strftime('%Y-%m-%d %T', (int)$s);
   $timeend = false;
   if ( !empty($f) ) {
      $timeend   = strftime('%Y-%m-%d %T', (int)$f);
   }
   $use_desc_order = empty($desc) ? '' : 'desc';

    $files = $adb->get_files($camera, $ser_nr, $timebegin, $timeend,  $use_desc_order);
      
   if ( !$files ) {
      print "<div style='padding: 10px;'>Странно..., ничего не найдено :-0<br>\n";
      print "<a href='javascript:window.history.back();' title='$strBack'>$strBack</a></div>\n";
      exit;
   }
   $_SESSION[$files_sess_var_name] = $files;
}
session_write_close();

//изменение масштаба
$tumb_sizes = get_scales($conf['pda_scale']);
if($tumb_sizes == null || sizeof($tumb_sizes)==0 ){
	//если ничего в конфиге не определено
	$tumb_sizes = array(0=>array('w' => '160', 'h' => '80',));
}
//добавляем контролы масштаба
print '<div id="scale">'."\n";
print '<input type="button" value="-" id="zoomout"  />'."\n";
print '<input type="button" value="+" id="zoomin" />'."\n";
print "	</div>\n";

?>

<script type="text/javascript" >

var SELF_ADR = <?php print "\"".$_SERVER['REQUEST_URI']."\"" ; ?>; 
var TOTAL_SCLS = <?php print sizeof($tumb_sizes); ?>; //кол-во предопределенных значений масштаба 

	$(function(){
		var expr = new RegExp('scl=\\d+', 'ig');
		var scale = 0;
		var isscl = expr.test(SELF_ADR);
		//определяем текущий масштаб			
		if(isscl){
			scale = SELF_ADR.match(expr)[0];
			scale = parseInt(scale.replace('scl=', ''));
		}

		if(scale>=TOTAL_SCLS-1){
			 $('#zoomin').attr({'disabled':'disabled'});
		}
		if(scale<=0){
			$('#zoomout').attr({'disabled':'disabled'});
		}
		//нажатие кнопоку изменения масштаба 
		$('#scale>input[type=button]').click(function(e){
			var act = SELF_ADR ; 
			var toReload = true;
			//устанавливаем новое значение масштаба 
			if( $(e.target).attr('id')=='zoomin' ){
				scale++;		
				if(scale>= TOTAL_SCLS){
					 scale = TOTAL_SCLS-1;
					 toReload = false;
				}
			}
			else{
				scale--;
				if(scale<0){
					scale=0;
					toReload = false;
				}
			}
			if(isscl){
				//если значение масштаба уже установлено 
				act= act.replace(expr, "scl=" + scale );
			}
			else{
				act +=  "&scl=" + scale;
			}
			if(toReload)window.open(act,'_self');
		});
	});
	

</script>
	
	

<?php 

/* pagination */
require_once('paginator.inc.php');
$pagi = new PDA_Paginator($files,
   isset($off) ? (int)($off) : 0,
   sprintf('files.php?camera=%u&ser_nr=%u&s=%u&f=%u', $camera, $ser_nr, $s, $f),
   $conf,
   $conf['pda-thumb-image-per-page']);
$pagi->print_above();

/* print objects <img> to page */
foreach($pagi as $row) 
{
   $START  = (int)$row[0];
   $FINISH = (int)$row[1];
   $EVT_ID = (int)$row[2];
   $FILESZ_KB = (int)$row[3];
   $FRAMES = (int)$row[4];
   $U16_1  = (int)$row[5];
   $U16_2  = (int)$row[6];
   $orig_src = $conf['prefix'] . $conf['media-alias'] . '/' . $row[7];
   
   print "<div style='margin: 0px 0px 10px 0px; pad: 0px 0px 0px 0px; border-bottom: 1px dotted;'>\n";
   print strftime('&nbsp;%d(%a) %T<br>', $START);
   if ( $EVT_ID >= 15 && $EVT_ID <= 22 /* jpegs */ ) {
      $jpeg_info = "$FILESZ_KB kB, [$U16_1 x $U16_2]";
      printf("<a href='$orig_src' title='Открыть оригинал $jpeg_info'>\n");
      
      printf('<img src="../lib/resize_img.php?url=%s&w=%u&h=%u" alt="Ошибка загрузки">',
      urlencode("http://".$_SERVER["SERVER_NAME"].$orig_src),
      $tumb_sizes[$scale]['w'],
      $tumb_sizes[$scale]['h']);
      
      print "</a></div>\n";
   }
}

$pagi->print_below();

print '<div>';
if ($recsess_list_url)
   print "<a href='$recsess_list_url' title='К списку сеансов записи'>К списку</a>&nbsp;|&nbsp;\n";
print "<a href='./' title='$strHome'>$strHome</a></div>\n";

require ('../foot.inc.php');


//сортирует массив размеров
function get_scales($scales, $orderByWidth=true){
	$sizes = array();
	foreach($scales as $key=>$value ){
		$temp;
		preg_match_all('/\d+/', $value, $temp); 
		if(sizeof($temp[0])!=2) continue;
		$sizes[$key]=array('w'=>$temp[0][0], 'h'=>$temp[0][1]);
	}
	if($orderByWidth) usort($sizes, 'sort_by_width');
	else usort($sizes, 'sort_by_height');
	return  $sizes;
}

//предикаты сортировки
function sort_by_width($f, $s){
	return $f['w']-$s['w'];
} 
function sort_by_height($f, $s){
	return $f['h']-$s['h'];
}




?>

























