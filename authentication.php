<?php
session_start();

if(!isset($_SESSION['authenticated']))
{
    $_SESSION['status'] = "Faça login para aceder.";
    header("Location: login.php");
    exit(0); 
}
?>