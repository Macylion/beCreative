<?php
	function printMenu($yourID){
		require("conn.php");
		require_once("lib.php");
		$sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
		$sql->query('SET CHARACTER SET utf8');
		$ux = ($sql->query("SELECT * FROM `users` WHERE `id` = ".$yourID));
		$user = $ux->fetch_row();
		$img_url = str_replace("C:/xampp/htdocs/", "", getImageByID(getPersonaByUserID($yourID)[4])[6]);
		$admin_panel = '';
		if($user[5] == 1){
			$admin_panel = '<div id="admin"><a title="phpMyAdmin" class="a1" href="http://188.246.137.178/phpmyadmin/" target="_blank"><img class="iconmenu" src="pics/admin.png"></a></div>';
			echo '<style>#menu{background-color: red;}</style>';
		}
		echo '
		<script src="https://unpkg.com/tippy.js@2.5.2/dist/tippy.all.min.js"></script>
		<div id="menu">
			<a class="a1" href="index.php"><div class="textlogo">beCreative!</div></a>
			'.$admin_panel.'
			<div class="menu_righter">
				<a title="Bądź kreatywny" class="a1" href="upload.php"><img class="iconmenu" src="pics/upload.png"></a>
				<a title="O Stronie" class="a1" href="about.php"><img class="iconmenu" src="pics/about.png"></a>
				<a title="Wyloguj się" class="a1" href="logout.php"><img class="iconmenu" src="pics/logout.png"></a>
				<a title="'.$_SESSION['yourName'].'" class="a1" href="profile.php?id='.$yourID.'"><img class="avatarmenu" src="'.$img_url.'" alt="avatar"></a>
			</div>
		</div>
		<script>tippy(".a1")</script>
		';

		$sql->close();
	}

	function printFooter(){
		echo '
			<div id="footer">
				Maciej Kubus (and gaa.tsdns.pl)
				<p>2018 &copy;</p>
			</div>
		';
	}
?>
