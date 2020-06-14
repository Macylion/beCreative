<?php
	session_start();
	if(!isset($_SESSION['isLoged']) || !isset($_GET['a'])){
		header('Location: index.php');
		die();
	}

	require_once('conn.php');
	require_once("lib.php");
	$sq = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
	$sq->query('SET CHARACTER SET utf8');

	if($_GET['a'] == '1'){
		$sq->query("INSERT INTO `favorites` VALUES (NULL,'".$_SESSION['yourID']."','".$_POST['id']."','".date("Y-m-d H:i:s")."')");
		$sq->query("UPDATE `images` SET `fav` = `fav` + '1' WHERE `images`.`id` = ".$_POST['id']);
	}else{
		$sq->query("DELETE FROM `favorites` WHERE `img_id` = ".$_POST['id']." AND `user_id` = ".$_SESSION['yourID']);
		$sq->query("UPDATE `images` SET `fav` = `fav` - '1' WHERE `images`.`id` = ".$_POST['id']);
	}
	//date("Y-m-d H:i:s")
	$sq->close();

	header('Location: img.php?id='.$_POST['id']);
?>