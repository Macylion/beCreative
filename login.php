<?php
	session_start();
	ob_start();
	if(isset($_SESSION['isLoged'])){
		header('Location: index.php');
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf8">
	<meta name="description" content="Bądź kreatyny stwórz lepszy świat.">
	<meta name="author" content="Maciej Kubus">
	<meta name="keywords" content="kreatywność, creativity, lepszy, świat">
	<title>Zaloguj się | beCreative! | Bądź kreatyny stwórz lepszy świat</title>
	<link rel="stylesheet" href="css/styles.css">
	<link rel="shortcut icon" href="pics/favicon.png">
	<script type="text/javascript">
		function reg(){
			window.location.href = 'register.php';
		}
		function start(){
			$("#login").draggable();
			$("#aban").draggable();
		}
		function error(){}
	</script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body onload="error();start();" class="loginbody">
	<div id="menu">
		<a class="a1" href="index.php"><div class="textlogo">beCreative!</div></a>
		<div class="menu_righter" style="display: none;">
			<a class="a1" href="upload.php"><img class="iconmenu" src="pics/upload.png"></a>
			<a class="a1" href="about.php"><img class="iconmenu" src="pics/about.png"></a>
			<a class="a1" href="logout.php"><img class="iconmenu" src="pics/logout.png"></a>
			<a class="a1" href="profile.php?id='1'"><img class="avatarmenu" src="pics/bg.png" alt="avatar"></a>
		</div>
	</div>
	<?php 
		ob_start();
		if(isset($_POST['llogin'])){
			//Parse data
			$login = $_POST['llogin'];
			$pass = $_POST['lpass'];
			//Connect
			require_once("conn.php");
			require_once("lib.php");
			$sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
			$sql->query('SET CHARACTER SET utf8');
			//Query
			if($res = $sql->query("SELECT * FROM `users` WHERE `login` = '$login' ")){
				//Check number of rows
				if($res->num_rows == 1){
					$row = $res->fetch_row();
					if(password_verify($pass, $row[3])){
						if($row[4] == '1'){header("Location: error.php?err=Jesteś+zbanowany"); die(); }
						$_SESSION['isLoged'] = 1;
						$_SESSION['yourName'] = $row[1];
						$_SESSION['yourID'] = $row[0];
						$_SESSION['isAdmin'] = $row[5];
						header('Location: list.php');
					}else
					 echo '<script> function error(){ document.getElementById("error").innerHTML = "Hasło jest błedne.";}</script>';
				} else
					echo '<script> function error(){ document.getElementById("error").innerHTML = "Nie odnaleziono użytkownika.";}</script>';
			}
		}
	?>
	<div id="aban" style=" background-color: white; width: 378px; height: 32px; font-family: 'Gugi', sans-serif; font-size: 20px; line-height: 32px; text-align: center; padding: 8px 24px; border-radius: 16px; box-shadow: 4px 4px 8px black; position: fixed; left: calc(50% - 213px); top: 128px; cursor: move; z-index: 2;"> "Abandon all hope, ye who enter here" </div>
	<form autocomplete="off" id="login" action="login.php" method="POST" style="cursor: move;">
		<input type="text" placeholder="Twój login" name="llogin"><br>
		<input type="password" placeholder="Twoje hasło" name="lpass"><br>
		<div id="error" style="cursor: move;"></div>
		<font color="black">
			We've Updated <br>
			our <a target="_blank" href="regulamin.php">Terms of Service</a> <br>
			and <a target="_blank" href="regulamin.php">Privacy Policy</a>
		</font>
		<input style="margin-top: 0;" value="Zaloguj się" type="submit">
		<input onclick="reg();" type="button" value="Zarejestruj się">
		<!--<i>If (zapomniałeś hasła)<br>
		 masz dwie opcje<br>
		 albo się od nowa rejestrujesz<br>
		 albo mnie znasz i grzecznie prosisz o jakieś nowe<br>
		 albo czekasz cierpliwie na implementacje systemu "Zapomniałem hasła".<br>
		 Napisałem dwie? To źle? Liczy się od zera.....</i>
		 <br><a href="register.php">Register się</a>
		 <br><br><br><br>
		 <i>Dla idiotów, którzy nie potrafią się zarejestrować:<br>
			login: public<br>
			password: qwerty123</i>-->
	</form>
	<!--div style="z-index: 999; position: fixed; bottom: 0; left: 0; width: 100%; height: 32px; background-image: url('pics/sponsorship.png'); box-shadow: 0 0 8px black; "></div-->
	<div id="maciek" onclick=" window.open('https://github.com/Macylion', '_blank');">by Maciej Kubus <img alt="" src="pics/p.png" style=" margin-top: 14px; width: 166px; height: 166px;"></div>
	<style>
		#maciek{
			font-family: 'Gugi', sans-serif;
			font-size: 18px;
			padding: 8px 12px;
			background-color: white;
			border-top-left-radius: 16px;
			border-top-right-radius: 16px;
			position: fixed;
			bottom: 0;
			width: 166px;
			cursor: pointer;
			/*left: calc(100% - 256px);*/
			right: 48px;
			text-align: center;
			transform: translateY(182px);
			transition: all 1.2s;
		}
		#maciek:hover{
			transform: translateY(0);
			transition: all 1.2s;
		}
	</style>
</body>
</html>