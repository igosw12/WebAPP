<?php
error_reporting(0);
session_start();
@include 'authentication.php';
@include 'config.php';
header("Pragma: no-cache");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");

require 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;

$user = $_SESSION['auth_user']['user'];
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];

$avatar_profile = "SELECT Logo FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy LIMIT 1";
$result = mysqli_query($conn, $avatar_profile);

if($result && mysqli_num_rows($result) > 0)
{
    $row = mysqli_fetch_assoc($result);
    $avatar = $row['Logo'];
}
##nazwa
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
##wojewodztwo
$Wojewodztwo_profile = mysqli_query($conn, "SELECT pracodawcy.Wojewodztwo FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_assoc($Wojewodztwo_profile);
$value_Wojewodztwo = $row['Wojewodztwo'];
##opis
$opis_profile = mysqli_query($conn, "SELECT opis FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_assoc($opis_profile);
$value_opis = $row['opis'];
##NIP
$NIP_profile = mysqli_query($conn, "SELECT NIP FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_assoc($NIP_profile);
$value_NIP = $row['NIP'];
##umiejetnosci
$skill_profile = mysqli_query($conn, "SELECT cechy FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$skills = '';
if(mysqli_num_rows($skill_profile) > 0){
    $row = mysqli_fetch_assoc($skill_profile);
    $skills_array = explode(',', $row['cechy']);
    $skills = implode(',', $skills_array);
}
##upowaznienia
$upowa_profile = mysqli_query($conn, "SELECT uprawnienia FROM pracodawcy, uzytkownicy WHERE user = '$user' AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$upowaznienia = '';
if(mysqli_num_rows($upowa_profile) > 0){
    $row = mysqli_fetch_assoc($upowa_profile);
    $upowaznienia_array = explode(',', $row['uprawnienia']);
    $upowaznienia = implode(',', $upowaznienia_array);
}

if(isset($_POST['zapisz'])){

    $nazwa = mysqli_real_escape_string($conn, $_POST['nazwa']);
    $Miasto = mysqli_real_escape_string($conn, $_POST['miasto']);
    $Ulica = mysqli_real_escape_string($conn, $_POST['ulica']);
    $KodPocztowy = mysqli_real_escape_string($conn, $_POST['kodpocztowy']);
    $Wojewodztwo = mysqli_real_escape_string($conn, $_POST['wojewodztwo']);
    $NIP = mysqli_real_escape_string($conn, $_POST['NIP']);
    $opis = mysqli_real_escape_string($conn, $_POST['opis']);
    $skill = mysqli_real_escape_string($conn, $_POST['skill']);
    $permission = mysqli_real_escape_string($conn, $_POST['permission']);

    $sql = "SELECT * FROM pracodawcy WHERE id_uzytkownicy=$id_uzytkownicy";
    $result = mysqli_query($conn, $sql);

    $NIP_query = "SELECT * FROM pracodawcy WHERE NIP = '$NIP' AND id_uzytkownicy!=$id_uzytkownicy LIMIT 1";
    $NIP_query_run = mysqli_query($conn, $NIP_query);

    if(mysqli_num_rows($NIP_query_run) > 0){
        $_SESSION['status'] = "Dla danego numeru NIP istnieje już firma";
        header('Location: dodaj-pracodawce.php?username=' . $user);
        echo '<script>document.getElementById("NIP").removeAttribute("disabled");</script>';
        exit(0);
    }else{
        if (mysqli_num_rows($result) > 0) {
            $update_profile_2 = "UPDATE pracodawcy SET nazwa = '$nazwa', opis = '$opis', Miasto = '$Miasto', Wojewodztwo = '$Wojewodztwo', Ulica = '$Ulica', KodPocztowy = '$KodPocztowy', NIP = '$NIP', cechy = '$skill', uprawnienia = '$permission' WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1"; 
            $update_profile_run_2 = mysqli_query($conn, $update_profile_2);
            
        } else {
            $insert1 = "INSERT INTO pracodawcy (id_uzytkownicy) VALUES('$id_uzytkownicy')";
            $query_run = mysqli_query($conn, $insert1);
            $update_profile_2 = "UPDATE pracodawcy SET nazwa = '$nazwa', opis = '$opis', Wojewodztwo = '$Wojewodztwo', Miasto = '$Miasto', Ulica = '$Ulica', KodPocztowy = '$KodPocztowy', NIP = '$NIP', cechy = '$skill', uprawnienia = '$permission' WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1"; 
            $update_profile_run_2 = mysqli_query($conn, $update_profile_2);
        }

        if(!empty($_FILES['userImage']['tmp_name']) && $_FILES['userImage']['size'] > 0) {
                $tmpPath = $_FILES['userImage']['tmp_name'];
                $compressedImagePath = 'fimowe_profilowe/obrazka.jpg';
                $image = Image::make($tmpPath);
                $image->save($compressedImagePath, 80);
               $image = mysqli_real_escape_string($conn, $image);
               $update_profile_avatar = "UPDATE pracodawcy SET Logo = '$image' WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1";
               $update_profile_avatar_run = mysqli_query($conn, $update_profile_avatar);
        }
    }
    if($update_profile_run_2){
        $_SESSION['status'] = "Nowe dane zostały pomyślnie ustawione";
        $_SESSION['aktualne_dane'] = $_POST;
        header('Location: dodaj-pracodawce.php?id=' . $id_uzytkownicy);
        exit(0);
    }else{
        $_SESSION['status'] = "Coś poszło nie tak";
        header('Location: dodaj-pracodawce.php?id=' . $id_uzytkownicy);
        exit(0);
    }
    if (isset($_SESSION['aktualne_dane'])) {
        $aktualne_dane = $_SESSION['aktualne_dane'];
        unset($_SESSION['aktualne_dane']);
    }
}
?>




<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
        <link rel="stylesheet" href="css/index.css" />
        <link rel="stylesheet" href="css/style2.css" />
        <script src="profil.js"></script>
        <title>Profil firmy</title>
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



        <div class="container">

            <form action = "" method="post" id="update" class="main-form-container" onsubmit="onFormSubmitHandler(event)" enctype="multipart/form-data">
                <div class="description">
                <div class="alert">
                <?php
                if(isset($_SESSION['status']))
                {
                    echo "<h4>".$_SESSION['status']."</h4>";
                    unset($_SESSION['status']);
                }
                ?>
                </div>
                    <h4>O mnie</h4>
                    <p>Opowiedz nam coś o Twojej firmie</p>
                </div>
                <div class="form-container">
                    <h1 id="user", name="user"><?= $_SESSION['auth_user']['user']; ?></h1>
                    <hr />
                    <div class="user-image-upload">
                        <div class="user-image-container">
                        <?php
                        if (isset($avatar)) {
                            echo '<img src="data:avatar/jpeg;base64,'.base64_encode($avatar).'" alt="user-image" id="user-image" />';
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
                    <div class="form-item">
                        <label for="nazwa">Nazwa</label>
                        <input type="text" name="nazwa" id="nazwa" required disabled pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" value="<?php echo $value_nazwa; ?>">
                        <label for="NIP">Numer NIP</label>
                        <input type="text" name="NIP" id="NIP" required disabled pattern="[0-9]{10}" maxlength="10" title="Pole NIP powinno składać się z 10 cyfr" value="<?php echo $value_NIP; ?>">
                        <label for="wojewodztwo">Województwo</label>
                        <input type="text" name="wojewodztwo" id="wojewodztwo" required disabled pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" value="<?php echo $value_Wojewodztwo; ?>">
                        <label for="miasto">Miasto</label>
                        <input type="text" name="miasto" id="miasto" required disabled pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" value="<?php echo $value_Miasto; ?>">
                        <label for="ulica">Ulica</label>
                        <input type="text" name="ulica" id="ulica" required disabled value="<?php echo $value_Ulica; ?>">
                        <label for="kodpocztowy">Kod pocztowy</label>
                        <input type="text" name="kodpocztowy" id="kodpocztowy" required disabled pattern="[0-9]{2}-[0-9]{3}" value="<?php echo $value_KodPocztowy; ?>">
                    </div>
                    <div class="form-item">
                        <label for="opis">Opis firmy</label>
                        <textarea id="opis" name="opis" id="" rows="7" disabled><?php echo $value_opis; ?></textarea>
                    </div>
                </div>
                <hr />
                <div class="description">
                    <h4>Cechy firmy</h4>
                    <p>Podaj specjalizacje danej przedsiębierczości</p>
                </div>
                <div class="form-container">
                    <div class="form-item1">
                    <input type="text" name="skill" id="skill" value="<?php echo $skills; ?>"/>
                    </div>
                </div>
                <hr />
                <div class="description">
                    <h4>Nadaj administratorów</h4>
                    <p>Dodaj osoby upoważnione za pomocą numerów ID użytkowników</p>
                </div>
                <div class="form-container">
                    <div class="form-item2">
                    <input type="text" name="permission" id="permission" value="<?php echo $upowaznienia; ?>"/>
                    </div>
                </div>
                <hr />
                <button type="submit" name="zapisz" id="zapisz" class="btn btn-primary" disabled>Zapisz</button>
                <button type="button" name="edytuj" id="edytuj" class="btn btn-primary" onclick ="enableInput()">Edytuj</button>
            </form>
            
        </div>
    </body>

    <script>
    $(document).ready(function(){
    
    $('#skill').tokenfield({
    autocomplete:{
    source: ['Wyścigi','Programista','Bankowość','Kredyty','Kredyt','Informatyka','Sklep','Odzież','AGD','RTV','Szkoła','Studia','GameDev','Studio','Florystyka','Księgowość'],
    delay:100
    },
    showAutocompleteOnFocus: true
    });
    });
</script>
<script>
    $(document).ready(function(){
    
    $('#permission').tokenfield({
    autocomplete:{
    delay:100
    },
    showAutocompleteOnFocus: true
    });
    });
</script>

    <script>
    function enableInput() {
    document.getElementById("nazwa").disabled = false;
    document.getElementById("NIP").disabled = false;
    document.getElementById("miasto").disabled = false;
    document.getElementById("ulica").disabled = false;
    document.getElementById("kodpocztowy").disabled = false;
    document.getElementById("wojewodztwo").disabled = false;
    document.getElementById("opis").disabled = false;
    document.getElementById("skill").disabled = false;
    document.getElementById("zapisz").disabled = false;
    }
    </script>
    <script>
    function enableTytul() {
        var select1 = document.getElementById("tytul");
        var select2 = document.getElementById("wyksztalcenie");

    if (select2.value == "Wyższe" || select2.value == "Podyplomowe" || select2.value == "Ustawiczne") {
      select1.value = "<?php echo $value_tytul; ?>"
      select1.disabled = false;
    } else {
      select1.value = "";
      select1.disabled = true;
      
    }
  }
    </script>
    <script>
    document.getElementById("userImage").addEventListener("change", function() {
        alert("Nowy plik został wybrany!");
    });
    </script>

</html>
