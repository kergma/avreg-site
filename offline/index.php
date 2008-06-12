<?php

require('../lib/config.inc.php');

/**
 * Send http headers
 */
// Don't use cache (required for Opera)
$now = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: ' . $now);
header('Last-Modified: ' . $now);
header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0
// Define the charset to be used
header('Content-Type: text/html; charset=' . $charset);

DENY($arch_status);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">

<html>
<head>
<link rel="SHORTCUT ICON" href="<?php echo $conf['prefix']; ?>/favicon.ico">
<title><?php echo($PrNameEng . '['.$named.']::Архив'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $GLOBALS['charset']; ?>" />
<meta name="author" content="Andrey Nikitin &lt;nik-a at mail dot ru&gt;">
<style type="text/css">
<!--
body  {font-family: <?php echo $right_font_family; ?>; font-size: <?php echo $font_size; ?>}
//-->
</style>
</head>

<frameset border="1" framespacing="1" rows="80%,*">
	<frameset border="1" framespacing="1" cols="67%,*">
		<frame align="right" name="view" src="<?php echo $conf['prefix']; ?>/offline/view-image.php" frameborder="yes" marginwidth="0" marginheight="0" noresize scrolling="no">
		<frame name="result" src="<?php echo $conf['prefix']; ?>/offline/result.php" frameborder="yes" marginwidth="2" marginheight="3" scrolling="auto">
	</frameset>
	<frame name="query" src="<?php echo $conf['prefix']; ?>/offline/query.php" frameborder="yes" marginwidth="0" marginheight="0" scrolling="auto">
</frameset>
</html>
