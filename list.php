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
	<title>Lista obrazów | beCreative! | Bądź kreatyny stwórz lepszy świat</title>
	<link rel="stylesheet" href="css/styles.css">
	<link rel="shortcut icon" href="pics/favicon.png">
	<script src="js/jquery-3.3.1.min.js"></script>
</head>
<body onscroll="doMoreImgs()" onresize="doMoreImgs()">
	<?php printMenu($_SESSION['yourID'])?>
	<div id="image_container">
	<?php 
		//Print hi
		if(isset($_SESSION['isLoged']))
	 		echo 'hi <a href="profile.php?id='.$_SESSION['yourID'].'">'.$_SESSION['yourName'].'!</a> <a href="logout.php">Wyloguj się</a> <a href="upload.php">Upload</a> <br><hr><br>';
	 	else
	 		echo '<a href="login.php">Zaloguj się</a>
	 		     <a href="register.php">Zarejestruj się</a><br><hr><br>';
		//Connect BD
		$sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
		$sql->query('SET CHARACTER SET utf8');
		//DB Query
		if($res = $sql->query('SELECT * FROM `images` ORDER BY `date` DESC')){
			$xxx = 0;
			while ($row = $res->fetch_row()) {
				//echo '<a href="img.php?id='.$row[0].'">'.$row[2].'</a><br>';
				if(!($row[7] == 1 || $row[8] == 1) && $row[0]){
					$visible = "none";
					if($xxx < 64)  $visible = "inline-block";
					/*echo '<a id="i'.$xxx.'" class="a3" href="img.php?id='.$row[0].'"><div class="image" style="display: '.$visible.'; background-image: url('."'".str_replace("C:/xampp/htdocs/","",$row[6])."'".');">
						<div class="fog"></div>
						<div class="text">
							<div class="title">'.$row[2].'</div>
							<div class="shadow"></div>
						</div>
					</div></a>';*/ //commented before mp4 update
					$urlo = str_replace("C:/xampp/htdocs/","",$row[6]);
					if(explode(".", $urlo)[1] == "mp4")

						echo '<a id="i'.$xxx.'" class="a3" href="img.php?id='.$row[0].'"><div class="image" style="display: '.$visible.'; background-image: url('."'pics/blank.png'".');">
								<div class="fog"></div>
								<div class="text">
									<div class="title">'.$row[2].'</div>
									<div class="shadow"></div>
								</div>
							</div></a>';
					else
						echo '<a id="i'.$xxx.'" class="a3" href="img.php?id='.$row[0].'"><div class="image" style="display: '.$visible.'; background-image: url('."'".str_replace("C:/xampp/htdocs/","",$row[6])."'".');">
								<div class="fog"></div>
								<div class="text">
									<div class="title">'.$row[2].'</div>
									<div class="shadow"></div>
								</div>
							</div></a>';
					$xxx++;
				}

			}
			echo '<script> const num_rows = '.$xxx.';</script>';
		}

		$sql->close();
	?>
	</div>
	<div id="sorry">
		<h1>Scrolluj żeby ładować nowe elementy!</h1>
		<p>Jeśli elementy się nie łądują</p>
		<p>Przyjdź później lub <a class="a2" href="upload.php">dodaj jakieś obrazki.</a></p>
	</div>
	<?php printFooter();?>
	<script defer>
		function showMore() {
			var i = 0;
			var j = 0;
			$('.image').each(function(){
				if ($(this).css('display') != 'none')i++;
				if ($(this).css('display') === 'none' && j <= 7){
					$(this).css('display', 'inline-block');
					j++;
				}
			});
			return 'OK';
		}
		function isVisible(){
			elem = $('#sorry');
			var docViewTop = $(window).scrollTop();
		    var docViewBottom = docViewTop + $(window).height();
		    var elemTop = $(elem).offset().top;
		    var elemBottom = elemTop + $(elem).height();

		    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
		}
		function doMoreImgs(){
			if(isVisible()){ showMore(); console.log('i do this')}
		}

	</script>
</body>
</html>