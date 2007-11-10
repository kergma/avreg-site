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
</head>
<body>
<h1 align="center">
<?php
  $tab = getParVal('tab');
  switch ($tab) {
    case 'control':
        echo $right_control; break;
    case 'status':
        echo $right_status; break;
    case 'statistics':
        echo $right_statistics; break;
    case 'tune':
        echo $right_tune; break;
  } // switch
?>
</h1>
<? phpinfo(); ?>
</body>
</html>
