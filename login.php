<?php
	session_start();
	if(isset($_SESSION['loggedIN'])){
		header('Location: index.php');
	}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>loginPage</title>
	<link rel="stylesheet" href="css/style.css">
	<style>
		body{
	background: #eee;
	padding:0;
	margin: 0;
}

#frm{
	border: 1px solid black;
	width: 20%;
	border-radius: 5px;
	margin:100px auto;
	background: white;
	padding: 50px;
}
#loginbtn{
	color: #fff;
	background: #337ab7;
	padding: 5px;
	margin-left: 69%;

}
	</style>
</head>
<body>
	<?php require_once "header.php" ?>
	<div id="frm">
		<form action="login/loginquery.php" method="POST">
			<p>
				<label>Username: </label>
				<input type="text" id="login" name="login" placeholder="login" />
			</p>
			<p>
				<label>Password: </label>
				<input type="password" id="password" name="pass" placeholder="password" />
			</p>
			<p>
				<input type="button" id="loginbtn" value="LOG IN" />
			</p>
				<p id="response"></p>
		</form>
	</div>

	<script src="js/jquery-3.2.1.min.js"></script>
	<script>
		$(document).ready(function (){
			$("#loginbtn").on('click', function(){
				var login = $("#login").val();
				var password = $("#password").val();

				if(login!="" && password!=""){
					$.post(
							'login/loginquery.php',{
								"what": 1,
								"loginPHP": login,
								"passwordPHP": password
							}, function(response){
								console.log(response);
								$("#response").html(response);
								if(response.indexOf('success') >= 0){
									//window.location.assign("hidden.php");
									window.location.assign("index.php");
								}
							}
						
					);
				}



			});
		});







	</script>
</body>
</html>