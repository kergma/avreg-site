<?php
header('Location: http://v-serv/');
header('WWW-Authenticate: Basic realm="VServ"', TRUE);
header('HTTP/1.0 401 Authorization Required');
?>

