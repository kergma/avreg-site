<?php
unset($outs);
$cmd=$GLOBALS['conf']['daemon'].' status';
print '<div class="warn">' ."\n";
print $r_conrol_state.' : ';
exec($GLOBALS['conf']['sudo'].' '.$cmd, $outs, $retval);
$srun = ($retval === 0)?true:false;
if ( $srun ) {
   print '<span class="HiLiteBig">' . sprintf($fmtServerWorked,$named).'</span>' ."\n";
} else {
   print '<span class="HiLiteBigErr">'.sprintf($strServerStoped, $named, $sip).'</span>' ."\n";
}
if (isset($outs) && is_array($outs)) {
   echo '<pre>';
   echo '# '.$cmd."\n";
   foreach ($outs as $line)
      echo $line."\n";
   echo '</pre>' ."\n";
}
print '</div><br />' ."\n";

?>
