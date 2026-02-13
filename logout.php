<?php
    session_start(); 
    session_unset();
    session_destroy();
    
    if(!isset($_SESSION['username']) || $_SESSION['logged_in'] !== true){
        header('Location: index.php');
        exit;
    }
?>