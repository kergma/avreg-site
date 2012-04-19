<?php
require_once('../lib/config.inc.php');
// TODO  rotate live image

$local = true; /* local or http only supported */

function die412($msg)
{
   header("$_SERVER[SERVER_PROTOCOL] 412 Precondition failed");
   echo $msg;
   exit;
}
function die403($msg)
{
   header("$_SERVER[SERVER_PROTOCOL] 403 Forbidden");
   echo $msg;
   exit;
}
function die404($msg)
{
   header("$_SERVER[SERVER_PROTOCOL] 404 Not found");
   echo $msg;
   exit;
}
function die500($msg)
{
   header("$_SERVER[SERVER_PROTOCOL] 500 Server Error");
   echo $msg;
   exit;
}

if ( empty($_REQUEST['file']) && !isset($_REQUEST['camera']) )
   die412("couldn't set either \"file\" or \"camera\" param");

$now = time();
$max_age = &$conf['pda-thumb-image-max-age'];
$etag  = null;
$img_uri  = '';
$img_file_stat = false;

$is_local = !empty($_REQUEST['file']);
if ( $is_local ) {
   /* get the local file */
   $img_uri = $conf['storage-dir'] . '/' . $_REQUEST['file'];
   $etag = sha1($img_uri);
   $img_file_stat = stat($img_uri);
   if ( $img_file_stat === false ) {
      if ( file_exists($img_uri) )
         die403("image file \"$img_uri\" cannot be read: $last_err[message]");
      else
         die404("image file \"$img_uri\" not found");
   }

   /* проверяем If-Modified-Since и If-None-Match: Etag */
   $cached_mtime = null; /* empty($cached_mtime) - признак отсутствия/ошибки_парсинга If-Modified-Since */
   $cached_etag  = null; /* empty($cached_etag) - признак отсутвия If-None-Match */
   if ( !empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) ) {
      $cached_mtime = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
      if ( $cached_mtime < 0 )
         $cached_mtime = null;
   }
   if ( !empty($_SERVER['HTTP_IF_NONE_MATCH']) )
      $cached_etag  = trim($_SERVER['HTTP_IF_NONE_MATCH'], "\"'\t\n\r ");

   if ( (!empty($cached_etag)  and (($cached_etag === $etag) or ($cached_etag === '*'))) or
        (!empty($cached_mtime) and (($img_file_stat['mtime'] <= $cached_mtime) and ($now > $cached_mtime)))
      ) {
        /* предполагаем что если клиент и спрашивает одним из 
         * то, у него в кеше есть уже файл и если всё норм. то отв. 304 */
         header("$_SERVER[SERVER_PROTOCOL] 304 Not Modified");
         exit;
   }

   $path_info = pathinfo($img_uri);
   switch ($path_info['extension']) {
      case 'jpeg':
      case 'jpg':
      case 'JPEG':
      case 'JPG':
         $image_type = IMG_JPEG;
         $gd = @imagecreatefromjpeg($img_uri);
         break;
      case 'png':
      case 'PNG':
         $image_type = IMG_PNG;
         $gd = @imagecreatefromjpeg($img_uri);
         break;
      default:
         die("only jpeg and png supported");
   }
} else {
   /* live camera image over http:// */
   $cam_nr = (int)$_REQUEST['camera'];
   require_once('../lib/get_cam_url.php');
   $img_uri = preg_replace('@://[^/:]+@', '://localhost', get_cam_http_url($conf, $cam_nr, 'jpeg', true));
   // header('X-URL: ' . $img_uri);
   $jpeg_data = file_get_contents($img_uri, false);
   if ( $jpeg_data === false ) {
      $last_err = error_get_last();
      die500("image file \"$img_uri\" cannot be read: $last_err[message]");
   }
   $image_type = IMG_JPEG;
   $gd = imagecreatefromstring($jpeg_data);
   if ( !$gd ) {
      $last_err = error_get_last();
      die500("image file \"$img_uri\": $last_err[message]");
   }
}


// Get/calc new image resolution
$width_src  = imagesx($gd);
$height_src = imagesy($gd);

$width_new = $conf['pda-max-image-width'];
if ( !empty($_REQUEST['width']) ) {
   if ( (int)$_REQUEST['width'] < $width_new )
      $width_new = (int)$_REQUEST['width'];
}
if ( empty($_REQUEST['height']) ) {
   $height_new = (int)(((float)$width_new / (float)$width_src) * (float)$height_src);
} else
   $height_new = (int)$_REQUEST['height'];
// die("[$width_src x $height_src] -> [$width_new x $height_new]");

$thumb = imagecreatetruecolor($width_new, $height_new);
// Resize
imagecopyresized($thumb, $gd, 0, 0, 0, 0, $width_new, $height_new, $width_src, $height_src);

$gmt_now = gmdate('D, d M Y H:i:s', $now) . ' GMT';
if ( $is_local ) {
   /* включаем агрессивное кеширование */
   header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $img_file_stat['mtime']) . ' GMT');
   header('Expires: ' . gmdate('D, d M Y H:i:s', $now + $max_age) . ' GMT');
   header("Etag: \"$etag\"");
   header("Cache-Control: private, max-age=$max_age");
   header("Content-type: " . image_type_to_mime_type($image_type));
} else {
   /* live image отключаем вовсе кеширование */
   header('Last-Modified: ' . $gmt_now);
   header('Expires: ' . $gmt_now);
   header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
   header('Pragma: no-cache'); // HTTP/1.0
   header("Content-type: " . image_type_to_mime_type($image_type));
}

switch($image_type) {
case IMG_JPEG:
   imagejpeg($thumb, null, $conf['pda-jpeg-quality'] );
   break;
case IMG_PNG:
   imagepng($thumb);
   break;
}
?>
