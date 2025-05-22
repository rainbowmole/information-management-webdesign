<?php
	session_start(); 
	//checking for session id or admin
	if(!isset($_SESSION['role']) or ($_SESSION['role'] != 'admin')){
		header("location: login.php");
		exit();
	}
?>