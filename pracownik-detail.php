<?php
@include 'config.php';
session_start();
##error_reporting(0);
##ini_set('display_errors', 0);
$id_poten = $_GET['id'];

$weryfikacja = "SELECT * FROM uzytkownicy WHERE id_uzytkownicy = '$id_poten' AND zgodauzytkownika = 'Nie'";
$weryfikacja1 = mysqli_query($conn, $weryfikacja);

if (mysqli_num_rows($weryfikacja1) > 0) {
    header("Location: index.php");
}
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];
header("Cache-Control: no-cache, must-revalidate");
$sql = "SELECT uzytkownicy.email, uzytkownicy.id_uzytkownicy, profil_zawodowy.tytul, profil_zawodowy.cv, profil_zawodowy.skills, profil_zawodowy.opis, profil_zawodowy.wyksztalcenie, uzytkownicy.imie, uzytkownicy.nazwisko, uzytkownicy.avatar, uzytkownicy.telefon, uzytkownicy.email, uzytkownicy.wojewodztwo, uzytkownicy.miasto, uzytkownicy.ulica, uzytkownicy.kodpocztowy FROM uzytkownicy, profil_zawodowy WHERE uzytkownicy.id_uzytkownicy = '$id_poten' AND profil_zawodowy.id_uzytkownicy = uzytkownicy.id_uzytkownicy";


$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$value_odbiorca = $row['id_uzytkownicy'];
$value_imie = $row['imie'];
$value_nazwisko = $row['nazwisko'];
$value_wojewodztwo = $row['wojewodztwo'];
$value_miasto = $row['miasto'];
$value_kodpocztowy = $row['kodpocztowy'];
$value_telefon = $row['telefon'];
$value_email = $row['email'];
$avatar = $row['avatar'];
$value_tytul = $row['tytul'];
$value_wyksztalcenie = $row['wyksztalcenie'];
$value_opis = $row['opis'];
$value_skills = $row['skills'];
$filename = $row['cv'];
//$value_adres = $row[]
$aplikacja = 'null';
if(isset($_POST['wyslij'])){

    $wiadomosc = mysqli_real_escape_string($conn, $_POST['wiadomosc']);
  
    $wyslij_wiadomosc = "INSERT INTO wiadomosci (id_wysylajacego, id_odbiorcy, wiadomosc) VALUES ('$id_uzytkownicy', '$value_odbiorca', '$wiadomosc')";
    $wyslij_wiadomos_run = mysqli_query($conn, $wyslij_wiadomosc);
    if($wyslij_wiadomos_run){
      $_SESSION['status'] = "Wysłano wiadomość";
      $_SESSION['aktualne_dane'] = $_POST;
      header('Location: profil.php?');
      exit(0);
  }else{
      $_SESSION['status'] = "Coś poszło nie tak";
      header('Location: profil.php?');
      exit(0);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Oferta pracy</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style3.css">
    <link rel="stylesheet" href="css/style4.css">
    <link rel="stylesheet" href="css/style9.css">
    <script src="skrypty.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
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


<main>
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form action="" method="post" enctype="multipart/form-data">
            <?php 
            $sql = "SELECT cv, filecontent FROM profil_zawodowy, uzytkownicy WHERE uzytkownicy.id_uzytkownicy = $id_poten AND profil_zawodowy.id_uzytkownicy = uzytkownicy.id_uzytkownicy";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $filename = $row['cv'];
            ?>
                <div class="form-item">
                        <label for="wiadomosc">Wyślij wiadomość aplikantowi</label>
                        <textarea id="wiadomosc" name="wiadomosc" id="" rows="7" required>Witam 
Jesteśmy zainteresowani Pana usługami więc z tego powodu chcielibyśmy umówić się z Panem na spotkanie w celu przedstawienia szczegółów odnośnie naszej oferty pracy. W tym celu proponujemy dla Pana termin w dniu XX.XX.XXXX o godzinie XX:XX pod adresem zawartym w ofercie pracy. 
W razie pytanie prosimy o kontakt pod numerem telefonu 
Z poważaniem
XYZ 
</textarea>
                    <hr class="line-style-1">
                    <button class="wyslij" name="wyslij" id="wyslij" type="submit">Wyślij</button>
                </div>
                </form>
                </div>

    </div>
</div>
		<section class="job-details">
			<h2></h2>
            <div class="container">
            <div class="left">
            <p class="job-info-nazwa"><i class="fa-solid fa-user"></i></i>  </i></i><span class="job-location"><?php echo $value_imie; ?> <?php echo $value_nazwisko; ?></span></p>
            <p class="job-placa"><i class="fa-solid fa-house"></i>&nbsp</i><span class="job-contract-type"><?php echo $value_wojewodztwo; ?></span></p>
            <span class="adres-detail"><?php echo $value_miasto; ?></span></p>
            <span class="adres-detail"><?php echo $value_kodpocztowy; ?></span></p>
            <button type="button" id="toggleButton" onclick="toggleData()">Pokaż dane</button>
            <div id="dataContainer" style="display: none;">
            <p class="job-placa"><i class="fa-solid fa-envelope"></i>&nbsp</i><span class="job-contract-type"><?php echo $value_email; ?></span></p>
            <p class="job-placa"><i class="fa-solid fa-phone"></i><span class="job-contract-type"><?php echo $value_telefon; ?></span></p>
            </div>
            </div>
            <div class="right">
            <div class="awatar-osoba" id="awatar-osoba">
              <?php
                                    if (isset($avatar)) {
                                      echo '<img class="job-profile" src="data:avatar/jpeg;base64,'.base64_encode($avatar).'" alt="user-image" id="user-image" />';
                                  } else {
                                      echo '<img class="job-profile" src="img/User-avatar.svg.png" alt="user-image" id="user-image" />';
                                  }
            ?>
            </div>
                                </div>
            </div>
            <hr class="line-style-1">
			<p class="job-umowa"><i class="fa-solid fa-graduation-cap"></i>&nbsp</i><span class="job-contract-type"><?php echo $value_tytul; ?></span></p>
            <p class="job-tryb"><i class="fa-solid fa-school"></i>&nbsp</i><span class="job-contract-type"><?php echo $value_wyksztalcenie; ?></span></p>
            <p class="job-wymiar"><i class="fa-solid fa-flask"></i>&nbsp</i><span class="job-contract-type"><?php echo $value_skills; ?></span></p>
            <hr class="line-style-1">
			<ul>
            <c class="job-title">Opis</c>
            <hr class="line-style-3" width="55">
			<ul>
            <d class="job-kwalfi"><?php echo $value_opis?></d>
            <ul>
            <p>Pobierz plik: <a href="uploads/<?php echo $filename; ?>" download="<?php echo $filename; ?>"><?php echo $filename; ?></a></p>
                <?php
                if (!empty($resume)) {
                echo '<a href="download.php?id='.$file['id'].'">Pobierz plik</a>';
                }
                ?>
            <hr class="line-style-1">
            </section>
            <?php echo '<a class="apply-now" id="apply-now">Napisz</a>'; ?>
	</main>
    <script>
    function toggleData() {
    var button = document.getElementById("toggleButton");
    var container = document.getElementById("dataContainer");
    
    if (container.style.display === "none") {
        container.style.display = "block";
        button.innerHTML = "Ukryj dane";
    } else {
        container.style.display = "none";
        button.innerHTML = "Pokaż dane";
    }
    }
  </script>
  <script>
var modal = document.getElementById("myModal");
var modalButton = document.getElementById("apply-now");
var close = document.getElementsByClassName("close")[0];

modalButton.onclick = function() {
    document.documentElement.scrollTop = 0;

}
close.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

modalButton.onclick = function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    modal.style.display = "block";
    document.body.classList.add("modal-open");
}

close.onclick = function() {
    modal.style.display = "none";
    document.body.classList.remove("modal-open");
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        document.body.classList.remove("modal-open");
    }
}
</script>
</body>
</html>