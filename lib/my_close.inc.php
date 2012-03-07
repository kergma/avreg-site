<?php

/* Free resultset */
if ( isset($result) && !is_null($result) )  {
   mysql_free_result($result);
   unset($result);
}

/* Closing connection */
if ( isset($link) && !is_null($link) ) {
   mysql_close($link);
   unset($link);
}
?>
