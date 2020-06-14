<?php
	session_start();
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf8">
	<meta name="description" content="Bądź kreatyny stwórz lepszy świat.">
	<meta name="author" content="Maciej Kubus">
	<meta name="keywords" content="kreatywność, creativity, lepszy, świat">
	<title><?php if(!isset($_GET['err'])) $err = 'Error';else $err = $_GET['err'];echo $err;?> | beCreative! | Bądź kreatyny stwórz lepszy świat</title>
	<link rel="stylesheet" href="css/styles.css">
	<link rel="shortcut icon" href="pics/favicon.png">
</head>
<body class="errbody">
	<div id="menu">
		<a class="a1" href="index.php"><div class="textlogo">beCreative!</div></a>
		<div class="menu_righter" style="display: none;">
			<a class="a1" href="upload.php"><img class="iconmenu" src="pics/upload.png"></a>
			<a class="a1" href="about.php"><img class="iconmenu" src="pics/about.png"></a>
			<a class="a1" href="logout.php"><img class="iconmenu" src="pics/logout.png"></a>
			<a class="a1" href="profile.php?id='1'"><img class="avatarmenu" src="pics/bg.png" alt="avatar"></a>
		</div>
	</div>
	<div class="err">
	<?php
		if(!isset($_GET['err'])) $err = 'Error';
		else $err = $_GET['err'];

		echo $err;

	?>
	</div>
	<div class="errhome" onclick="window.location.href = 'index.php' ">
		Strona Główna
	</div>
</body>
</html>