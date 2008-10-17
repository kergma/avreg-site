<?php
require ('../head.inc.php');

DENY($admin_status);

require ('../lib/my_conn.inc.php');
require ('./mon-type.inc.php');

?>

<script type="text/javascript" language="javascript">
<!--
function reset_to_list()
{
	window.open('<?php echo $conf['prefix']; ?>/admin/mon-list.php', target='_self');
}
// -->
</script>

<?php

echo '<h1>' . sprintf($r_mons,$named,$sip) . '</h1>' ."\n";

if ( isset($cmd) )
{
	if ( isset($mon_nr) && isset($mon_name) && isset($mon_type) )
	{
		switch ( $cmd )	{
			case '_ADD_NEW_MON_':
                    require('active_pipe.inc.php');
					$wins_array = &$active_pipes;
					if ( count($wins_array) > 0 ) {
						print '<p class="HiLiteBigWarn">' . 
                           sprintf ($fmtMonAddInfo,$mon_nr, $mon_name) . '</p>' ."\n";
						print '<p class="HiLiteBigWarn">' . $strMonAddInfo2 . '</p>' ."\n";
						$a = getSelectHtmlByName('mon_wins[]',
                              $wins_array, FALSE , 1, 1, '',
                                  TRUE, 'sel_change(this);','cam ');
                        //print('<pre><code>');print_r($a);print('</code></pre>');
						print '<form action="'.$_SERVER['PHP_SELF'].
                        '"  onSubmit="return validate();" method="POST">'."\n";
						layout2table ( $mon_type, ($mon_type == 'QUAD_25_25')? 500:400, NULL,  $a);
						print '<input type="hidden" name="cmd" value="_ADD_NEW_MON_OK_">'."\n";
	    				print '<input type="hidden" name="mon_nr" value="'.$mon_nr.'">'."\n";
						print '<input type="hidden" name="mon_name" value="'.$mon_name.'">'."\n";
						print '<input type="hidden" name="mon_type" value="'.$mon_type.'">'."\n";
						print '<br><input type="submit" name="btn" value="'.$strSave.'">'."\n";
						print '<input type="reset" name="btn" value="'.
                           $strRevoke.'" onclick="reset_to_list();">'."\n";
						print '</form>'."\n";
					}
					require ('../foot.inc.php');
					exit;
				break; /**/

			case '_ADD_NEW_MON_OK_':
				$i = 0;
				$fWINS = array();
				$vWINS = array();
				while ( $i < count($mon_wins) ) {
					if ( !empty( $mon_wins[$i] ) ) {
						array_push( $fWINS, 'WIN'.($i+1) );
						array_push( $vWINS, $mon_wins[$i] );
					}
					$i++;
				}
				if ( count( $fWINS ) > 0 )
				{
				/*
					$query = 'INSERT INTO MONITORS '.
							 '(SERV_IP, MON_NR, MON_TYPE, MON_NAME, '.implode (', ',$fWINS).', CHANGE_HOST, CHANGE_USER, CHANGE_TIME) '.
	 						 "VALUES (ip2long($sip), $mon_nr, '$mon_type', '$mon_name', ".implode (', ',$vWINS).", '$remote_addr', '$login_user', NOW());";
				*/
 					$query = sprintf('INSERT INTO MONITORS '.
							 '(BIND_MAC, MON_NR, MON_TYPE, MON_NAME, %s, CHANGE_HOST, CHANGE_USER) '.
	 						 'VALUES (\'local\', %d, \'%s\', \'%s\', %s, \'%s\', \'%s\')',
							 implode (', ',$fWINS), $mon_nr, $mon_type, $mon_name, implode (', ',$vWINS), $remote_addr, $login_user);
					mysql_query($query) or die('Query failed: `'. $query . '`');
					unset ($mon_nr);
				} else {
					print '<p class="HiLiteBigErr">' . $strNotChoiceCam . '</p>' ."\n";
                    print_go_back();
					require ('../foot.inc.php');
					exit;
				}
				break;
			} // switch
	} else {
		print '<p class="HiLiteBigErr">' . $strMonAddErr1 . '</p>' ."\n";
		unset ($mon_nr);
	}
} // if isset cmd

echo '<h2>' . $r_mon_addnew . '</h2>' ."\n";

if ( !isset($mon_nr) || $mon_nr =='')
{
	/* Performing new SQL query */
	$query = 'SELECT MON_NR FROM MONITORS WHERE BIND_MAC=\'local\' ORDER BY MON_NR';
	$result = mysql_query($query) or die('Query failed: `'. $query . '`');
	$num_rows = mysql_num_rows($result);
	$all_mons = array(1,1,1,1,1,1,1,1,1,1);
	$r_count = 0;
	if ( $num_rows > 0 )
	{
		while ( $row = mysql_fetch_array($result, MYSQL_ASSOC) )
		{
			if ( ($row['MON_NR'] >=  0)  && ($row['MON_NR'] <=  9) )
				$all_mons[$row['MON_NR']] = 0;
			$r_count++;
		}
	}
	$ii = 0;
	$allow_mons = array();
	while ($ii < count($all_mons)) {
		if ($all_mons[$ii] > 0) array_push ($allow_mons, $ii);
		$ii++;
	}
	if ( count($allow_mons) > 0 ) {
		print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
		print getSelectHtmlByName('mon_nr',$allow_mons, FALSE , 1, 0, $allow_mons[0], FALSE, FALSE, $left_monitors . ' ') . "\n";
		print '&nbsp;&nbsp;&nbsp;'.$strNamed.': <input type="text" name="mon_name" size=16 maxlength=16 value="">'."\n";
		$wins = range(1, 25);

?>
<br><br>
<table cellspacing="0" border="1" cellpadding="5">
<tr bgcolor="#f4f0f4">
   <td><input type="radio" name="mon_type" value="ONECAM"><?php echo $strONECAM ; ?></td>
   <td><input type="radio" name="mon_type" value="QUAD_4_4"><?php echo $strQUAD_4_4 ; ?></td>
   <td><input type="radio" name="mon_type" value="MULTI_6_9"><?php echo $strMULTI_6_9 ; ?></td>
   <td><input type="radio" name="mon_type" value="MULTI_7_16"><?php echo $strMULTI_7_16 ; ?></td>
   <td><input type="radio" name="mon_type" value="MULTI_8_16"><?php echo $strMULTI_8_16 ; ?></td>
</tr>
<tr>
   <td><?php layout2table ( 'ONECAM', 160, $wins ); ?></td>
   <td><?php layout2table ( 'QUAD_4_4', 160, $wins ); ?></td>
   <td><?php layout2table ( 'MULTI_6_9', 160, $wins ); ?></td>
   <td><?php layout2table ( 'MULTI_7_16', 160, $wins ); ?></td>
   <td><?php layout2table ( 'MULTI_8_16', 160, $wins ); ?></td>
<tr bgcolor="#f4f0f4">
   <td><input type="radio" name="mon_type" value="QUAD_9_9"><?php echo $strQUAD_9_9 ; ?></td>
   <td><input type="radio" name="mon_type" value="MULTI_10_16"><?php echo $strMULTI_10_16 ; ?></td>
   <td><input type="radio" name="mon_type" value="MULTI_13_16"><?php echo $strMULTI_13_16 ; ?></td>
   <td><input type="radio" name="mon_type" value="MULTI_13_25"><?php echo $strMULTI_13_25 ; ?></td>
   <td><input type="radio" name="mon_type" value="QUAD_16_16"><?php echo $strQUAD_16_16 ; ?></td>
</tr>
<tr>
   <td><?php layout2table ( 'QUAD_9_9', 160, $wins ); ?></td>
   <td><?php layout2table ( 'MULTI_10_16', 160, $wins ); ?></td>
   <td><?php layout2table ( 'MULTI_13_16', 160, $wins ); ?></td>
   <td><?php layout2table ( 'MULTI_13_25', 160, $wins ); ?></td>
   <td><?php layout2table ( 'QUAD_16_16', 160, $wins ); ?></td>
</tr>
<tr bgcolor="#f4f0f4">
   <td><input type="radio" name="mon_type" value="MULTI_16_25"><?php echo $strMULTI_16_25 ; ?></td>
   <td><input type="radio" name="mon_type" value="MULTI_17_25"><?php echo $strMULTI_17_25 ; ?></td>
   <td><input type="radio" name="mon_type" value="MULTI_19_25"><?php echo $strMULTI_19_25 ; ?></td>
   <td><input type="radio" name="mon_type" value="MULTI_22_25"><?php echo $strMULTI_22_25 ; ?></td>
   <td><input type="radio" name="mon_type" value="QUAD_25_25"><?php echo $strQUAD_25_25 ; ?></td>
</tr>
<tr>
   <td><?php layout2table ( 'MULTI_16_25', 160, $wins ); ?></td>
   <td><?php layout2table ( 'MULTI_17_25', 160, $wins ); ?></td>
   <td><?php layout2table ( 'MULTI_19_25', 160, $wins ); ?></td>
   <td><?php layout2table ( 'MULTI_22_25', 160, $wins ); ?></td>
   <td><?php layout2table ( 'QUAD_25_25', 160, $wins ); ?></td>
</tr>
</table>
<br>
<?php
		print '<input type="hidden" name="cmd" value="_ADD_NEW_MON_">'."\n";
		print '<input type="submit" name="btn" value="'.$l_mon_addnew.'">'."\n";
		print '<input type="reset" name="btn" value="'.$strRevoke.'" onclick="reset_to_list();">'."\n";
 		print '</form>'."\n";
	} else {
		print '<p class="HiLiteBigErr">' . $strMonNrLimit . '</p>' ."\n";
	}
}

// phpinfo ();
require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
