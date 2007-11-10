<?php
    /* Connecting, selecting database */
    if ($link === null)
    {
      $link = mysql_connect($conf['db-host'], $conf['db-user'], $conf['db-passwd'])
          or die("Could not connect to mysql database");
      mysql_select_db($conf['db-name']) or die('Could not select database "'.$conf['db_name'].'"');

      mysql_query( "SET NAMES 'utf8' COLLATE 'utf8_general_ci'", $link );
    }
?>
