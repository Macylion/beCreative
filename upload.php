<?php
	session_start();
	if(!isset($_SESSION['isLoged'])){
		header('Location: index.php');
		die();
	}
	require("conn.php");
	require_once("lib.php");
	require_once("menu.php");
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf8">
	<meta name="description" content="Bądź kreatyny stwórz lepszy świat.">
	<meta name="author" content="Maciej Kubus">
	<meta name="keywords" content="kreatywność, creativity, lepszy, świat">
	<title>Dodaj obrazek | beCreative! | Bądź kreatyny stwórz lepszy świat</title>
	<link rel="stylesheet" href="css/styles.css">
	<link rel="shortcut icon" href="pics/favicon.png">
	<script type="text/javascript">
		function u() {
			document.getElementById('f').innerHTML = document.getElementById('file').value.split('\\').pop();
		}

		function hide(a){
			a.style.display = "none";
		}
	</script>
</head>
<body class="uploadbody" onload="start();">
	<?php printMenu($_SESSION['yourID'])?>
	<form id="uform" action="uploaded.php" method="post"
	enctype="multipart/form-data">
	<input placeholder="Tytuł" type="text" name="title" minlength="3" maxlength="32" required><br>
	<?php if(isset($_GET['e'])) echo '<!--Wprowadź tytuł<br>-->'?>
	<textarea maxlength="256" placeholder="Króki opis &#13;&#10;Max 256 znaków" name="desc"></textarea><br>
	<label><input onchange="u();" type="file" name="file" id="file"><p id="f">Kliknij aby dodać obrazek.</p></label>
	<input onclick="/*hide(this);*/"  type="submit" name="submit" value="Wyślij">
	</form>

</body>
</html>