<?php
require ('../head.inc.php');

function getmacs()
{
   $mac = '<not ethernet card>';
   $v = popen ($GLOBALS['GETMAC'], 'r');
   $mac = fread($v, 12);
   pclose ($v);
   return $mac;
}

echo '<h1>' . sprintf($r_key, $named) . '</h1>' ."\n";

echo '<h2>' . sprintf($r_key_state,$conf['key']) . '</h2>' ."\n";
clearstatcache();

if ( empty($conf['key']) || !file_exists($conf['key']) ) {
  echo '<div class="error">'.sprintf($key_not_found,$conf['key']).'</div>'."\n";
} else if ( !is_readable($conf['key']) ) {
  echo '<div class="error">'.sprintf($key_not_rd,$conf['key']).'</div>'."\n";
} else {
   $keyfile = fopen($conf['key'], 'r');
   if (false === $keyfile)
      die();
   $key_array=array();
   $key_opts='';
   while (!feof($keyfile)) {
      $line = trim(fgets($keyfile, 1024));
      $key_array[] = htmlentities($line, ENT_QUOTES).'<br />'."\n";
      if ( preg_match('/^key_ver5_part1=(.*)$/i',$line, $matches) ) {
         $key_opts = $matches[1];
         break;
      }
   }
   fclose($keyfile);

   if ( ! empty($key_opts) ) {
      if ( 0 === strpos($key_opts, 'xx') ) {
        // trial key
        echo '<div class="info">'.$trial_key.'</div>'."\n";
        echo '<pre class="tty">'."\n";
        passthru($conf['ip'].' link show');
        echo '</pre>'."\n";
      } else if (preg_match('/^(([0-9A-Fa-f]{2}:?){6})-(([0-9A-Fa-f]{2}:?){4})$/',
                  $key_opts,$matches) ) {
        $bind_mac=$matches[1];
        $mac_opts=$matches[3];
        // tohtml($matches);
        sscanf($mac_opts, "%x:%x:%x:%x",
               $net_videos, $v4l_videos,
               $net_audios, $analog_audios);
        // print
        reset($key_array);
        echo '<div class="info">'."\n";
        foreach ($key_array as &$line) {
           echo($line);
        }
         echo '<br />'."\n";
        echo sprintf($strKeyOptions,$net_videos, $v4l_videos, $net_audios, $analog_audios);
        echo '</div>'."\n";
      } else {
         echo '<div class="error">'.$key_bad.'</div>'."\n";
      }
   }
}
/*
string htmlentities ( string string [, int quote_style [, string charset]] )

	if ( isset($ini_array['KEY']) ) {
		if ( isset($ini_array['KEY']['key1']) && !empty($ini_array['KEY']['key1']))	{
			$key_mac = substr($ini_array['KEY']['key1'],0,12);
			$real_mac = getmac();
			$key_opt = substr($ini_array['KEY']['key1'],12);
			$allow_cams = hexdec(substr($key_opt,0,2));
			print '<p><strong>'.$strKeyOptions.'</strong><br>'."\n";
			print $strMacAddr.': '.$key_mac.'<br>'."\n";
			print $strMaxServerCams.': '.$allow_cams.'<p>'."\n";
			if (0 === strcmp($key_mac,'xxxxxxxxxxxx')) {
				print '<div class="help">' . sprintf($demo_key,$real_mac) . '</div>' ."\n";
			} else {
				if ( 0 !== strcmp($key_mac,$real_mac) ) {
					print '<p><font size="+1" color="' . $error_color . '">' . sprintf($fmtBadMac,$real_mac) . '</font></p>' ."\n";
				}
			}
		} else
			print '<p><font size="+1" color="' . $error_color . '">' . $key_bad . '</font></p>' ."\n";
	} else 
		print '<p><font size="+1" color="' . $error_color . '">' . $key_bad . '</font></p>' ."\n";
} else {
	print '<p><font size="+1" color="' . $error_color . '">' . $key_not_found . '</font></p>' ."\n";
}
*/

// phpinfo();
require ('../foot.inc.php');
?>
