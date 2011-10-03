<?php 
if (!isset($_POST['method']) || empty($_POST['method'])) {
	// Загрузка главной страницы галереи
	$pageTitle='gallery_title';
	$USE_JQUERY = true; 
	$ie6_quirks_mode = true;
	// Подключение стилей
	$css_links = array( 'offline/gallery/css/main.css', 
						'offline/gallery/css/html5reset-1.6.1.css');
	// Подключение js скриптов
	$link_javascripts = array('offline/gallery/js/jquery.jstree.js',
								'offline/gallery/js/jquery.mousewheel.min.js',
								'offline/gallery/js/main.js');
	require_once('../head.inc.php');
	$GCP_query_param_list=array('text_left', 'Hx2');
	require('../lib/get_cams_params.inc.php');
	if ( $GCP_cams_nr == 0 )
   		die('There are no available cameras!');
   	// Подключение самой страницы галереи
	require_once('gallery/index.php');
	require_once('../foot.inc.php');
} else {
	
	// Ответ аякс запроса
	require_once('../lib/config.inc.php');
	require_once('../lib/my_conn.inc.php');
	$GCP_query_param_list=array('text_left', 'Hx2');
	require('../lib/get_cams_params.inc.php');
	if ( $GCP_cams_nr == 0 )
   		die('There are no available cameras!');
	require_once('gallery/gallery.php');
	// Инициализация класа галереи
	$gallery = new Gallery($_POST);
	// Возврат ответа запроса
	$gallery->print_result();
	require_once('../lib/my_close.inc.php');
}
?>
