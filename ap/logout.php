<?php
    session_start();
    //pag walang nakalogin,
    //ibato sa index.php
    if(!isset($_SESSION['user_id'])){
        header("Location:/Kalye-Co./index.php");
        exit();
    }
    //pag may nakalogin
    //delete session, bato sa index.php

    else{
        $_SESSION = array();
        session_destroy();
        setcookie('PHPSESSID', '', time()-3600,'/', '', 0, 0);
        header("Location:/Kalye-Co./index.php");
        exit();
    }
?>