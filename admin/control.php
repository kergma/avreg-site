<?php
require ('../head.inc.php');
DENY($admin_status);
require_once ('../lib/my_conn.inc.php');
require('warn.inc.php');

/*
$charset = mysql_client_encoding($link);
printf ("current character set is %s\n", $charset);
*/

function print_messages()
{
  $logfile = popen($GLOBALS['conf']['sudo'] . ' ' . $GLOBALS['conf']['tail'] . ' -n 60 '.$GLOBALS['conf']['daemon-log'].' 2>&1', 'r');
   print '<div class="tty">'."\n";
   while (!feof ($logfile))
   {
      $buffer = fgets($logfile, 1024);
		if ( preg_match('/crit|err|fail|invalid|bad|unable|warn|notice|could`t|cannot|can`t|not|duplicate|reset|reject|drop|unsupport/i', $buffer) )
			print '<font color="#FFFF99">'.$buffer.'</font><br>';
		else
			print $buffer.'<br>';
	}
	pclose($logfile);
	print '</div><br />'."\n";
}

echo '<h1>' . $r_control . '</h1>' ."\n";

$srun = check_videoserv();

$cmd_released=NULL;

if (isset($cmd)) {
  if ($srun)
  {
    if ( 1 === strpos($cmd,'tart')) {
      $wrn=sprintf($runVservWarn1,$cmd);
      unset($cmd);
    }
  } else {
    if ( 1 !== strpos($cmd,'tart')) {
      $wrn=sprintf($runVservWarn2,$cmd);
      unset($cmd); 
    }
  }
  if (!isset($cmd))
    print ('<p class="HiLiteBigErr">'.$wrn.'</p>');
} 

if ( isset($cmd) )
{
	$cmd = strtolower($cmd);
	if ( isset($confirm_btn) )
	{
		if ( $confirm_btn ===  $strYes )
		{
			$strwarning = '';
			if       ( $cmd == 'start' ) {
				$strwarning = $strRunA;
			} elseif ( $cmd == 'restart' ) {
				$strwarning = $strRestartA;
	/*
			} elseif ( $cmd == 'condrestart' ) {
				$strwarning = $strCondRestartA;
	*/
			} elseif ( $cmd == 'reload' ) {
				$strwarning = $strReloadA;
			} elseif ( $cmd == 'snapshot' ) {
				$strwarning = $strSnapshotA;
			} elseif ( $cmd == 'stop' ) {
				$strwarning = $strStopA;
		    	}

			if ( !empty($strwarning) )
			{
			print '<p><font size="+1" color="' . $warn_color . '">' . $strwarning;
			print_syslog (LOG_WARNING, sprintf ('command `%s\'', $cmd));
			$outline = exec($conf['sudo'] . ' ' . $conf['daemon'] . ' ' .$cmd,
                    $outs, $retval);
			if ( $retval === 0 ) {
				print 'OK</font></p>' ."\n";
				$cmd_released=TRUE;
				// print '<p>' . $outline . '</p>' ."\n";
			} else {
				print $strError. '</font></p>' ."\n";
				print '<p><font size="+1" color="Red">' . $outline . '</font></p>' ."\n";
				$cmd_released=FLASE;
				print '<div style="color:Red;">'.$strCheckLog.'</div>';
				print_messages();
			}
			usleep(300000);
			$srun = check_videoserv();
			}
		}
	} else {
    if ( $cmd == 'start' ) {
 		print '<p><font size="+1" color="' . $warn_color . '">' . sprintf ($fnmWarnControl,$strRunW) . '</font></p>' ."\n";
	} elseif ( $cmd == 'restart' ) {
        print '<p><font size="+1" color="' . $error_color . '">' . $mon_exit . '</font></p>' ."\n";
		print '<p><font size="+1" color="' . $warn_color . '">' . sprintf ($fnmWarnControl,$strRestartW) . '</font></p>' ."\n";
/*
	} elseif ( $cmd == 'condrestart' ) {
		$strwarning = $strCondRestartW;
*/
	} elseif ( $cmd == 'reload' ) {
        print '<p><font size="+1" color="' . $warn_color . '">' . $strReloadW.'</font></p>' ."\n";
	} elseif ( $cmd == 'snapshot' ) {
 		print '<p><font size="+1" color="' . $warn_color . '">'. $strSnapshotW .'</font></p>' ."\n";
        } elseif ( $cmd == 'stop' ) {
 		print '<p><font size="+1" color="' . $warn_color . '">' . sprintf ($fnmWarnControl,$strStopW) .'</font></p>' ."\n";
	} else {
       MYDIE("invalid command");
    }
	print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
	print '<input type="hidden" name="cmd" value="'.$cmd.'">'."\n";
	print '<input type="submit" name="confirm_btn" value="'.$strYes.'">'."\n";
    if (isset($from)) {
      print '<input type="button" name="confirm_btn" value="'.$strNo.'" onclick="javascript:window.history.back();">'."\n";
      print '<input type="hidden" name="from" value="'.$from.'">'."\n";
    } else
      print '<input type="submit" name="confirm_btn" value="'.$strNo.'">'."\n";
      
	print '</form>'."\n";
	require ('../foot.inc.php');
	exit;
 }
}

require('_vidserv_status.inc.php');

print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST"><FIELDSET>'."\n";
echo '<h2>' . $r_conrol_control . '</h2>' ."\n";
if ( !$srun )
{
	print '<input type="submit" name="cmd" class="enabled" value="Start">'.$strRun."\n";
	print '<br><input type="submit" disabled name="cmd"  class="disabled" value="Restart">'.$strRestart.'&nbsp;<img src="'.$conf['prefix'].'/img/hotsync_busy.gif" width="22" height="22" align="middle" border="0">&nbsp;<img src="'.$conf['prefix'].'/img/hotsync.gif" width="22" height="22" align="middle" border="0">'."\n";
	// print '<br><input type="radio" disabled name="cmd" value="condrestart">'.$strCondRestart."\n";
	print '<br><input type="submit" disabled name="cmd"  class="disabled" value="Reload">'.$strReload.'&nbsp;<img src="'.$conf['prefix'].'/img/hotsync.gif" width="22" height="22" align="middle" border="0">&nbsp;'."\n";
	print '<br><input type="submit" disabled name="cmd"  class="disabled" value="Snapshot">'.$strSnapshot."\n";
	print '<br><input type="submit" disabled name="cmd"  class="disabled" value="Stop">'.$strStop."\n";
} else {
	print '<input type="submit" disabled name="cmd" class="disabled" value="Start">'.$strRun."\n";
	print '<br><input type="submit" name="cmd" value="Restart"  class="enabled">'.$strRestart.'&nbsp;<img src="'.$conf['prefix'].'/img/hotsync_busy.gif" width="22" height="22" align="middle" border="0">&nbsp;<img src="'.$conf['prefix'].'/img/hotsync.gif" width="22" height="22" align="middle" border="0">'."\n";
	// print '<br><input type="radio" name="cmd" value="condrestart">'.$strCondRestart."\n";
	print '<br><input type="submit" name="cmd" class="enabled" value="Reload">'.$strReload.'&nbsp;<img src="'.$conf['prefix'].'/img/hotsync.gif" width="22" height="22" align="middle" border="0">&nbsp;'."\n";
	print '<br><input type="submit" name="cmd" class="enabled" value="Snapshot">'.$strSnapshot."\n";
	print '<br><input type="submit" name="cmd" class="enabled" value="Stop">'.$strStop."\n";
}
print '</FIELDSET></form>'."\n";

if ($cmd_released !== FLASE) {
   if ($srun)
      print '<div>'.$strCheckLog.'</div>';
   else 
      print '<div style="color:Red;">'.$strCheckLog.'</div>';
   print_messages();
}

// phpinfo();
require ('../foot.inc.php');
?>
