<?php
	session_start();
	//if session is already exist -> user cant login again
	if(isset($_SESSION['loggedIN'])){
		header('Location: ../index.php');//перенаправляем на страничку
		exit();
	}
	if(isset($_POST['what'])){
		$conn = mysqli_connect("localhost", "root", "root", "clothingstore");
		// $login = mysql_real_escape_string($_POST['loginPHP']);
		// $password = mysql_real_escape_string($_POST['passwordPHP']);
		$login = $_POST['loginPHP'];
		$password = $_POST['passwordPHP'];

		if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    	}
    	mysqli_set_charset($conn, "utf8");

		$sql = "SELECT * FROM user WHERE login = '$login' and password = '$password'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		if (mysqli_num_rows($result) > 0) {
		$_SESSION['loggedIN']='1';
		$_SESSION['sessionuseridrole']=$row['useridrole']; //+ save login
		
		$_SESSION['sessionuserlogin']=$login; //+ save login
		$_SESSION['sessionusername']=$row['username']; //+ save his name
		$_SESSION['sessionuserid']=$row['iduser']; //сохраняем в сессии id клиента
        exit('<font color="green">Login success!</font>') ;
    	} else {
         exit('<font color="red">Login failed!</font>') ;
    	}
    	mysqli_close($conn);
	}

?>

