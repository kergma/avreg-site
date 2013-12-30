<?php
?>

<div class="ptz_area_right">
    <p>ptz right content</p>
</div>

<div class="ptz_area_bottom">
    <p>ptz bottom content</p>
</div>

<?php
/* предотвращаем дальнейшую загрузку контента оригинальной страницы custom.php */
while (@ob_end_flush()) {
    // do nothing
}
exit();
