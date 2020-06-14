<?php
	ob_start();
	session_start();
	if(!isset($_SESSION['isLoged'])){
		header('Location: index.php');
		die();
	}

	require_once('conn.php');
	require_once("lib.php");
	if(strlen($_POST['comm']) > 0){
		$sq = new mysqli($DBadress, $DBuser, $DBpass, $DBdb);
		$sq->query('SET CHARACTER SET utf8');
		$id = $_POST['id'];
		$comm = $_POST['comm'];
		
		$comm = str_replace('"', "&quot;", $comm);
	    $comm = str_replace("'", "&apos;", $comm);
	    $comm = str_replace("`", "&#180;", $comm);
	    $comm = str_replace("/", "&#47;", $comm);
	    $comm = str_replace("\\", "&#92;", $comm);
	    $comm = str_replace("<", "&lt;", $comm);
	    $comm = str_replace(">", "&gt;", $comm);

		$sq->query("INSERT INTO `comments` VALUES (NULL, '".$_SESSION['yourID']."', '".$id."', '".$comm."', CURRENT_TIMESTAMP)");
		$sq->close();
	}
	header("Location: img.php?id=".$_POST['id']);
?>