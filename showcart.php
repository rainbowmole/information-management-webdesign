<?php
session_start(); 
//checking for session id or admin
if(!isset($_SESSION['user_level']) or ($_SESSION['user_level'] != 0)){
    header("location: login_page.php");
    exit();
}

echo "Showing the Cart"

?>