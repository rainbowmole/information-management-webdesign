<?php
session_start(); 
//checking for session id or admin
if(!isset($_SESSION['role']) or ($_SESSION['role'] != 'user')){
	header("location: login.php");
	exit();
}

require('mysqli_connect.php');

$food_id = $_POST['food_id'];
$quantity = $_POST['quantity'];

$sql = "INSERT INTO cart (user_id, food_id, quantity, created_at, updated_at)
        VALUES (?, ?, ?, NOW(), NOW())";
$stmt = mysqli_prepare($dbcon, $sql);
mysqli_stmt_bind_param($stmt, "iii", $user_id, $food_id, $quantity);
mysqli_stmt_execute($stmt);

?>