<?php
session_start();
@include 'config.php';
@include 'obslugujace.php';
$offset = 0;
error_reporting(0);
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];
header("Cache-Control: no-cache, must-revalidate");
?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/regulamin.css">
<link rel="stylesheet" type="text/css" href="css/style3.css">
<nav>
    <ul class="navbar-menu">
        <li><a href="index.php">Strona główna</a></li>
        <li><a href="cvgenerator.php">Generator CV</a></li>
        <li class="navbar-dropdown">
        <a href="#">Profil</a>
        <ul class="navbar-dropdown-menu">
            <li><a href="profil.php">Edytuj Profil</a></li>
            <li><a href="moje-aplikacje.php">Złożone aplikacje</a></li>
            <li><a href="otrzymane-aplikacje.php">Otrzymane aplikacje</a></li>
        </ul>
        </li>
        <li><a href="dodajoferte.php">Dodaj oferte pracy</a></li>
        

        <?php if(!isset($_SESSION['authenticated'])) : ?>
            <li><a href="login.php">Logowanie</a></li>
                <?php endif ?>

                <?php if(isset($_SESSION['authenticated'])) : ?>
                    <li><a href="logout.php">Wyloguj</a></li>
                <?php endif ?>
    </ul>
</nav>
  <title>Regulamin serwisu</title>
</head>
<body>
  <div class="container">
    <h1>Regulamin serwisu</h1>
    <div class="regulamin">
      <?php include 'regulamin.txt'; ?>
    </div>
  </div>
</body>
</html>