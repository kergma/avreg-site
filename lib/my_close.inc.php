<?php
     /* Free resultset */
    if (!empty($result)) {mysql_free_result($result);}

    /* Closing connection */
    if (!empty($link)) {mysql_close($link);}
?>