<?php

unset($DAEMONS_STATES);

print '<div class="warn">' ."\n";
print '<p>' .$r_conrol_state. ":</p>\n";
foreach ( $profconfs as $path ) {
   $_profile = basename($path);
   $cmd=$GLOBALS['conf']['daemon'].' status' . ' ' . $_profile ;
   unset($outs);
   exec($GLOBALS['conf']['sudo'].' '.$cmd, $outs, $retval);
   $srun = ($retval === 0)?true:false;
   $DAEMONS_STATES[$_profile]=$srun;
   if ( $srun ) {
      print '<span class="HiLiteBig">';
      $st = &$strRunned;
   } else {
      print '<span class="HiLiteBigErr">';
      $st = &$strStopped;
   }
   if ( empty($_profile) )
     $msg = sprintf('%s - %s',  $videoserv, $st);
   else
     $msg = sprintf('%s-%s - %s', $videoserv, $_profile, $st);
   print($msg . '</span>' ."\n");
   if (isset($outs) && is_array($outs)) {
      echo '<pre>';
      echo '# '.$cmd."\n";
      foreach ($outs as $line)
         echo $line."\n";
      echo '</pre>' ."\n";
   }
}
// tohtml($DAEMONS_STATES);
print '</div><br />' ."\n";

?>
