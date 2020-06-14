<?php
	session_start();
	if(!isset($_SESSION['isLoged'])){
		header('Location: index.php');
		die();
	}
	require("conn.php");
	require_once("lib.php");
	require_once("menu.php");
	//comms deleting
	if(isset($_GET['del']) && $_SESSION['isAdmin'] == 1){
		$s = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
		$s->query('SET CHARACTER SET utf8');
		$s->query('UPDATE `comments` SET `text`=concat(`text`, "*hide*") WHERE `id` = '.$_GET['del']);
		$s->close();
		header('Location: img.php?id='.$_GET['id']);
		die();
	}

	//Setting avatar and bg
	if(isset($_GET['set'])){
		$db = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
		$db->query('SET CHARACTER SET utf8');
		//Setting avatar
		if($_GET['set'] == 1){
			$db->query('UPDATE `persona` SET `avatar_img_id` = '.$_GET['id'].' WHERE `user_id` = '.$_SESSION['yourID']);
		}
		//Setting bg
		if($_GET['set'] == 2){
			$db->query('UPDATE `persona` SET `bg_img_id` = '.$_GET['id'].' WHERE `user_id` = '.$_SESSION['yourID']);
		}
		$db->close();
		header('Location: img.php?id='.$_GET['id']);
		die();
	}
	//nsfw and deleting
	if(isset($_GET['ac']) && $_SESSION['isAdmin'] == 1){
		$dbsql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
		$dbsql->query('SET CHARACTER SET utf8');
		//nsfwing
		if($_GET['ac'] == 1){
			$dbsql->query('UPDATE `images` SET `nsfw` = 1 WHERE `id` = '.$_GET['id']);
		}	
		//oking
		if($_GET['ac'] == 0){
			$dbsql->query('UPDATE `images` SET `deleted` = 0 WHERE `id` = '.$_GET['id']);
			$dbsql->query('UPDATE `images` SET `nsfw` = 0 WHERE `id` = '.$_GET['id']);
		}
		//deleting
		if($_GET['ac'] == 2){
			$dbsql->query('UPDATE `images` SET `deleted` = 1 WHERE `id` = '.$_GET['id']);
			$dbsql->query('UPDATE `images` SET `nsfw` = 1 WHERE `id` = '.$_GET['id']);
		}
		$dbsql->close();
		header('Location: img.php?id='.$_GET['id']);
		die();
	}
	//UPDATE `images` SET `nsfw` = 1 WHERE `id` = 100
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf8">
	<meta name="description" content="Bądź kreatyny stwórz lepszy świat.">
	<meta name="author" content="Maciej Kubus">
	<meta name="keywords" content="kreatywność, creativity, lepszy, świat">
	<title>Obrazek | beCreative! | Bądź kreatyny stwórz lepszy świat</title>
	<link rel="stylesheet" href="css/styles.css">
	<link rel="shortcut icon" href="pics/favicon.png">
	<script type="text/javascript">
		function hide(a){
			a.style.display = "none";
		}
	</script>
</head>
<body class="imgbody">
	<?php printMenu($_SESSION['yourID'])?>
	<div id="img">
	<?php 
		//Connect BD
		require_once("conn.php");
		require_once("lib.php");
		$sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
		$sql->query('SET CHARACTER SET utf8');
		//Get highest ID
		$ec = $sql->query('SELECT * FROM `images` ORDER BY `id` DESC');
		$ecr = $ec->fetch_row();
		//Error handling and get GET ID
		if(!isset($_GET['id'])) error("Brak podanego ID, błąd przekierowania");
		$id = $_GET['id'];
		//Set display add/remove from favorites
		$_SESSION['fav_add'] = "block";
		$_SESSION['fav_remove'] = "block";
		$res = $sql->query("SELECT * FROM `favorites` WHERE user_id = ".$_SESSION['yourID']." AND img_id = ".$id);
		if($res->num_rows){
			$_SESSION['fav_add'] = "none";
			$_SESSION['fav_remove'] = "flex";
			$_SESSION['fav_class'] = "one";
		}else{
			$_SESSION['fav_add'] = "flex";
			$_SESSION['fav_remove'] = "none";
			$_SESSION['fav_class'] = "two";
		}
		if($id > $ecr[0]) error("Taki projekt nie istnieje");
		//DB Query
		if($res = $sql->query('SELECT * FROM `images` WHERE `id` = '.$id)){
			$row = $res->fetch_row(); 
			//Get author display name
			$res2 = $sql->query('SELECT * FROM `users` WHERE `id` = '.$row[4]);
			$row2 = $res2->fetch_row();
			$author_name = $row2[1];
			//If is hidden
			if($row[8] == 1 && $_SESSION['isAdmin'] != 1)
				error('Obrazek został zbanowany');
			if($row[8] == 1){
				echo '<div id="banned">OBRAZEK ZBANOWANY</div>';
				echo '
				<style>
					#banned{
						position: fixed;
						bottom: 0;
						width: 100%;
						left: 0;
						text-align: center;
						font-family: "Roboto", sans-serif;
						font-size: 32px;
						color: red;
						font-weight: bold;
						z-index: 999;
						text-shadow: black 0 0 4px;
					}
				</style>
				';
			}
			
			//Print data
			echo "<div id=".'info'."><div id='quick'><h1>".$row[2]."</h1>";
			
			echo '<script>document.title="'.$row[2].' | beCreative! | Bądź kreatyny stwórz lepszy świat"</script>';

			echo "<h2>".$row[5]."</h2></div>";
			$img_url = str_replace("C:/xampp/htdocs/", "", getImageByID(getPersonaByUserID($row[4])[4])[6]);
			//if isAdmin
			$a123 = '';
			if($row2[5] == 1)
				$a123 = '<div class="admin">Administrator</div>';

			echo '<a href="profile.php?id='.$row[4].'"><div id="author"> <img  alt="" src="'.$img_url.'"><h3>'.$author_name."</h3><div id='desc'>".$a123.getPersonaByUserID($row[4])[2]."</div></div></a>";
			echo '
			<form id="fav_info" class="'.$_SESSION['fav_class'].'" action="fav.php?a=1" method="post" style="display:'.$_SESSION['fav_add'].'">
				<input type="hidden" name="id" value="'.$_GET['id'].'">
				<label>
					<h4>♥</h4><h5>'.$row[1].'</h5>
					<p>Dodaj do ulubionych</p>
					<input onclick="hide(this.parentElement);" type="submit" value="Dodaj do ulubionych">
				</label>
			</form>
			<form id="fav_info" class="'.$_SESSION['fav_class'].'" action="fav.php?a=0" method="post" style="display: '.$_SESSION['fav_remove'].'">
				<input type="hidden" name="id" value="'.$_GET['id'].'">
				<label>
					<h4>♥</h4><h5>'.$row[1].'</h5>
					<p>Usuń z ulubionych</p>
					<input onclick="hide(this.parentElement);" type="submit" value="Usuń z ulubionych">
				</label>
			</form>
			';
			if(strlen($row[3]) < 1){
				echo '</div>
				<style>
				#fav_info{
					border-bottom-right-radius: 18px;
					border-bottom-left-radius: 18px; 
				}
				</style>
				';
			}else{
				echo "<div id='description'>".$row[3]."</div></div>";
			}

			$blur = '0';
			if($row[7] == 1) $blur = '8px';

			echo '
				<style>
				#imginside{
					filter: blur('.$blur.');
					overflow: hidden;
				}
				#imginside:hover{
					filter: blur(0);
				}
				</style>
			';
			$urlo = str_replace("C:/xampp/htdocs/","",$row[6]);
			if(explode(".", $urlo)[1] == "mp4")
				echo '<div id="imginside"><video id="video" controls autoplay src="'.$urlo.'"></div><br>';
			else
				echo '<div id="imginside"><img alt="" src="'.$urlo.'"></div><br>';
		}
		$rs = $sql->query("SELECT * FROM `comments` WHERE `img_id` = ".$id." ORDER BY date DESC ");
		$_SESSION['comms'] = '';
		while ($r = $rs->fetch_row()) {
			//Get author
			$sq = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
			$sq->query('SET CHARACTER SET utf8');
			$x = $sq->query('SELECT * FROM `users` WHERE `id` = '.$r[1]);
			$author = $x->fetch_row();
			$sq->close();
			//$_SESSION['comms'] .= '<b>'.$author[1].'</b> - <i>'.$r[4].'</i><br>'.$r[3].'<br>';
			$remove = '';
			if($_SESSION['isAdmin'] == 1)
				$remove = '<a class="trash" href="img.php?id='.$_GET['id'].'&del='.$r[0].'"></a>';

			if(strpos($r[3], '*hide*') === false){
				$_SESSION['comms'] .= '<div class="comm">'.$remove.'<a href="profile.php?id='.$r[1].'"><img class="ava" alt="" src="'.str_replace("C:/xampp/htdocs/", "", getImageByID(getPersonaByUserID($r[1])[4])[6]).'"><div class="author">'.$author[1].'</div></a><div class="date">'.$r[4].'</div><div class="content">'.$r[3].'</div></div>';
			}
		}
		

		$sql->close();
	?>
	</div>

	<div id="set">
		<a class="a1" href="img.php?id=<?php echo $_GET['id'].'&set=1'?>"><div id="seta">Ustaw jako avatar.</div></a>
		<a class="a1" href="img.php?id=<?php echo $_GET['id'].'&set=2'?>"><div id="setb">Ustaw jako tło.</div></a>
		<?php
			if($_SESSION['isAdmin'] == 1)
				echo '
				<style>
				#setb, #setc{
					border-bottom: 2px solid black;
				}
				</style>
				<a class="a1" href="img.php?id='.$_GET['id'].'&ac=0"><div id="setc">Ustaw jako OK</div></a>
				<a class="a1" href="img.php?id='.$_GET['id'].'&ac=1"><div id="setc">Ustaw jako NSFW</div></a>
				<a class="a1" href="img.php?id='.$_GET['id'].'&ac=2"><div id="setd">Ukryj przed światem</div></a>
				';
		?>
	</div>

	<form class="comm" action="add_comm.php" method="post">
		<?php echo '<img class="ava" alt="" src="'.str_replace("C:/xampp/htdocs/", "", getImageByID(getPersonaByUserID($_SESSION['yourID'])[4])[6]).'">'; ?>
		<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
		<input maxlength="40" type="text" name="comm" placeholder="Dodaj komentarz... (użyj max 40 znaków)">
		<input onclick="hide(this);" type="submit" value="Wyślij">
	</form>
	<?php
		echo $_SESSION['comms'];
	?>
	<div style="width: 100%; height: 240px;"></div>
	<?php printFooter();?>

</body>
</html>

	