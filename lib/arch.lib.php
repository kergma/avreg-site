<?php


function sox_norm ($in_file,$dest_dir)
{
  //$pos = strpos(mime_content_type ($in_file), 'audio');
  //if ($pos === false) {
  //  print '<font color="'.$error_color.'"><p>'.sprintf ($strNotSoundFileFmt,$uploadfile).'</p></font>'."\n";
  //  return FALSE;
  //}
  $conf['sox']fmt = $conf['sox'] . ' %s -r 8000 -u %s';
  $path_parts = pathinfo($in_file);
  $out_file = strtolower (basename ($in_file, $path_parts['extension'])) . 'au';
  system ( sprintf ($conf['sox']fmt, $in_file, $dest_dir.$out_file) , $ret );
}


function unzip ($zipname, $dest_dir, $enc_type)
{
  $UNZIPfmt  = $conf['unzip'] . ' -joL %s -d %s> /dev/null 2>&1';
  $GUNZIPfmt = $conf['gzip']  . ' -cd %s | ' . $conf['tar'] . ' -C %s -x -f -';
  $BUNZIPfmt = $conf['bzip2'] . ' -cd %s | ' . $conf['tar'] . ' -C %s -x -f -';
  $conf['tmp-dir'] = dirname ($zipname) . '/unzip/';
  $retval = TRUE;
  system ($conf['rm'] . ' -fr ' .$conf['tmp-dir']);
  @mkdir ($conf['tmp-dir']);
  switch ($enc_type) {
        case 'ZIP':
                $un_arch = sprintf ($UNZIPfmt, $zipname, $conf['tmp-dir']);
                break;
        case 'GZIP':
                $un_arch = sprintf ($GUNZIPfmt, $zipname, $conf['tmp-dir']);
                break;
        case 'BZIP2':
                $un_arch = sprintf ($BUNZIPfmt, $zipname, $conf['tmp-dir']);
                break;
  }  // switch
  system ( $un_arch, $ret );
  $c=0;
  if ($dh = opendir($conf['tmp-dir'])) {
     while (($file = readdir($dh)) !== false) {
       if (($file != '.') && ($file != '..')) {
           sox_norm($conf['tmp-dir'].$file, $dest_dir);
           $c++;
       } //if
     }  //while
     closedir($dh);
  }
  return $retval;
}


?>
