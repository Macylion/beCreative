<?php
	session_start();
	if(!isset($_SESSION['isLoged'])){
		header('Location: index.php');
		die();
	}

	require_once('conn.php');
	require_once("lib.php");

	$des = $_POST['des'];
	$des = str_replace('"', "&quot;", $des);
	$des = str_replace("'", "&apos;", $des);
	$des = str_replace("`", "&#180;", $des);
	$des = str_replace("/", "&#47;", $des);
	$des = str_replace("\\", "&#92;", $des);
	$des = str_replace("<", "&lt;", $des);
	$des = str_replace(">", "&gt;", $des);

	$sq = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
	$sq->query('SET CHARACTER SET utf8');
	$sq->query("UPDATE `persona` SET description = '".$des."' WHERE user_id = ".$_SESSION['yourID']);
	$sq->close();
	header("Location: profile.php?id=".$_SESSION['yourID']);
?>