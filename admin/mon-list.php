<?php
require ('../head.inc.php');
require_once ('../lib/my_conn.inc.php');
require ('./mon-type.inc.php');

echo '<h1>' . sprintf($r_mons,$named,$sip) . '</h1>' ."\n";

if ( isset($cmd) )
{
	DENY($admin_status);
	switch ( $cmd )	{
		case 'DEL':
			echo '<p class="HiLiteBigWarn">' . sprintf ($fmtDeleteMonConfirm, $mon_nr,$mon_name) . '</p>' ."\n";
			print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
			print '<input type="hidden" name="cmd" value="DEL_OK">'."\n";
    		print '<input type="hidden" name="mon_nr" value="'.$mon_nr.'">'."\n";
			print '<input type="hidden" name="mon_name" value="'.$mon_name.'">'."\n";
			print '<input type="submit" name="mult_btn" value="'.$strYes.'">'."\n";
			print '<input type="submit" name="mult_btn" value="'.$strNo.'">'."\n";
 			print '</form>'."\n";
			require ('../foot.inc.php');
			exit;
			break; /**/
		case 'DEL_OK':
			if ( ($mult_btn == $strYes) && isset($mon_nr) )
			{
				$query = sprintf('DELETE FROM MONITORS WHERE BIND_MAC=\'local\' AND MON_NR=%d',  $mon_nr);
				mysql_query($query) or die("Query failed");
				echo '<p><font color="' . $warn_color . '">' . sprintf ($strDeleteMon, $mon_nr,$mon_name) . '</font></p>' ."\n";
			}
			unset($mon_nr);
		break;
	}
}

echo '<h2>' . $r_mon_list . '</h2>' ."\n";

if ( !isset($mon_nr) || $mon_nr =='')
{
	/* Performing new SQL query */
	$query = 'SELECT MON_NR, MON_TYPE, MON_NAME, IS_DEFAULT, ' .
	'WIN1, WIN2, WIN3, WIN4, WIN5, WIN6, WIN7, WIN8, WIN9, WIN10, WIN11, WIN12, WIN13, WIN14, WIN15, WIN16, '.
	'CHANGE_HOST, CHANGE_USER, CHANGE_TIME '.
	'FROM MONITORS '.
	'WHERE BIND_MAC=\'local\' '.
	'ORDER BY MON_NR';
	$result = mysql_query($query) or die('Query failed: `'. $query . '`');
	$num_rows = mysql_num_rows($result);
	if ( $num_rows > 0 )
	{
	if ( $admin_user ) {
		echo '<div class="help">' . $strUpdateHint . '</div>' ."\n";
		/* Printing results in HTML */
		print '<p align="center"><a href="'.$conf['prefix'].'/admin/mon-addnew.php">'.$l_mon_addnew.'</a></p>'."\n";
	}
	print $tabletag . "\n";
	print '<tr bgcolor="'.$header_color.'">'."\n";
	if ( $admin_user ) {
        print '<th>&nbsp;</th>'."\n";
        print '<th>&nbsp;</th>'."\n";
    }
	print '<th>'.$strOrder.'<br>'.$strName.'</th>'."\n";
	print '<th>'.$strCamPosition.'</th>'."\n";
	print '<th>'.$strUpdateControl.'</th>'."\n";
	print '</tr>'."\n";
	
	$r_count = 0;
	while ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
	{
		// $cam_name = getCamName($row['CAM_NR']);
		$wins_array = array ($row['WIN1'],  $row['WIN2'],  $row['WIN3'],  $row['WIN4'],
							 $row['WIN5'],  $row['WIN6'],  $row['WIN7'],  $row['WIN8'],
							 $row['WIN9'],  $row['WIN10'], $row['WIN11'], $row['WIN12'],
							 $row['WIN13'], $row['WIN14'], $row['WIN15'], $row['WIN16']);
		$r_count++;
		print "<tr>\n";
		if ( $admin_user ) 
        {
			print '<td><a href="'.$_SERVER['PHP_SELF'].'?cmd=DEL&mon_nr='.$row['MON_NR'].'&mon_name='.$row['MON_NAME'].'">'. $strDelete . '</a></td>' . "\n";
			print '<td><a href="'.$conf['prefix'].'/admin/mon-tune.php?mon_nr='.$row['MON_NR'].'&mon_name='.$row['MON_NAME'].'&mon_type='.$row['MON_TYPE'].'">'. $strEdit . '</a></td>' . "\n";
        } 
		print '<td nowrap><b>'. $left_monitors . ' #' . $row['MON_NR'] . '<br>' . $row['MON_NAME'] .'</b></td>' . "\n";
       
		print '<td>'; show_mon_type ( $row['MON_TYPE'], 128, $wins_array ); print '</td>'. "\n";
		$ts = $row['CHANGE_TIME'];
		$ut = mktime (substr($ts,8,2),substr($ts,10,2),substr($ts,12,2), substr($ts,4,2), substr($ts,6,2) ,substr($ts,0,4));
		print '<td>'. $row['CHANGE_HOST'] . '<br>' .$row['CHANGE_USER'] . '<br>' . strftime ( "%d.%m.%y %H:%M" , $ut) .'</td>' . "\n";
		print "</tr>\n";
	}
	print "</table>\n";
	if ( $admin_user ) 
        print '<p align="center"><a href="'.$conf['prefix'].'/admin/mon-addnew.php">'.$l_mon_addnew.'</a></p>'."\n";
	} else {
		print '<p><b>' . $strNotMonDef . '</b></p>' . "\n";
		if ( $admin_user ) print '<p align="center"><a href="'.$conf['prefix'].'/admin/mon-addnew.php">'.$l_mon_addnew.'</a></p>'."\n";
	}
}

// phpinfo ();
require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
