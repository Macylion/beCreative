<?php
session_start();
	if(!isset($_SESSION['isLoged'])){
		header('Location: login.php');
	}else{
		header('Location: list.php');
	}
?>