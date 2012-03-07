<?php
    /* Connecting, selecting database */
    if ($link === null)
    {
      $link = mysql_connect($conf['db-host'], $conf['db-user'], $conf['db-passwd'])
          or die('Could not connect to mysql server: ' . mysql_error());
      mysql_select_db($conf['db-name']) or die('Could not select database &#171;'.$conf['db-name'].'&#187;: ' . mysql_error());

      mysql_query( "SET NAMES 'utf8' COLLATE 'utf8_general_ci'", $link );
    }
?>
