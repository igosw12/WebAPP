<?php
error_reporting(0);
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
@include 'authentication.php';
@include 'config.php';
@include 'obslugujace.php';


$user = $_SESSION['auth_user']['user'];
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];


if(isset($_POST['akcja'])) {
  if($_POST['akcja'] == 'dodaj') {
    header('Location: dodaj-pracodawce.php');
    exit();
  }elseif ($_POST['akcja'] == 'szukaj'){
    header('Location: display-pracownika.php');
    exit();
  }else {
	header('Location: display-moje.php');
    exit();
  }
}


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/style3.css">
    <script src="skrypty.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <title>Job Finder</title>
</head>
<body>
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
    <head>
	<title>Pracodawca</title>
	<style>
		body {
			background-color: #f2f2f2;
            margin-top: 200px;
			font-family: Arial, sans-serif;
		}
		h1 {
			color: #333;
			text-align: center;
		}
		form {
			width: 100%;
			margin: auto;
			padding: 20px;
			background-color: #fff;
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
			border-radius: 10px;
		}
		label {
			display: block;
			margin-bottom: 10px;
			color: #333;
		}
		input[type="radio"] {
			margin-right: 10px;
		}
		input[type="submit"] {
			background-color: #4CAF50;
			color: #fff;
			padding: 10px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			margin-top: 20px;
		}
		input[type="submit"]:hover {
			background-color: #2E8B57;
		}
	</style>
</head>
<body>
	<h1>Wybierz, co chcesz zrobić:</h1>
	<form action="pracodawca.php" method="post">
		<label for="szukaj">
			<input type="radio" id="szukaj" name="akcja" value="szukaj">
			Szukaj pracowników
		</label>
		<label for="szukaj">
			<input type="radio" id="moje" name="akcja" value="moje">
			Moje oferty pracy
		</label>

        <?php
        $sql = "SELECT * FROM pracodawcy WHERE id_uzytkownicy=$id_uzytkownicy";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
        ?>
		<label for="dodaj">
			<input type="radio" id="dodaj" name="akcja" value="dodaj">
			Edytuj dane firmy
		</label>
        <?php
        } else{
            ?>
        <label for="dodaj">
		<input type="radio" id="dodaj" name="akcja" value="dodaj">
		Dodaj siebie jako pracodawcę
		</label>
        <?php
        }
        ?>
		<input type="submit" value="Wybierz">
	</form>
</body>
</html>


</body>
</html>