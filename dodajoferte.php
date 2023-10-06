<?php
session_start();
@include 'authentication.php';
@include 'config.php';
##header("Cache-Control: no-cache, must-revalidate");
##header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache");
header("Expires: 0"); 
error_reporting(0);
##ini_set('display_errors', 0);

require 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;

$user = $_SESSION['auth_user']['user'];
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];
$numer_pracodawcy = mysqli_query($conn, "SELECT id_pracodawcy FROM pracodawcy, uzytkownicy WHERE pracodawcy.id_uzytkownicy = $id_uzytkownicy");
$row = mysqli_fetch_array($numer_pracodawcy);
$value_pracodawca = $row['id_pracodawcy'];
##echo $numer_pracodawcy;
##Nazwa
$nazwa_profile = mysqli_query($conn, "SELECT nazwa FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_array($nazwa_profile);
$value_nazwa = $row['nazwa'];
##Miasto
$Miasto_profile = mysqli_query($conn, "SELECT pracodawcy.Miasto FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_assoc($Miasto_profile);
$value_Miasto = $row['Miasto'];
##Ulica
$Ulica_profile = mysqli_query($conn, "SELECT pracodawcy.Ulica FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_assoc($Ulica_profile);
$value_Ulica = $row['Ulica'];
##Kod pocztowy
$KodPocztowy_profile = mysqli_query($conn, "SELECT pracodawcy.KodPocztowy FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_assoc($KodPocztowy_profile);
$value_KodPocztowy = $row['KodPocztowy'];
##Wojewodztwo
$Wojewodztwo_profile = mysqli_query($conn, "SELECT pracodawcy.Wojewodztwo FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_assoc($Wojewodztwo_profile);
$value_Wojewodztwo = $row['Wojewodztwo'];

$avatar_profile = "SELECT Logo FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy LIMIT 1";
$result = mysqli_query($conn, $avatar_profile);

if($result && mysqli_num_rows($result) > 0)
{
    $row = mysqli_fetch_assoc($result);
    $logo = $row['Logo'];
}


if(isset($_POST['zapisz'])){
  $tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
  $data = mysqli_real_escape_string($conn, $_POST['data']);
  $nazwapracy = mysqli_real_escape_string($conn, $_POST['nazwapracy']);
  $miasto = mysqli_real_escape_string($conn, $_POST['miasto']);
  $ulica = mysqli_real_escape_string($conn, $_POST['ulica']);
  $kodpocztowy = mysqli_real_escape_string($conn, $_POST['kodpocztowy']);
  $wojewodztwo = mysqli_real_escape_string($conn, $_POST['wojewodztwo']);
  $Poziomstanowsika = $_POST['Poziom-stanowsika'];
  $Wymiarpracy = $_POST['Wymiar-pracy'];
  $Rodzajumowy = $_POST['Rodzaj-umowy'];
  $Trybpracy = $_POST['Tryb-pracy'];
  $typOferty = $_POST["typ_oferty"];
  $opis = mysqli_real_escape_string($conn, $_POST['opis']);
  $wymagania = mysqli_real_escape_string($conn, $_POST['wymagania']);
  $kwalfikacje = mysqli_real_escape_string($conn, $_POST['kwalfikacje']);
  $wynagrodzenie = mysqli_real_escape_string($conn, $_POST['wynagrodzenie']);
  $tags = mysqli_real_escape_string($conn, $_POST['skill']);
  $current_date_time = date('Y-m-d H:i:s');

  $opcjestonowisko = implode(", ", $Poziomstanowsika);
  $opcjewymiar = implode(", ", $Wymiarpracy);
  $opcjerodzaj = implode(", ", $Rodzajumowy);
  $opcjetryb = implode(", ", $Trybpracy);


  $insert1 = "INSERT INTO oferty_pracy (id_uzytkownicy) VALUES('$id_uzytkownicy')";
  $query_run = mysqli_query($conn, $insert1);
  $inserted_id = mysqli_insert_id($conn);
  if ($typOferty === "osoba_prywatna") {
    $update_profile_2 = "UPDATE oferty_pracy SET tytul = '$tytul', opis = '$opis', data_dodania = '$current_date_time',
    data_waznosci = '$data', wynagrodzenie = '$wynagrodzenie', miasto = '$miasto', wojewodztwo='$wojewodztwo', rodzaj_umowy = '$opcjerodzaj', tryb_pracy = '$opcjetryb',
    wymiar_pracy = '$opcjewymiar', poziom_stanowiska = '$opcjestonowisko', wymagania = '$wymagania', tags = '$tags', kwalfikacje = '$kwalfikacje', kod_pocztowy = '$kodpocztowy', ulica = '$ulica' 
    WHERE id_uzytkownicy = '$id_uzytkownicy' AND id_oferty_pracy = '$inserted_id' LIMIT 1"; 
  } elseif ($typOferty === "firma") {
    if (empty($_POST["nazwapracy"])) {
      header("Location: dodaj_pracodawce.php");
      exit;
    } else {
      $update_profile_2 = "UPDATE oferty_pracy SET tytul = '$tytul', opis = '$opis', data_dodania = '$current_date_time',
      data_waznosci = '$data', wynagrodzenie = '$wynagrodzenie', nazwapracy='$nazwapracy', miasto = '$miasto', wojewodztwo='$wojewodztwo', rodzaj_umowy = '$opcjerodzaj', tryb_pracy = '$opcjetryb',
      wymiar_pracy = '$opcjewymiar', poziom_stanowiska = '$opcjestonowisko', wymagania = '$wymagania', tags = '$tags', kwalfikacje = '$kwalfikacje', kod_pocztowy = '$kodpocztowy', ulica = '$ulica', id_pracodawcy = '$value_pracodawca' 
      WHERE id_uzytkownicy = '$id_uzytkownicy' AND id_oferty_pracy = '$inserted_id' LIMIT 1"; 
    }
  }
  $update_profile_run_2 = mysqli_query($conn, $update_profile_2);

  if(!empty($_FILES['userImage']['tmp_name']) && $_FILES['userImage']['size'] > 0) {
    $tmpPath = $_FILES['userImage']['tmp_name'];
    $compressedImagePath = 'ogloszenie/obrazka.jpg';
    $image = Image::make($tmpPath);
    $image->save($compressedImagePath, 80);
   $image = mysqli_real_escape_string($conn, $image);
   $update_profile_avatar = "UPDATE oferty_pracy SET zdjecie_promo = '$image' WHERE id_uzytkownicy = '$id_uzytkownicy' AND id_oferty_pracy = '$inserted_id' LIMIT 1";
   $update_profile_avatar_run = mysqli_query($conn, $update_profile_avatar);
  }
  if($update_profile_run_2){
  $_SESSION['status'] = "Nowe oferta pracy została dodana";
  $_SESSION['aktualne_dane'] = $_POST;
  header('Location: job-details.php?id=' . $inserted_id);
  exit(0);
  }else{
  $_SESSION['status'] = "Coś poszło nie tak";
  header('Location: dodaj-pracodawce.php?username=' . $user);
  exit(0);
  }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style3.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
        <script src="profil.js"></script>
    <title>Job Finder</title>

    <head>

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

	<title>Przykładowe okno modalne</title>
	<div class="form-container">
  <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
  <div class="form-window active" id="okno1">
    <label for="krok"> Krok 1/4 </label>
    <?php ##echo $value_pracodawca ?>
    <label for="typ_oferty">Typ oferty:</label>
    <input type="radio" name="typ_oferty" value="osoba_prywatna" id="osoba_prywatna" checked> Prywatne ogłoszenie
    <input type="radio" name="typ_oferty" value="firma" id="firma"> Firmowe
    <label for="tytul">Tytuł ogłoszenia:</label>
	<input type="text" name="tytul" id="tytul" required><br>
	<label for="data">Data ważności ogłoszenia:</label>
	<input type="date" name="data" id="data" required><br>
    <label for="nazwapracy1" id="nazwapracy1">Nazwa firmy:</label>
	<input type="text" name="nazwapracy" id="nazwapracy" required value="<?php echo $value_nazwa; ?>"><br>
    <label for="miejscepracy">Miejsce pracy:</label>
	<input type="text" name="miasto" id="miasto" required placeholder="Nazwa miasta" value="<?php echo $value_Miasto; ?>"><br>
  <input type="text" name="ulica" id="ulica" required placeholder="Nazwa ulicy i numer budynku" value="<?php echo $value_Ulica; ?>"><br>
  <input type="text" name="kodpocztowy" id="kodpocztowy" required pattern="[0-9]{2}-[0-9]{3}" placeholder="Kod pocztowy" value="<?php echo $value_KodPocztowy; ?>"><br>
  <input type="text" name="wojewodztwo" id="wojewodztwo" placeholder="Województwo" value="<?php echo $value_Wojewodztwo; ?>"><br>
    <div class="buttons">
    <button type="button" name="anuluj-button" id="anuluj-button" class="anuluj-button"> <a href="index.php" >Anuluj</a></button>
    <button type="button" class="next-button" id="next" onclick="pokazOkno2()">Kontynuuj</button>
    </div>
  </div>

  <div id="okno2" class="form-window active" style="display:none">
  <label for="krok"> Krok 2/4 </label>
  <div class="filter-box">
            <label for="Poziom-stanowsika">Poziom Stanowiska:</label>
            <select class="filter-select" id="Poziom-stanowsika[]" name="Poziom-stanowsika[]" required multiple>
                <option disabled selected value ="">Poziom Stanowiska</option>
                <option value="Młodszy specjalista(Junior)">Młodszy specjalista(Junior)</option>
                <option value="Specjalista(Mid)">Specjalista(Mid)</option>
                <option value="Starszy specjalista(Senior)">Starszy specjalista(Senior)</option>
                <option value="Dyrektor">Dyrektor</option>
            </select>
            <label for="Wymiar-pracy">Wymiar pracy:</label>
            <select class="filter-select" id="Wymiar-pracy[]" name="Wymiar-pracy[]" required multiple>
                <option disabled selected value ="">Wymiar pracy</option>
                <option value="Część etatu">Część etatu</option>
                <option value="Dodatkowa/Tymczasowa">Dodatkowa/Tymczasowa</option>
                <option value="Pełny etat">Pełny etat</option>
            </select>
            <label for="Rodzaj-umowy">Rodzaj umowy:</label>
            <select class="filter-select" id="Rodzaj-umowy[]" name="Rodzaj-umowy[]" required multiple>
                <option disabled selected value ="">Rodzaj umowy</option>
                <option value="Umowa o pracę">Umowa o pracę</option>
                <option value="Umowa o dzieło">Umowa o dzieło</option>
                <option value="Umowa zlecenie">Umowa zlecenie</option>
                <option value="Kontrakt B2B">Kontrakt B2B</option>
                <option value="Umowa o staż/praktyki">Umowa o staż/praktyki</option>
            </select>
            <label for="Tryb-pracy">Tryb pracy:</label>
            <select class="filter-select" id="Tryb-pracy[]" name="Tryb-pracy[]" required multiple>
                <option disabled selected value ="">Rodzaj umowy</option>
            <option value="Praca Stacjonarna">Praca Stacjonarna</option>
            <option value="Praca Zdalna">Praca Zdalna</option>
            <option value="Praca Hybrydowa">Praca Hybrydowa</option>
            </select>
</div>
  <div class="buttons">
    <button type="button" class="prev-button" id="prev" onclick="pokazOkno1()">Cofnij</button>
    <button type="button" class="next-button" id="next" onclick="pokazOkno3()">Kontynuuj</button>
   </div>
   </div>



    <div id="okno3" class="form-window active" style="display:none">
    <label for="krok"> Krok 3/4 </label>
    <div class="form-item">
      <label for="opis">Opis pracy</label>
      <textarea id="opis" name="opis" id="" rows="10"> </textarea>
    </div>
    <div class="form-item">
      <label for="wymagania">Wymagania</label>
      <textarea id="wymagania" name="wymagania" id="" rows="10"></textarea>
    </div>
    <div class="form-item">
      <label for="kwalfikacje">Kwalfikacje</label>
      <textarea id="kwalfikacje" name="kwalfikacje" id="" rows="10"></textarea>
    </div>
    

    <div class="buttons">
    <button type="button" class="prev-button" id="prev" onclick="pokazOkno2()">Cofnij</button>
    <button type="button" class="next-button" id="next" onclick="pokazOkno4()">Kontynuuj</button>
    </div>
    </div>



    <div id="okno4" class="form-window active" style="display:none">
    <label for="krok"> Krok 4/4 </label>
    <div class="form-item">
      <label for="wynagrodzenie">Wynagrodzenie</label>
      <input type="text" name="wynagrodzenie" id="wynagrodzenie" required pattern="[0-9]+(\.[0-9]{1,2})?"><br>
    </div>
    <label for="skill">Tagi</label>
                    <div class="form-item1">

                    <input type="text" name="skill" id="skill" value=""/>
                    </div>
    
                    <div class="user-image-upload">
                        <div class="user-image-container">
                        <?php
                            echo '<img src="img/job-seeker-icon.png" alt="user-image" id="user-image" />';
                        ?>
                        </div>
                        <input
                            type="file"
                            name="userImage"
                            id="userImage"
                            value="1"
                            onchange="userImageUploadHandler(event)"                      
                        />
                        </div>
    <div class="regulamin-akc">
      <input type="checkbox" required id="regulamin" name="regulamin">
      <label for="regulamin">Zapoznałem się z &nbsp<a href="regulamin.php"> regulaminem. </a> </label>
    </div>
    

    <div class="buttons">
    <button type="button" class="prev-button" id="prev" onclick="pokazOkno3()">Cofnij</button>
    <button type="submit" name="zapisz" id="zapisz" class="btn btn-primary">Zaakceptuj</button>
    </div>
    </div>
</div>

</head>
<script>
    $(document).ready(function(){
    
    $('#skill').tokenfield({
    autocomplete:{
    source: ['PHP','Codeigniter','HTML','JQuery','Javascript','CSS','Laravel','CakePHP','Symfony','Yii 2','Phalcon','Zend','Slim','FuelPHP','PHPixie','Mysql'],
    delay:100
    },
    showAutocompleteOnFocus: true
    });
    });
</script>
<script>
  function pokazOkno2() {
    document.getElementById("okno1").style.display = "none";
    document.getElementById("okno2").style.display = "block";
    document.getElementById("okno3").style.display = "none";
  }

  function pokazOkno3() {
    document.getElementById("okno2").style.display = "none";
    document.getElementById("okno3").style.display = "block"; 
    document.getElementById("okno4").style.display = "none";  
  }
  function pokazOkno1() {
    document.getElementById("okno2").style.display = "none";
    document.getElementById("okno1").style.display = "block";   
  }
  function pokazOkno4() {
    document.getElementById("okno3").style.display = "none";
    document.getElementById("okno4").style.display = "block";   
  }
</script>
<script>
    document.getElementById("userImage").addEventListener("change", function() {
        alert("Nowy plik został wybrany!");
    });
    </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const osobaPrywatnaRadio = document.getElementById("osoba_prywatna");
        const firmaRadio = document.getElementById("firma");
        const poleNazwaFirmy = document.getElementById("nazwapracy");
        const poleNazwaFirmy1 = document.getElementById("nazwapracy1");

        osobaPrywatnaRadio.addEventListener("change", function() {
            if (osobaPrywatnaRadio.checked) {
                poleNazwaFirmy.style.display = "none";
                poleNazwaFirmy1.style.display = "none";
            }
        });

        firmaRadio.addEventListener("change", function() {
            if (firmaRadio.checked) {
                poleNazwaFirmy.style.display = "block";
                poleNazwaFirmy1.style.display = "block";
            }
        });
        poleNazwaFirmy.style.display = "none";
        poleNazwaFirmy1.style.display = "none";
    });
</script>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            const firmaRadio = document.getElementById("firma");
            <?php
            if ($value_pracodawca <= 0){
            ?>
            firmaRadio.addEventListener("change", function() {
                if (firmaRadio.checked) {
                    location.reload();
                    window.location.href = "dodaj-pracodawce.php";
                }
            });
          <?php
            }
            ?>
        });
    </script>
</html>