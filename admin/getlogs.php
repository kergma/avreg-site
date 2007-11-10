<?php
require_once('../lib/config.inc.php');

clearstatcache();

if ( isset($howmatch) && !empty($howmatch) ) {
   settype($howmatch,'int');
   system($conf['sudo'] . ' ' .$conf['report-bug'] . ' ' . $howmatch, $retval);
 } else {
   system($conf['sudo'] . ' ' .$conf['report-bug'], $retval);
}
if ( $retval != 0 )
 die('Error run ' . $conf['report-bug']);

 
chdir($conf['upload-dir']);
 
if ( !file_exists('testhost.log.gz') )
  die('File not found testhost.log.gz');

setlocale(LC_TIME, "C");
$fname=strftime('log_%b%d_%H%M.txt.gz');
header('Content-type: application/x-gzip');
header('Content-Disposition: attachment; filename="'.$fname.'"');
readfile('testhost.log.gz');

?>
