<?php
/**
 * @file online/ptz/common.inc.php
 * @brief common firstly php-includes for all PTZ handler
 */

session_start();
if (isset($_SESSION['is_admin_mode'])) {
    unset($_SESSION['is_admin_mode']);
}

$NO_OB_END_FLUSH = true; // for setcookie()
if (empty($pageTitle)) {
    $pageTitle = 'PTZ';
}
$body_style = 'overflow: hidden;  overflow-y: hidden !important; padding: 0; margin: 0; width: 100%; height: 100%;';
/*
$css_links = array(
    'lib/js/third-party/jqModal.css',
    'online/online.css'
 );
*/
$USE_JQUERY = true;
$link_javascripts = array(
    'lib/js/third-party/jquery.mousewheel.min.js',
    'lib/js/third-party/json2.js'
);

$body_addons = 'scroll="no"';
$ie6_quirks_mode = true;
$IE_COMPAT='10';
if (empty($lang_file)) {
    $lang_file = '_ptz.php';
}

require('../../head.inc.php');
