<?php
if (isset($_POST['pipes_show']))
   $pipes_show = $_POST['pipes_show'];
if ( isset($pipes_show) ) {
   settype($pipes_show, 'int');
   setcookie('avreg_pipes_show',  $pipes_show, time()+5184000);
} else if ( isset($_COOKIE['avreg_pipes_show']) ) {
   $pipes_show = (Integer)$_COOKIE['avreg_pipes_show'];
} else {
   $pipes_show = 1;
}
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

if ( !isset($mon_nr) || $mon_nr =='' || empty($display) )
   die('empty $mon_nr and/or $display');

if (!settype($mon_nr,'int'))
   die('$mon_nr is\'t integer value');

if ($mon_nr < 0 || $mon_nr > 9)
   die('$mon_nr in\'t in [0..9]');

if ( !($display == 'L' || $display == 'R') )
   die('$display must be L or R char');

echo '<h2>' . sprintf($r_mon_tune, $mon_nr, $mon_name, $display=='R'?$sRightDisplay1:$sLeftDisplay1) . '</h2>' ."\n";

if (isset($cmd)) {
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
      if ( count( $fWINS ) > 0 )	{
         $query = sprintf('REPLACE INTO MONITORS '.
            '(BIND_MAC, DISPLAY, MON_NR, MON_TYPE, MON_NAME, %s, CHANGE_HOST, CHANGE_USER) '.
            'VALUES (\'local\', \'%s\', %d, \'%s\', \'%s\', %s, \'%s\', \'%s\')',
               implode (', ',$fWINS), $display, $mon_nr, $mon_type, $mon_name,
               implode (', ',$vWINS), $remote_addr, $login_user);
         mysql_query($query) or die('Query failed: `'. $query . '`'.'<br/><br/>'. mysql_error() );
         print '<p class="HiLiteBigWarn">' . sprintf($r_mon_changed, $mon_nr, empty($mon_name)?$mon_type:$mon_name, $display=='R'?$sRightDisplay1:$sLeftDisplay1) . '</p>'."\n";
         print '<center><a href="'.$conf['prefix'].'/admin/mon-list.php" target="_self">'.$r_mon_goto_list.'</a></center>'."\n";
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
   if ( count($wins_array) == 0 ) {
      print '<p class="HiLiteBigErr">' . $strNotViewCams  . '</p>' ."\n";
      print_go_back();
      require ('../foot.inc.php');
      exit;
   } else {
      $aaa = array();
      /* Performing new SQL query */
      $query = 'SELECT MON_NR, MON_TYPE, MON_NAME, IS_DEFAULT, ' .
         'WIN1, WIN2, WIN3, WIN4, WIN5, WIN6, WIN7, WIN8, WIN9, WIN10, WIN11, WIN12, WIN13, WIN14, WIN15, WIN16, WIN17, WIN18, WIN19, WIN20, WIN21, WIN22, WIN23, WIN24, WIN25, '.
         'CHANGE_HOST, CHANGE_USER, CHANGE_TIME '.
         'FROM MONITORS '.
         'WHERE BIND_MAC=\'local\' AND DISPLAY=\''.$display.'\' AND MON_NR='.$mon_nr;

      $result = mysql_query($query) or die('Query failed: `'. $query . '`');
      if (is_null($result)) die('No result');
      $row = mysql_fetch_row($result);
      for ($i=4; $i<29; $i++) {
         $a = getSelectHtmlByName('mon_wins[]',$wins_array, FALSE , 1, 1, $row[$i], TRUE, 'sel_change(this);');
         array_push($aaa, $a );
      }
      /* Free last resultset */
      mysql_free_result($result);
      $result = NULL;

      // print "<pre><code>".var_dump($aaa)."</code></pre>";
      print '<form action="'.$_SERVER['PHP_SELF'].'" method="POST"  onSubmit="return validate();">'."\n";
      print '<p class="HiLiteBigWarn">' . $strMonAddInfo2 . '</p>' ."\n";
      print '&nbsp;&nbsp;&nbsp;'.$strName.': <input type="text" name="mon_name" size=16 maxlength=16 value="'.$mon_name.'">'."\n";
      layout2table ( $mon_type, ($mon_type == 'QUAD_25_25')? 400:300, $aaa);
      print '<input type="hidden" name="cmd" value="_ADD_NEW_MON_OK_">'."\n";
      print '<input type="hidden" name="display" value="'.$display.'">'."\n";
      print '<input type="hidden" name="mon_nr" value="'.$mon_nr.'">'."\n";
      print '<input type="hidden" name="mon_type" value="'.$mon_type.'">'."\n";
      print '<br><input type="submit" name="btn" value="'.$strSave.'">'."\n";
      print '<input type="reset" name="btn" value="'.$strRevoke.'" onclick="reset_to_list();">'."\n";
      print '</form>'."\n";
   }
}
// phpinfo ();
require ('../lib/my_close.inc.php');
require ('../foot.inc.php');
?>
