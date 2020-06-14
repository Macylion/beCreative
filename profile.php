<?php
	session_start();
	if(!isset($_SESSION['isLoged'])){
		header('Location: index.php');
		die();
	}
	require_once("conn.php");
	require_once("lib.php");
	require_once("menu.php");
	//SuperAdmin Buttons
	if(isset($_GET['admin']))
		if($_GET['admin'] == "1" && $_SESSION['yourID'] == 1){
			$sq = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
			$sq->query('SET CHARACTER SET utf8');
			$sq->query("UPDATE `users` SET isAdmin = '1' WHERE id = ".$_GET['id']);
			$sq->close();
			header("Location: profile.php?id=".$_GET['id']);
		}
		else if($_GET['admin'] == "0" && $_SESSION['yourID'] == 1){
			$sq = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
			$sq->query('SET CHARACTER SET utf8');
			$sq->query("UPDATE `users` SET isAdmin = '0' WHERE id = ".$_GET['id']);
			$sq->close();
			header("Location: profile.php?id=".$_GET['id']);
		}
	//Baningo but on `mail`
	if(isset($_GET['ban']))
		if($_GET['ban'] == "1" && $_SESSION['yourID'] == 1){
			$sq = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
			$sq->query('SET CHARACTER SET utf8');
			$sq->query("UPDATE `users` SET mail = '1' WHERE id = ".$_GET['id']);
			$sq->close();
			header("Location: profile.php?id=".$_GET['id']);
		}
		else if($_GET['ban'] == "0" && $_SESSION['yourID'] == 1){
			$sq = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
			$sq->query('SET CHARACTER SET utf8');
			$sq->query("UPDATE `users` SET mail = '' WHERE id = ".$_GET['id']);
			$sq->close();
			header("Location: profile.php?id=".$_GET['id']);
		}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf8">
	<meta name="description" content="Bądź kreatyny stwórz lepszy świat.">
	<meta name="author" content="Maciej Kubus">
	<meta name="keywords" content="kreatywność, creativity, lepszy, świat">
	<title>beCreative! | Bądź kreatyny stwórz lepszy świat</title>
	<link rel="stylesheet" href="css/styles.css">
	<link rel="shortcut icon" href="pics/favicon.png">
	<script src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript">
		function edit(){
			$('#cdesc').css('display', 'block');
		}
		function unedit(){
			$('#cdesc').css('display', 'none');
		}
	</script>
</head>
<body class="profilebody" onload="tippy('.b_t_n');">
	<?php printMenu($_SESSION['yourID'])?>
	<!--Change desctiprion-->
	<style>
		.bbb{
			height: 94px;
			width: 256px;
			background-color: white;
			font-size: 24px;
			font-family: 'Roboto', sans-serif;
			border: 0;
			transition: all .5s;
		}
		.bbb:hover{
			background-color: lightgrey;
			cursor: pointer;
			transition: all .5s;
		}
	</style>

	<div id="cdesc" style="display: none; z-index: 1001; position: fixed; left: 0; top: 0; background-color: rgba(0,0,0,.7); width: 100%; height: 100%;">
		<div style="background-color: white; z-index: 1002; width: 512px; height: 128px; position: fixed; top: calc(50% - 64px - 8px); left: calc(50% - 256px - 8px); box-shadow: 4px 4px 8px #111111; padding: 16px;">
			<form method="POST" action="desc.php">
				<input placeholder="Wpisz tu swój nowy opis." type="text" name="des" style="height: 32px; width: 100%; border: 0; border-bottom: 1px solid black; text-align: center; font-size: 24px; margin-bottom: 8px;">
				<div style="; width: 100%; display: flex;">
					<input onclick="unedit();" type="button" class="bbb" value="Zamknij">
					<input type="submit" class="bbb" value="Wyślij">
				</div>
			</form>
		</div>
	</div>
	<!--Admin buttons-->
	<div id="set">
		<?php
		if($_SESSION['yourID'] != $_GET['id']){
			if($_SESSION['yourID'] == 1)
				echo '
						<a class="a1" href="profile.php?id='.$_GET['id'].'&admin=1'.'"><div id="seta">Moc Bogów i dla niego</div></a>
						<a class="a1" href="profile.php?id='.$_GET['id'].'&admin=0'.'""><div id="seta">Mocy Bogów to on godzien nie jest</div></a>
					
				';
			echo '<a class="a1" href="profile.php?id='.$_GET['id'].'&ban=1'.'"><div id="seta">SET BAN = 1</div></a>';
			echo '<a class="a1" href="profile.php?id='.$_GET['id'].'&ban=0'.'"><div style="border: 0; "id="seta">SET BAN = 0</div></a>';
		}
		?>
	</div>
	<!-- And posts -->
	<div id="posts">
	<?php
		//make a big array
		class Img{
			public $row;
			public $isFavorite;
			public $date;
			public $id;
			function __construct($r, $iU, $d){
				$this->row = $r;
				$this->isFavorite = $iU;
				$this->date = $d;
			}
		}
		$imgs;
		$imgs_index = 0;
		//require
		require_once("conn.php");
		require_once("lib.php");
		//Connect DB
		$sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
		$sql->query('SET CHARACTER SET utf8');
		//Get highest ID
		$ec = $sql->query('SELECT * FROM `users` ORDER BY `id` DESC');
		$ecr = $ec->fetch_row();
		//Error handling nd get GET ID
		if(!isset($_GET['id'])) error("Brak podanego ID, błąd przekierowania");
		$id = $_GET['id'];
		if($id > $ecr[0]) error("ID nie istnieje");
		//DB Query
		if($res = $sql->query('SELECT * FROM `users` WHERE `id` = '.$id)){
			$row = $res->fetch_row();
			//set variables
			$hearts = 0;
			$name = $row[1];
			$img_list = "";
			echo "<script> document.title = '".$row[1]." | beCreative! | Bądź kreatyny stwórz lepszy świat'; </script>";
			//Get images
			if($rs = $sql->query('SELECT * FROM `images` WHERE `author_id` = '.$id.' ORDER BY `date` DESC'))
				//Add image to string
				while($r = $rs->fetch_row()){
					if(!$r[8] == 1){
						$img_list .= '<br><a href="img.php?id='.$r[0].'">obrazek: '.$r[2].'</a>';	
						//Add to big array
						$imgs[$imgs_index] = new Img($r, 'Opublikowano', $r[5]);
						$imgs[$imgs_index]->id = $r[0];
						$imgs_index++;
					}
					$hearts += $r[1];
				}

			//adimo checko
			$a123 = '';
			if($row[5] == 1)
				$a123 = '<div class="admino">Administrator</div>';
			if($row[4] == '1')
				$a123 = '<div class="admino"><b>ZBANOWANY</b></div>';

			//checko ifo profilo iso youro
			$a321 = '';
			if($_GET['id'] == $_SESSION['yourID'])
				$a321 = '<div title="Zmień opis" onclick="edit();" class="b_t_n"></div>';

			//Print data
			echo "<div id='profile'>";
			echo "<div id='name'>".$name.$a321.'</div>';
			echo "<div id='i'><a href=profile.php?id=".$row[0]."><img alt='' src='".str_replace('C:/xampp/htdocs/','',getImageByID(getPersonaByUserID($id)[4])[6])."'></a></div>";
			echo '<div id="hearts"><div id="h">♥</div> '.$hearts."</div>";
			echo '<div id="desc">'.$a123.getPersonaByUserID($id)[2].'</div>';
			echo "</div><script>document.body.style.backgroundImage = ".'"'."url('".str_replace('C:/xampp/htdocs/','',getImageByID(getPersonaByUserID($id)[5])[6])."')".'"'.";</script> ";

			//echo $img_list; //Lista twoich obrazów

		}

		//Print favorites
		$res = $sql->query('SELECT * FROM `favorites` WHERE `user_id` = '.$_GET['id']);
		while($row = $res->fetch_row()){
			$rs = $sql->query('SELECT * FROM `images` WHERE `id` = '.$row[2].' ORDER BY `date`');
			$rw = $rs->fetch_row();
			if($r[0] !== ""){
				//echo '<a href="img.php?id='.$rw[0].'">'.$rw[2]."</a><br>"; //Lista twoich ulubionych
				$imgs[$imgs_index] = new Img($rw, 'Dodano do ulubionych', $row[3]);
				$imgs[$imgs_index]->id = $rw[0];
				$imgs_index++;
			}
		}

		$sql->close();


		//spacer desu
		echo '<div style="margin-top: 128px;"></div>';
		if($imgs_index !== 0){
			//imgs sort by date
			function date_compare($a, $b){
			    $t1 = strtotime($a->date);
			    $t2 = strtotime($b->date);
			    return $t1 - $t2;
			}    
			usort($imgs, 'date_compare');
			$imgs = array_reverse($imgs);
			//Wywalanie obrazków
			foreach ($imgs as $img) {
				//db connect
				$sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
				$sql->query('SET CHARACTER SET utf8');
				$res2 = $sql->query('SELECT * FROM `users` WHERE `id` = '.$img->row[4]);
				$row2 = $res2->fetch_row();
				$author_name = $row2[1];
				//checko nsfw
				$nsfw = '';
				$nsfw2 = '';
				if($img->row[7] == 1){
					$nsfw = ' nsfw';
					$nsfw2 = '<div class="ns">[NSFW]</div>';
				}
				//Printo posto
				if($img->row[8] != 1){
					$d1 = DateTime::createFromFormat('Y-m-d H:i:s', $img->date);
					$d2 = date_format($d1, 'd-m-Y H:i');
					echo '<div class="post">';
					echo '<a class="a2" href="img.php?id='.$img->row[0].'""><div class="img'.$nsfw.'" style="background-image: url('."'".str_replace("C:/xampp/htdocs/","",$img->row[6])."'".');"></div>';
					echo '<div class="tit">'.$nsfw2.' '.$img->row[2].'</div></a>';
					echo '<div class="desc'.$nsfw.'">'.$img->row[3].'</div>';
					echo '<div class="author">'.$author_name.'</div>';
					echo '<div class="date">'.$img->isFavorite.': <b>'.$d2.'</b></div>';
					echo '</div>';
				}
				//db disconect
				$sql->close();
			}
		}else{
			echo '<div class="post" style="margin-top: 40vh;">';
			echo '<h1>Nic tu nie ma...</h1>';
			echo "</div>";
		}
	?>

		<div style="width: 100%; height: 640px;"></div>
		<?php printFooter();?>
	</div>
</body>
</html>