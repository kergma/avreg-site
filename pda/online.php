<?php

$pageTitle = sprintf('Камера №%u', $_GET['camera']);
// $body_onload='body_loaded();';
require ('head_pda.inc.php');

if ( !isset($camera) || !settype($camera, 'int') )
   die('should use "camera" cgi param');

require_once('../lib/get_cam_url.php');

$GCP_query_param_list=array('work', 'allow_networks', 'text_left', 'geometry', 'Hx2');
require_once('../lib/get_cams_params.inc.php');
$cam_conf = &$GCP_cams_params[$camera];
$cam_name = $cam_conf['text_left'];
list($w, $h) = sscanf($cam_conf['geometry'], '%ux%u');
if ( $cam_conf['Hx2'] )
   $h *= 2;
$cam_url = "../lib/img_resize.php?camera=$camera";
?>

<script type="text/javascript">
function img_evt(e_id)
{
   if ( (typeof window.img_evt2).charAt(0) != 'u' )
      img_evt2(e_id);
   else
      IMG_EVT_OCCURED = e_id;
};
</script>

<?php
if ( !isset($refresh) ) {
   $refresh_img_a = array(
       0 => 'вручную',
       1 => '0,5 сек.',
       2 => '1 сек.',
       4 => '2 сек.',
       6 => '3 сек.');

   if ( isset($_SESSION['refresh']) ) {
      $refresh = (int)$_SESSION['refresh'];
      if ( !array_key_exists( $refresh, $refresh_img_a ) )
         $refresh = 0;
   } else
      $refresh = 0;

   printf('<IMG id="viewport" src="%s&width=%u" width="%u" style="border: 1px solid;" alt="%s снапшот" onerror="img_evt(1);" />',
      $cam_url, $conf['pda-thumb-image-width'], $conf['pda-thumb-image-width'], $cam_name);
?>
<br>
<form action="online.php" method="GET">
<input type="hidden" name='camera' value="<?php echo $camera; ?>">
<div>
Обновлять изображение: 
<?php print getSelectByAssocAr('refresh', $refresh_img_a, false, 1, 1, $refresh, false); ?>
</div>
<div>
<input type="submit" id="btSubmit" value="<?php echo 'Наблюдать камеру'; ?>">
&nbsp;<a href='./' title='<?php echo $strHome; ?>'><?php echo $strHome; ?></a>
</div>
</form>
<?php
} else {
   /* смотрим детально и с обновлениями */
   $_SESSION['refresh'] = $refresh;

   printf('<IMG id="viewport" src="%s"
      alt="Загружается изображение с %s ..."
      border="1px"
      onclick="refresh_img();" onload="img_evt(0);" onerror="img_evt(1);" oabort="img_evt(2);">',
         $cam_url,  $cam_name);
}
?>

<script type="text/javascript">
var refresh_mode = <?php echo (!isset($refresh) ? '-1' : $refresh) ?>;
var CAM_INFO = {
   'nr'    : <?php echo $camera; ?>,
   'name'  : '<?php echo $cam_name; ?>',
   'active': <?php if ($cam_conf['work'] && $cam_conf['allow_networks']) echo 'true'; else echo 'false'; ?>,
   'width' : <?php echo $w; ?>,
   'height': <?php echo $h; ?>,
   'url'   : '<?php echo $cam_url; ?>'
};

var IMG = document.getElementById('viewport'); // FIXME if isn't DOM ready? 
var BTSUBMIT = document.getElementById('btSubmit');
var REFRESH  = document.getElementById('refresh');

function refresh_img()
{
   var now = new Date();
   var update_url = CAM_INFO['url'] + '&_=' + now.getTime(); // prevent local browser caching
   IMG.setAttribute('src', update_url);
};

function img_evt2(e_id)
{
   var ms;

   switch(e_id)
   {
   case 0: // onload
      if ( refresh_mode <= 0 /* manual refresh */ )
         return;
      tmr = setTimeout('refresh_img();', refresh_mode * 500);
      break;
   case 1: // onerror
      IMG.setAttribute('alt', 'Ошибка загрузки изображения');
   case 2: // onabort
      if ( e_id != 1 )
         IMG.setAttribute('alt', 'Загрузка изображения прервана пользователем');
      IMG.style.color = 'Red';
      BTSUBMIT.setAttribute('disabled', true);
      REFRESH.setAttribute('disabled', true);
      break;
   default:
      alert('unknown event id ' + e_id);
   }
};

/* если img_evt() успел сработать до загрузки страницы FIXME правильней исп. ready или хотя бы body_onload */
if ( (typeof IMG_EVT_OCCURED).charAt(0) != 'u' )
   img_evt2(IMG_EVT_OCCURED);

</script>

<?php
// tohtml($_SESSION);
require ('../foot.inc.php');
?>
