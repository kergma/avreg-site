<?php
	
	if(isset($_GET['user'])){
		$as_guest = trim($_GET['user']);
	}else{
		$as_guest = 'guest';
	}
	
	header("location:index.php?user=".$as_guest);
	exit();
?>