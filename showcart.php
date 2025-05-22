<?php
	session_start(); 
	//checking for session id or admin
	if(!isset($_SESSION['role']) or ($_SESSION['role'] != 'user')){
		header("location: login.php");
		exit();
	}
?>
