<?php

/**
 * @file admin/web_set_def.php
 * @brief Устанавливает раскладку по умолчанию
 */

$layout_num = $_GET['layout'];
require_once('../lib/config.inc.php');
$res = $adb->webSetDefLayout($layout_num);
var_export($res);
