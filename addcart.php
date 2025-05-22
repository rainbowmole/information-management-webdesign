<?php
session_start(); 
//checking for session id or admin
if(!isset($_SESSION['user_level']) or ($_SESSION['user_level'] != 0)){
    header("location: login.php");
    exit();
}

echo "Item add to Cart"
?>