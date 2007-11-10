<?php
$JS_file=$conf['prefix'].'/offline/view.js';
$body_style='margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; ';
require ('../head.inc.php');
require_once ('../lib/my_conn.inc.php');
DENY($arch_status);
?>

<script type="text/javascript" language="JavaScript1.2">
<!--
if (ie||ns6)
  tipobj=document.all? 
     document.all['tooltip'] :
     document.getElementById? document.getElementById('tooltip') : '';

document.onmousemove=positiontip;
// -->
</script>

<div id="content" style="position:absolute;width:100%;height:100%;"
 onmouseover="ddrivetip();" onmouseout="hideddrivetip();">

<?php


if (!isset ($src) || empty($src))
{
	print '<h4 align="center"><br><br><br><br><br><br>'.$strViewFrame.'</h4>'."\n";
} 
print  '</div>'."\n";

require_once ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
