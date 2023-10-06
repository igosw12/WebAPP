<?php
@include 'authentication.php';
@include 'config.php';
error_reporting(0);
session_start();
header("Cache-Control: no-cache, must-revalidate");
$id_oferty = $_GET['id'];
$id_pomocnicze = $id_oferty;
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];

$query1 = "SELECT id_uzytkownicy FROM oferty_pracy WHERE id_oferty_pracy = $id_oferty";
$result = mysqli_query($conn, $query1);
$row = $result->fetch_assoc();
$value = $row['id_uzytkownicy'];

$query3 = "SELECT * FROM oferty_pracy WHERE id_oferty_pracy = $id_oferty AND id_pracodawcy IS NULL";
$wynik = mysqli_query($conn, $query3);

$query = "SELECT * FROM pracodawcy, oferty_pracy WHERE id_oferty_pracy = $id_oferty AND oferty_pracy.id_pracodawcy = pracodawcy.id_pracodawcy AND FIND_IN_SET('$id_uzytkownicy', REPLACE(pracodawcy.uprawnienia, ' ', ''))";
$result5 = mysqli_query($conn, $query);
##echo $result5;
if($value == $id_uzytkownicy OR mysqli_num_rows($result5) > 0) {
##    
}else {
  header("Location: index.php");
}


$query = "SELECT * FROM oferty_pracy WHERE id_oferty_pracy = $id_oferty";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$value_id = $row['id_oferty'];
$value_miasto = $row['miasto'];
$value_ulica = $row['ulica'];
$value_kodpocztowy = $row['kod_pocztowy'];
$value_wojewodztwo = $row['wojewodztwo'];
$value_tytul = $row['tytul'];
$value_opis = $row['opis'];
$value_data_waznosci = $row['data_waznosci'];
$value_wynagrodzenie = $row['wynagrodzenie'];
$value_rodzaj_umowy = $row['rodzaj_umowy'];
$value_tryb_pracy = $row['tryb_pracy'];
$value_wymiar_pracy = $row['wymiar_pracy'];
$value_poziom_stanowiska = $row['poziom_stanowiska'];
$value_wymagania = $row['wymagania'];
$value_kwalfikacje = $row['kwalfikacje'];
$value_nazwa_pracy = $row['nazwapracy'];  
$avatar_profile = "SELECT zdjecie_promo FROM oferty_pracy WHERE id_oferty_pracy = $id_oferty LIMIT 1";
$result = mysqli_query($conn, $avatar_profile);
$wybrane_opcje = explode(',', $value_poziom_stanowiska);
$skill_profile = mysqli_query($conn, "SELECT tags FROM oferty_pracy WHERE id_oferty_pracy = $id_oferty");
$skills = '';
if(mysqli_num_rows($skill_profile) > 0){
    $row = mysqli_fetch_assoc($skill_profile);
    $skills_array = explode(',', $row['tags']);
    $skills = implode(',', $skills_array);
}

if($result && mysqli_num_rows($result) > 0)
{
    $row = mysqli_fetch_assoc($result);
    $logo = $row['zdjecie_promo'];
} 
if($value == $id_uzytkownicy OR mysqli_num_rows($result5) >= 0){
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
    $opis = mysqli_real_escape_string($conn, $_POST['opis']);
    $wymagania = mysqli_real_escape_string($conn, $_POST['wymagania']);
    $kwalfikacje = mysqli_real_escape_string($conn, $_POST['kwalfikacje']);
    $wynagrodzenie = mysqli_real_escape_string($conn, $_POST['wynagrodzenie']);
    $skill = mysqli_real_escape_string($conn, $_POST['skill']);
    $current_date_time = date('Y-m-d H:i:s');

    $opcjestonowisko = implode(", ", $Poziomstanowsika);
    $opcjewymiar = implode(", ", $Wymiarpracy);
    $opcjerodzaj = implode(", ", $Rodzajumowy);
    $opcjetryb = implode(", ", $Trybpracy);

    $update_profile_2 = "UPDATE oferty_pracy SET tytul = '$tytul', opis = '$opis', data_dodania = '$current_date_time',
    data_waznosci = '$data', wynagrodzenie = '$wynagrodzenie', nazwapracy='$nazwapracy', miasto = '$miasto', wojewodztwo = '$wojewodztwo', rodzaj_umowy = '$opcjerodzaj', tryb_pracy = '$opcjetryb',
    wymiar_pracy = '$opcjewymiar', poziom_stanowiska = '$opcjestonowisko', wymagania = '$wymagania', tags = '$skill', kwalfikacje = '$kwalfikacje', kod_pocztowy = '$kodpocztowy', ulica = '$ulica' 
    WHERE id_oferty_pracy = '$id_oferty' LIMIT 1"; 
    $update_profile_run_2 = mysqli_query($conn, $update_profile_2);

    if($update_profile_run_2){
        header("Location: job-details.php?id=" . $id_oferty);
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
    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <!-- icons -->
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
	<title></title>
	<div class="form-container">
  <form action="" method="post" enctype="multipart/form-data">
  <div class="form-window active" id="okno1">
    <label for="krok"> Krok 1/4 </label>
    <label for="tytul">Tytuł ogłoszenia:</label>
	<input type="text" name="tytul" id="tytul" required value="<?php echo $value_tytul; ?>"><br>
	<label for="data">Data ważności ogłoszenia:</label>
	<input type="date" name="data" id="data" required value="<?php echo $value_data_waznosci; ?>"><br>
    <label for="nazwapracy">Nazwa firmy:</label>
  <?php if(mysqli_num_rows($wynik) <= 0){ ?>
	<input type="text" name="nazwapracy" id="nazwapracy" required value="<?php echo $value_nazwa_pracy; ?>"><br>
    <label for="miejscepracy">Miejsce pracy:</label>
  <?php } ?>
	<input type="text" name="miasto" id="miasto" required placeholder="Nazwa miasta" value="<?php echo $value_miasto; ?>"><br>
  <input type="text" name="ulica" id="ulica" required placeholder="Nazwa ulicy i numer budynku" value="<?php echo $value_ulica; ?>"><br>
  <input type="text" name="kodpocztowy" id="kodpocztowy" required pattern="[0-9]{2}-[0-9]{3}" placeholder="Kod pocztowy" value="<?php echo $value_kodpocztowy; ?>"><br>
  <input type="text" name="wojewodztwo" id="wojewodztwo" required placeholder="Województwo" value="<?php echo $value_wojewodztwo; ?>"><br>
    <div class="buttons">
    <button type="button" class="prev-button" id="prev" onclick="" disabled>Anuluj</button>
    <button type="button" class="next-button" id="next" onclick="pokazOkno2()">Kontynuuj</button>
    </div>
  </div>

  <div id="okno2" class="form-window active" style="display:none">
  <label for="krok"> Krok 2/4 </label>
  <div class="filter-box">
  <label for="Poziom-stanowsika">Poziom stanowiska:</label>
            <?php    
            $stanowiska = array('Młodszy specjalista(Junior)', 'Specjalista(Mid)', 'Starszy specjalista(Senior)', 'Dyrektor');
            ?>
            <select class="filter-select" id="Poziom-stanowsika" name="Poziom-stanowsika[]" multiple required>
            <option disabled value ="">Poziom Stanowiska</option>
              <?php
              foreach ($stanowiska as $stanowisko) {
                $sel1 = (strpos($value_poziom_stanowiska, $stanowisko) !== false) ? 'selected' : '';
                echo '<option value="' . $stanowisko . '" ' . $sel1 . '>' . $stanowisko . '</option>';
              }
              ?>
            </select>
            <label for="Wymiar-pracy">Wymiar pracy:</label>
            <?php    
            $wymiary = array('Część etatu', 'Dodatkowa/Tymczasowa', 'Pełny etat');
            ?>
            <select class="filter-select" id="Wymiar-pracy[]" name="Wymiar-pracy[]" required multiple>
                <option disabled value ="">Wymiar pracy</option>
                <?php
                foreach ($wymiary as $wymiar) {
                $sel2 = (strpos($value_wymiar_pracy, $wymiar) !== false) ? 'selected' : '';
                echo '<option value="' . $wymiar . '" ' . $sel2 . '>' . $wymiar . '</option>';
              }
              ?>
            </select>
            <label for="Rodzaj-umowy">Rodzaj umowy:</label>
            <?php    
            $umowy = array('Umowa o pracę', 'Umowa o dzieło', 'Umowa zlecenie', 'Kontrakt B2B', 'Umowa o staż/praktyki');
            ?>
            <select class="filter-select" id="Rodzaj-umowy[]" name="Rodzaj-umowy[]" required multiple>
                <option disabled value ="">Rodzaj umowy</option>
                <?php
                foreach ($umowy as $umowa) {
                $sel3 = (strpos($value_rodzaj_umowy, $umowa) !== false) ? 'selected' : '';
                echo '<option value="' . $umowa . '" ' . $sel3 . '>' . $umowa . '</option>';
              }
              ?>
            </select>
            <label for="Tryb-pracy">Tryb pracy:</label>
            <?php    
            $tryby = array('Praca Stacjonarna', 'Praca Zdalna', 'Praca Hybrydowa');
            ?>
            <select class="filter-select" id="Tryb-pracy[]" name="Tryb-pracy[]" required multiple>
            <option disabled value ="">Rodzaj umowy</option>
            <?php
                foreach ($tryby as $tryb) {
                $sel4 = (strpos($value_tryb_pracy, $tryb) !== false) ? 'selected' : '';
                echo '<option value="' . $tryb . '" ' . $sel4 . '>' . $tryb . '</option>';
              }
              ?>
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
      <textarea id="opis" name="opis" id="" rows="10"><?php echo $value_opis; ?></textarea>
    </div>
    <div class="form-item">
      <label for="wymagania">Wymagania</label>
      <textarea id="wymagania" name="wymagania" id="" rows="10"><?php echo $value_wymagania; ?></textarea>
    </div>
    <div class="form-item">
      <label for="kwalfikacje">Kwalfikacje</label>
      <textarea id="kwalfikacje" name="kwalfikacje" id="" rows="10" ><?php echo $value_kwalfikacje; ?></textarea>
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
      <input type="text" name="wynagrodzenie" id="wynagrodzenie" required pattern="[0-9]+(\.[0-9]{1,2})?" value=<?php echo $value_wynagrodzenie; ?>><br>
    </div>
                    <div class="form-item1">
                    <input type="text" name="skill" id="skill" value="<?php echo $skills; ?>"/>
                    </div>
    
                    <div class="user-image-upload">
                        <div class="user-image-container">
                        <?php
                        if (isset($logo)) {
                            echo '<img src="data:logo/jpeg;base64,'.base64_encode($logo).'" alt="user-image" id="user-image" />';
                        } else {
                            echo '<img src="img/User-avatar.svg.png" alt="user-image" id="user-image" />';
                        }
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
    

    <div class="buttons">
    <button type="button" class="prev-button" id="prev" onclick="pokazOkno3()">Cofnij</button>
    <button type="submit" name="zapisz" id="zapisz" class="btn btn-primary">Edytuj ogłoszenie</button>
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
</html>

<?php
}else{
    header("Location: job-details.php?id='$id_oferty'");
} 
?>