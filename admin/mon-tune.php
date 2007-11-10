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
echo '<h2>' . sprintf($r_mon_tune,$mon_nr,$mon_name) . '</h2>' ."\n";

if ( isset($mon_nr) && isset($mon_name) && isset($mon_type) )
{
	settype($mon_nr,'int');
	if (isset($cmd))
    {
		switch ( $cmd )	{
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
 					$query = sprintf('REPLACE INTO MONITORS '.
							 '(BIND_MAC, MON_NR, MON_TYPE, MON_NAME, %s, CHANGE_HOST, CHANGE_USER) '.
	 						 'VALUES (\'local\', %d, \'%s\', \'%s\', %s, \'%s\', \'%s\')',
							  implode (', ',$fWINS),
                       $mon_nr, $mon_type, $mon_name,
                       implode (', ',$vWINS), $remote_addr, $login_user);
				mysql_query($query) or die('Query failed: `'. $query . '`'.'<br/><br/>'. mysql_error() );
                    print '<p class="HiLiteBigWarn">' . sprintf($r_mon_changed,$mon_nr, $mon_name) . '</p>'."\n";
                    print '<center><a href="'.$conf['prefix'].'/admin/mon-list.php" target="_self">'.$r_mon_goto_list.'</a></center>'."\n";
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
       // cmd not set
       require('active_pipe.inc.php');
       $wins_array = &$active_pipes;
       if ( count($wins_array) > 0 )
       {
        $aaa = array();
       /* Performing new SQL query */
       $query = 'SELECT MON_NR, MON_TYPE, MON_NAME, IS_DEFAULT, ' .
       'WIN1, WIN2, WIN3, WIN4, WIN5, WIN6, WIN7, WIN8, WIN9, WIN10, WIN11, WIN12, WIN13, WIN14, WIN15,
       WIN16, '.
       'CHANGE_HOST, CHANGE_USER, CHANGE_TIME '.
       'FROM MONITORS '.
       'WHERE BIND_MAC=\'local\' '.
       'AND MON_NR='.$mon_nr;
       	
        $result = mysql_query($query) or die('Query failed: `'. $query . '`');
        if (is_null($result)) die('No result');
        $row = mysql_fetch_row($result);
        for ($i=4; $i<20; $i++)
        {
           $a = getSelectHtmlByName('mon_wins[]',$wins_array, FALSE , 1, 1, $row[$i], TRUE, 'sel_change(this);','cam ');
           array_push($aaa, $a );
        }
        /* Free last resultset */
        mysql_free_result($result);
        $result = NULL;
        
        // print "<pre><code>".var_dump($aaa)."</code></pre>";
        print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST"  onSubmit="return validate();">'."\n";
        print '<p class="HiLiteBigWarn">' . $strMonAddInfo2 . '</p>' ."\n"; 
        print '&nbsp;&nbsp;&nbsp;'.$strName.': <input type="text" name="mon_name" size=16 maxlength=16 value="'.$mon_name.'">'."\n";    
        show_mon_type ( $mon_type, 400, $aaa);
        print '<input type="hidden" name="cmd" value="_ADD_NEW_MON_OK_">'."\n";
        print '<input type="hidden" name="mon_nr" value="'.$mon_nr.'">'."\n";
        print '<input type="hidden" name="mon_type" value="'.$mon_type.'">'."\n";
        print '<br><input type="submit" name="btn" value="'.$strSave.'">'."\n";
        print '<input type="reset" name="btn" value="'.$strRevoke.'" onclick="reset_to_list();">'."\n";
        print '</form>'."\n";
     }
    }
 
} else {
	print '<p><font color="' . $error_color . '">' . $strMonAddErr1 . '</font></p>' ."\n";
	unset ($mon_nr);
}

// phpinfo ();
require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
