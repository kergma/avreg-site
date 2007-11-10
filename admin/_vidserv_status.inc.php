<?php

print '<div class="warn">' ."\n";
print $r_conrol_state.' : ';
if ( $srun ) {
  if ( file_exists ($conf['daemon-pid']) ) {
    $start_time = strftime('%b %d %Y %H:%M',filemtime($conf['daemon-pid']));
	} else $start_time = 'unknown';
	print '<span class="HiLiteBig">' . sprintf($fmtServerWorked,$named,$start_time) . '</span>' ."\n";
    if (isset($outline))
    	print '<p>' . $outline . '</p>' ."\n";
} else {
	print '<span class="HiLiteBigErr">' . sprintf($strServerStoped, $named, $sip) . '</span>' ."\n";
}
print '</div><br />' ."\n";

?>
