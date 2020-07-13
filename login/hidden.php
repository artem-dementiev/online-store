<?php
	session_start();
	if(!isset($_SESSION['loggedIN'])){
		header('Location: login.php');
		exit();
	}


?>
<a href="logout.php">log out</a>
You are logged in!