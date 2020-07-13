<?php
	session_start();
	if(isset($_SESSION['loggedIN'])){
		$hellouser=$_SESSION['sessionusername'];

		// exit();
	}


?>
<style>
        header{
        display: flex;
        justify-content: space-between;
        padding: 10px;
        background:#296929;
        color:#fff;
    }
    .header__section{
    display: flex;
    align-items: center;
    }
    a{
        text-decoration: none;
        color: #fff;
    }
    .header__item{
        padding: 10px 15px;
        font-size: 20px;
        margin-left: 5px;
    }
    .header__item:hover{
        background:#418141;
        border-radius: 4px;
    }
    .headerlogo{
        font-weight: bold;
    }
    .headerButton{
        cursor: pointer;
    }
</style>
		<header>
			<div class="header__section">
				<div class="header__item headerlogo">EcA</div>
				<div class="header__item headerButton"><a href="index.php">Главная</a></div>
				<div class="header__item headerButton"><a href="cart.php">Корзина</a></div>
				<div class="header__item headerButton"><a href="profile.php">Личный кабинет</a></div>
                <div class="header__item headerButton"><a href="admin.php">Страничка админа</a></div>
			</div>
				<div class="header__section">
                <div class="header__item ">Здраствуйте <?php echo $hellouser;?></div>
				<div class="header__item headerButton"><a href="login.php">Войти</a></div>
				<div class="header__item headerButton"><a href="logout.php">Выйти</a></div>
			</div>
		</header>


