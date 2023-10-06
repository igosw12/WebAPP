<?php
@include 'config.php';
session_start();
error_reporting(0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];

$newValue = $_POST['status'];
$id_wiadomosci = $_POST['id'];


$sql = "UPDATE wiadomosci SET przeczytane = '$newValue' WHERE id_wiadomosci = '$id_wiadomosci'";
$update_profile_run = mysqli_query($conn, $sql);

?>