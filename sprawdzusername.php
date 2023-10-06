<?php
$user = $_GET["username"];

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "magisterka";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM uzytkownicy WHERE user = '$user'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "Ten username już istnieje w naszej bazie danych.";
}
else {
    echo " ";
}

?>