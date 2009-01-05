<?php
require ('../head.inc.php');
DENY($admin_status);
require_once ('../lib/my_conn.inc.php');
require('warn.inc.php');

/*
$charset = mysql_client_encoding($link);
printf ("current character set is %s\n", $charset);
*/

// override REQUEST 'profile'
if ( isset($AVREG_PROFILE) )
    $profile = &$AVREG_PROFILE;

function print_messages()
{
   $_cmd = $GLOBALS['conf']['sudo'] . ' ' .
           $GLOBALS['conf']['tail'] . ' -n 200 ' . $GLOBALS['conf']['daemon-log'] .
           ' | ' . $GLOBALS['conf']['grep'] . ' ' . $GLOBALS['conf']['daemon-name'] .
           ' | ' . $GLOBALS['conf']['tail'] . ' -n 50';

   $logfile = popen($_cmd.' 2>&1', 'r');
   print '<div class="tty">'."\n";
   print '<span style="color: #66FF00;">' . '$ '. htmlspecialchars(str_replace(array('/bin/','/usr'),'',$_cmd),ENT_QUOTES, $chset) . '</span><br>'. "\n";
   while (!feof ($logfile))
   {
      $buffer = fgets($logfile, 1024);
		if ( preg_match('/crit|err|fail|invalid|bad|unable|warn|notice|could`t|cannot|can`t|not|duplicate|reset|reject|drop|unsupport/i', $buffer) )
			print '<font color="#FFFF99">'.htmlspecialchars($buffer,ENT_QUOTES, $chset).'</font><br>';
		else
		    print htmlspecialchars($buffer,ENT_QUOTES, $chset).'<br>';
	}
	pclose($logfile);
	print '</div><br />'."\n";
}

echo '<h1>' . $r_control . '</h1>' ."\n";

$cmd_released=NULL;

if (isset($cmd)) {

  if ( isset($profile))
     exec($GLOBALS['conf']['sudo'].' '.$GLOBALS['conf']['daemon'].' status '. $profile, $outs, $retval);
  else
     exec($GLOBALS['conf']['sudo'].' '.$GLOBALS['conf']['daemon'].' status', $outs, $retval);
  
  $srun = ($retval === 0)?true:false;

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
                        $fullcmd = $conf['daemon'] . ' ' . $cmd;
                        if (isset($profile))
                           $fullcmd .= ' ' . $profile;
			print_syslog (LOG_WARNING, sprintf ('command `%s\'', $fullcmd));
			unset($outs);
			exec($conf['sudo'] . ' ' . $fullcmd . ' 2>&1', $outs, $retval);
			if ( $retval === 0 ) {
				print 'OK</font></p>' ."\n";
				$cmd_released=TRUE;
				// print '<p>' . $outline . '</p>' ."\n";
			} else {
				print $strError. '</font></p>' ."\n";
				print '<p><font size="+1" color="Red">' . implode('<br />',$outs) . '</font></p>' ."\n";
				$cmd_released=FLASE;
				print '<div style="color:Red;">'.$strCheckLog.'</div>';
				print_messages();
			}
			usleep(300000);
                        exec($GLOBALS['conf']['sudo'].' '.$GLOBALS['conf']['daemon'].' status', $outs, $retval);
                        $srun = ($retval === 0)?true:false;
         		}
		}
	} else {
    if ( $cmd == 'start' ) {
 		print '<p class="HiLiteBigWarn">' . sprintf ($fnmWarnControl,$strRunW) . '</p>' ."\n";
	} elseif ( $cmd == 'restart' ) {
        print '<p class="HiLiteBigWarn">' . $sViewerRestartWarn . '</p>' ."\n";
		print '<p class="HiLiteBigWarn">' . sprintf ($fnmWarnControl,$strRestartW) . '</p>' ."\n";
/*
	} elseif ( $cmd == 'condrestart' ) {
		$strwarning = $strCondRestartW;
*/
	} elseif ( $cmd == 'reload' ) {
        print '<p class="HiLiteBigWarn">' . $strReloadW.'</p>' ."\n";
	} elseif ( $cmd == 'snapshot' ) {
 		print '<p class="HiLiteBigWarn">'. $strSnapshotW .'</p>' ."\n";
        } elseif ( $cmd == 'stop' ) {
 		print '<p class="HiLiteBigWarn">' . sprintf ($fnmWarnControl,$strStopW) .'</p>' ."\n";
	} else {
       MYDIE("invalid command");
    }
        print '<div class="warn">' ."\n";
        if ( isset($profile))
           echo '# ' . $GLOBALS['conf']['daemon'].' '.$cmd . ' ' . $profile . '  [?]';
        else
           echo '# ' . $GLOBALS['conf']['daemon'].' '.$cmd . '  [?]';
        print '</div><br />' ."\n";
	print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
	print '<input type="hidden" name="cmd" value="'.$cmd.'">'."\n";
	print '<input type="submit" name="confirm_btn" value="'.$strYes.'">'."\n";
        if (isset($profile))
	  print '<input type="hidden" name="profile" value="'.$profile.'">'."\n";
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
echo '<h2>'."\n";
if ( isset($AVREG_PROFILE) ) {
  $sel_profile = &$AVREG_PROFILE;
  echo $r_conrol_control . ' &#171;'.$conf['daemon-name'].'&#187;';
} else {
   $AVREG_PROFILES = array_keys($DAEMONS_STATES);
   $sel_profile = ( isset($profile) && !empty($profile) )? $profile:$AVREG_PROFILES[0];
   if (empty($sel_profile))
      echo $r_conrol_control . ' ' . '&#171;'.$conf['daemon-name'].'&#187;';
   else
     echo $r_conrol_control . 'avregd-'. getSelectHtmlByName('profile',
          array_keys($DAEMONS_STATES), FALSE, 1, 0, $sel_profile, FALSE, TRUE);
}

echo '</h2>' ."\n";
if ( ! $DAEMONS_STATES[$sel_profile] )
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
