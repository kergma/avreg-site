<?php

require('./config.inc.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>IVR_Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $GLOBALS['charset']; ?>" />
<style type="text/css">
<!--
body  {font-family: <?php echo $right_font_family; ?>; font-size: <?php echo $font_size; ?>}
//-->
</style>
<base target="phpmain">
</head>
<body>
<div align="center"><h2><?php echo $left_logo; ?></h2></div>
<hr noshade>
<h3>+<a href="'.$conf['prefix'].'/state.php"><?php echo $left_status; ?></a></h4>
<h3>+<a href="'.$conf['prefix'].'/control.php"><?php echo $left_control; ?></a></h4>
<h3>+<a href="'.$conf['prefix'].'/main.php?tab=statistics"><?php echo $left_statistics; ?></a></h4>
<h3>+<a href="'.$conf['prefix'].'/tune/index.php"><?php echo $left_tune; ?></a></h4>
<p>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="'.$conf['prefix'].'/tune/common.php"><?php echo $l_tune_parameter; ?></a></p>
<p>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="'.$conf['prefix'].'/tune/channels.php"><?php echo $l_tune_channels; ?></a></p>
<p>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="'.$conf['prefix'].'/tune/periods.php"><?php echo $l_tune_period; ?></a></p>
<p>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="'.$conf['prefix'].'/tune/sound_files.php"><?php echo $l_tune_sounds; ?></a></p>
<p>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="'.$conf['prefix'].'/tune/phrases.php"><?php echo $l_tune_phrase; ?></a></p>
<p>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="'.$conf['prefix'].'/tune/cid.php"><?php echo $l_tune_cid; ?></a></p>
<p>&nbsp;&nbsp;-&nbsp;&nbsp;<a href="'.$conf['prefix'].'/tune/action.php"><?php echo $l_tune_action; ?></a></p>
<hr noshade>
</body>
</html>
