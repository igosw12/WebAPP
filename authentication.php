<?php 
session_start();

if(!isset($_SESSION['authenticated']))
{
    $_SESSION['status'] = "Zaloguj się by mieć dostęp do zawartości.";
    header("Location: login.php");
    exit(0);
}