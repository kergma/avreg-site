<?php
if ( !isset($NOBODY) ) { 
   @include($conf['customize-dir'] . preg_replace('%^/[^/]+(/.+)\.php%', '\1_footer.inc.php', $_SERVER['SCRIPT_NAME']));
   print '</body>'."\n";
}
print '</html>'."\n";
?>
