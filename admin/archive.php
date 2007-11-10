<?php
require ('../head.inc.php');
require ('../lib/my_conn.inc.php');

echo '<h1>' . sprintf($r_archive, $named, $sip) . '</h1>' ."\n";

if ( isset($cmdSave) )
{
	DENY($admin_status);
	$query = sprintf('REPLACE INTO ARCH_CLEAN '.
		'(BIND_MAC, PERIOD, MIN1, HOUR2, MIN2, START_SPACE1, STOP_SPACE1, START_SPACE2, STOP_SPACE2, '.
/*    	'FILES_PER_ONE1, FILES_PER_ONE2, '. */
		'CHANGE_HOST, CHANGE_USER) '.
		'VALUES (\'local\', %u, %u, %u, %u, %u, %u, %u, %u, \'%s\', \'%s\')',
		$period_type, $min1, $hour2, $min2,
        $start_space1, $stop_space1, $start_space2, $stop_space2,
        $remote_addr, $login_user);
	mysql_query($query) or die('Query failed: `'. $query . '`');
	
	$crontab = popen ('/usr/bin/sudo /usr/local/sbin/setcron -f /etc/cron.d/archclrn', 'w');
	if ( $period_type == 0 ) {
		$time_par = sprintf('%02d *',$min1);
		$cmd_param = sprintf(' -s %d -p %d -d 1000 -t 2',$start_space1, $stop_space1);
	} else {
		$time_par = sprintf('%02d %02d',$min2,$hour2);
		$cmd_param = sprintf(' -s %d -p %d -d 1000 -t 2',$start_space2, $stop_space2);
	}
	fwrite($crontab, sprintf('%s * * * root /usr/sbin/archclrn %s', $time_par, $cmd_param));
	pclose ($crontab);
    $outline = exec($conf['sudo'].' '.$conf['conf'].' restart', $outs, $retval);
    if ( $retval !== 0 ) {
       print '<p><font size="+1" color="Red">' . $outline . '</font></p>' ."\n";
    }
}
{
	echo '<h2>' . $r_arc_cleaner . '</h2>' ."\n";
   	
    require('warn.inc.php');

    print '<p>'.$strPrim1.'</p><ol>'."\n";
	print '<li>' . $r_arc_check_period . '</li>'."\n";
	print '<li>' . $r_arc_check_time . '</li>'."\n";
	print '<li>' . $r_arc_min_space_start . '</li>'."\n";
	print '<li>' . $r_arc_min_space_stop . '</li>'."\n";
    // print '<li>' . $r_arc_FilesPerOnce . '</li>'."\n";
    
	print '</ol>'."\n";

    echo '<div class="warn">' . $r_ach_help . '</div><br />' ."\n";
    
	/* Performing new SQL query */
	$query = 'SELECT  PERIOD,  MIN1, HOUR2, MIN2, START_SPACE1, STOP_SPACE1, START_SPACE2, STOP_SPACE2, '.
/*    		 'FILES_PER_ONE1, FILES_PER_ONE2, '. */
			 'CHANGE_HOST, CHANGE_USER, CHANGE_TIME '.
			 'FROM ARCH_CLEAN WHERE BIND_MAC=\'local\'';
	$result = mysql_query($query) or die('Query failed: `'. $query . '`');
	$num_rows = mysql_num_rows($result);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$p1 ='';
	$p2 ='';
	if ( $row )
	{
		if ( $row['PERIOD'] )
			$p2 = 'checked';
		else
			$p1 = 'checked';
	}
	if ( $arch_user )
		print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
	print '<table cellspacing="0" border="1" cellpadding="5">'."\n";
	print '<tr bgcolor="'.$header_color.'">'."\n";
	print '<th>'.$srt_arc_period.'&nbsp;<sup>(1)</sup></th>'."\n";
	print '<th colspan="2">'.$srt_arc_time.'&nbsp;<sup>(2)</sup></th>'."\n";
	print '<th>'.$srt_start_clean_space.'&nbsp;<sup>(3)</sup></th>'."\n";
	print '<th>'.$srt_stop_clean_space.'&nbsp;<sup>(4)</sup></th>'."\n";
    // print '<th>'.$strFilesPerOnce.'&nbsp;<sup>(5)</sup></th>'."\n";
	print '<th>'.$strUpdateControl.'</th>'."\n";
	print '</tr>'."\n";
	if ( $p1 )
		print "<tr bgcolor=\"$rowHiLight\">\n";
	else
		print '<tr>'."\n";
	print '<td><input '.$p1.' type="radio" name="period_type" value="0">'.$every_hour.'</td>'."\n";
	print '<td align="center">*</td>'."\n";
	print '<td align="center">'.$strMinuteMin.':&nbsp;'.getSelectHtmlByName('min1', $minute_array, FALSE, 1, 0, $row['MIN1'], FALSE, FALSE).'</td>'."\n";
	print '<td align="center">&lt;&nbsp;'.getSelectHtmlByName('start_space1', $min_disk_space_array1, FALSE, 1, 0, $row['START_SPACE1'], FALSE, FALSE).'&nbsp;'.$byteUnits[3].'</td>'."\n";
	print '<td align="center">+&nbsp;'.getSelectHtmlByName('stop_space1', $min_disk_space_array1, FALSE, 1, 0, $row['STOP_SPACE1'], FALSE, FALSE).'&nbsp;'.$byteUnits[3].'</td>'."\n";
	// print '<td align="center">'.getSelectHtmlByName('fpo1', $delFilePerOnce, FALSE, 1, 0, $row['FILES_PER_ONE1'], FALSE, FALSE).'</td>'."\n";
   
	$ts = $row['CHANGE_TIME'];
	$ut = mktime (substr($ts,8,2),substr($ts,10,2), substr($ts,12,2), substr($ts,4,2), substr($ts,6,2) ,substr($ts,0,4));
	print '<td>'. $row['CHANGE_HOST'] . '<br>' .$row['CHANGE_USER'] . '<br>' . strftime ( "%d.%m.%y %H:%M" , $ut) .'</td>' . "\n";
	print '</tr>'."\n";
	if ( $p2 )
		print "<tr bgcolor=\"$rowHiLight\">\n";
	else
		print '<tr>'."\n";
	print '<td><input type="radio"  '.$p2.'  name="period_type"  value="1">'.$every_day.'</td>'."\n";
	print '<td align="center">'.$strHourMin.':&nbsp;'.getSelectHtmlByName('hour2', $hour_array, FALSE, 1, 0, $row['HOUR2'], FALSE, FALSE).'</td>'."\n";
	print '<td align="center">'.$strMinuteMin.':&nbsp;'.getSelectHtmlByName('min2', $minute_array, FALSE, 1, 0, $row['MIN2'], FALSE, FALSE).'</td>'."\n";
	print '<td align="center">&lt;&nbsp;'.getSelectHtmlByName('start_space2', $min_disk_space_array2, FALSE, 1, 0, $row['START_SPACE2'], FALSE, FALSE).'&nbsp;'.$byteUnits[3].'</td>'."\n";
	print '<td align="center">+&nbsp;'.getSelectHtmlByName('stop_space2', $min_disk_space_array2, FALSE, 1, 0, $row['STOP_SPACE2'], FALSE, FALSE).'&nbsp;'.$byteUnits[3].'</td>'."\n";
	// print '<td align="center">'.getSelectHtmlByName('fpo2', $delFilePerOnce, FALSE, 1, 0, $row['FILES_PER_ONE2'], FALSE, FALSE).'</td>'."\n";
	print '<td>'. $row['CHANGE_HOST'] . '<br>' .$row['CHANGE_USER'] . '<br>' . strftime ( "%d.%m.%y %H:%M" , $ut) .'</td>' . "\n";
	print '</tr>'."\n";
	print '</table>'."\n";
	if ($arch_user)
	{
		if ( !$row )
			print '<input type="hidden" name="flag_first" value="1">'."\n";
		print '<br><input type="submit" name="cmdSave" value="'.$strSave.'">'."\n";
	}
	if ($arch_user)
		print '</form>'."\n";
}

// phpinfo ();
require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
