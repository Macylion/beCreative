<?php
	ob_start();
	session_start();
	if(isset($_SESSION['isLoged'])){
		header('Location: index.php');
		die();
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf8">
	<meta name="description" content="Bądź kreatyny stwórz lepszy świat.">
	<meta name="author" content="Maciej Kubus">
	<meta name="keywords" content="kreatywność, creativity, lepszy, świat">
	<title>Zarejestruj się | beCreative! | Bądź kreatyny stwórz lepszy świat</title>
	<link rel="stylesheet" href="css/styles.css">
	<link rel="shortcut icon" href="pics/favicon.png">
	<script>
		function log(){
			window.location.href = "login.php";
		}
		function start(){
			$("#login").draggable();
			$('#aban').draggable();
		}
	</script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body class="regbody" onload="start();">
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
		require_once("conn.php");
		require_once("lib.php");

		//Create a bool isOK
		$isOK = false;

		//Login Check
		if(isset($_POST['login'])){
			$login = $_POST['login'];
			//Alphanumeric
			if(!ctype_alnum($login)){
				$_SESSION['elogin'] = "Login musi być alfa-numeryczny";
				$isOK = false;
			}
			//Lenght
			if(strlen($login) < 1 || strlen($login) > 32){
				$_SESSION['elogin'] = "Login musi zawierać 3-32 znaki";
				$isOK = false;
			}
			//Exist
			$sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
			$sql->query('SET CHARACTER SET utf8');
			if($e = $sql->query("SELECT * FROM `users` WHERE `login` = '$login'")){
				$i = 0;
				while($e->fetch_row()) $i++;
				echo $i;
			}
			$sql->close();
			if($i > 0){
				$_SESSION['elogin'] = "Login jest zajęty";
				$isOK = false;
			}else 
				$isOK = true; //Make isOK because login is typed
		}

		//Display Name Check
		if(isset($_POST['dname'])){
			$dname = $_POST['dname'];
			//Alphanumeric
			if(!ctype_alnum($dname)){
				$_SESSION['edname'] = "Twoja nazwa musi być alfa-numeryczna";
				$isOK = false;
			}
			//Lenght
			if(strlen($dname) < 1 || strlen($dname) > 32){
				$_SESSION['edname'] = "Twoja nazwa musi zawierać 3-32 znaki";
				$isOK = false;
			}
		}
		//Password
		if(isset($_POST['pass'])){
			$pass = $_POST['pass'];
			//Alphanumeric
			if(!ctype_alnum($pass)){
				$_SESSION['epass'] = "Twoja nazwa musi być alfa-numeryczna";
				$isOK = false;
			}
			//Lenght
			if(strlen($pass) < 8 || strlen($pass) > 24){
				$_SESSION['epass'] = "Twoja hasło musi zawierać 8-24 znaki";
				$isOK = false;
			}
		}
		//Repeat Password
		if(isset($_POST['rpass']) && isset($_POST['pass'])){
			$rpass = $_POST['rpass'];
			$pass = $_POST['pass'];
			if($rpass != $pass){
				$_SESSION['erpass'] = "Hasła muszą być identyczne";
				$isOK = false;
			}
		}

		//Put data into db
		if($isOK){
			//Parse data
			$login = $_POST['login'];
			$dname = $_POST['dname'];
			$pass = $_POST['pass'];
			$hashPass = password_hash($pass, PASSWORD_DEFAULT);
			//Connect
			$sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
			$sql->query('SET CHARACTER SET utf8');
			//Put data
			$x = $sql->query("INSERT INTO `users` (`id`, `Display_name`, `login`, `pass`, `mail`, `isAdmin`) VALUES (NULL, '$dname', '$login', '$hashPass', '', '0')");
			//echo $x;
			//Get ID
			$sq = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
			$sq->query('SET CHARACTER SET utf8');
			$res = $sq->query("SELECT * FROM `users` WHERE login = '".$login."'");
			$row = $res->fetch_row();
			$sq->close();
			//Create row in persona table
			$sql->query("INSERT INTO `persona` VALUES (NULL, '".$row[0]."', 'Nowy użytkownik beCreative!', '".$dname."', '1', '2')");
			$sql->close();
			session_unset();
			header('Location: succes.php');
			die();
		}

		
	?>
	<div id="aban" style=" background-color: white; width: 378px; height: 64px; font-family: 'Gugi', sans-serif; font-size: 20px; line-height: 32px; text-align: center; padding: 8px 24px; border-radius: 16px; box-shadow: 4px 4px 8px black; position: fixed; left: calc(50% - 213px); top: 128px; cursor: move; z-index: 2;"> "If you want to be original,<br> be ready to be copied" </div>
	<div style="display: block;">
	<form id="login" action="register.php" method="POST" style="cursor: move;">
		<input pattern="[a-zA-Z0-9]+" title="Tylko znaki alpha-numeryczne." type="text" minlength="2" placeholder="Login" name="login" value="<?php if(isset($_POST['login'])) echo $_POST['login']; ?>" required><br>
		<?php if(isset($_SESSION['elogin'])) echo $_SESSION['elogin'].'<br>'; ?>

		<input pattern="[a-zA-Z0-9]+" title="Tylko znaki alpha-numeryczne." type="text" minlength="2" placeholder="Nazwa widoczna" name="dname" value="<?php if(isset($_POST['dname'])) echo $_POST['dname']; ?>"required><br>
		<?php if(isset($_SESSION['edname'])) echo $_SESSION['edname'].'<br>'; ?>
		
		<input pattern="[a-zA-Z0-9]+" title="Tylko znaki alpha-numeryczne." type="password" minlength="8" placeholder="Hasło" name="pass" value="<?php if(isset($_POST['pass'])) echo $_POST['pass']; ?>"required><br>
		<?php if(isset($_SESSION['epass'])) echo $_SESSION['epass'].'<br>'; ?>
		
		<input type="password" placeholder="Powtórzone hasło" name="rpass" value="<?php if(isset($_POST['rpass'])) echo $_POST['rpass']; ?>"required><br>
		<?php if(isset($_SESSION['erpass'])) echo $_SESSION['erpass'].'<br>'; ?>
		
		<label>Klikając "Wyślij" akceptuję <a target="_blank" href="regulamin.php">regulamin</a>.</label>

		<input type="submit" value="Wyślij">
		<input onclick="log();" type="button" value="Zaloguj się">
	</form>
	</div>
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
			overflow: hidden;
		}
		#maciek:hover{
			transform: translateY(0);
			transition: all 1.2s;
		}
	</style>
</body>
</html>
<?php
	session_unset();
?>