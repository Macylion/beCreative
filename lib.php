<?php
	function error($arg){
		header('Location: error.php?err='.str_replace(" ","+",$arg));
		die();
	}

	function getPersonaByUserID($user_id){
		require "conn.php";
		$sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
		$sql->query('SET CHARACTER SET utf8');
		$res = $sql->query("SELECT * FROM `persona` WHERE user_id = ".$user_id);
		$row = $res->fetch_row();
		$sql->close();
		return $row;
	}
	function getImageByID($id){
		require "conn.php";
		$sql = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
		$sql->query('SET CHARACTER SET utf8');
		$res = $sql->query("SELECT * FROM `images` WHERE id = ".$id);
		$row = $res->fetch_row();
		$sql->close();
		return $row;
	}

?>