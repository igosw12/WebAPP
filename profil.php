<?php
session_start();
@include 'authentication.php';
@include 'config.php';
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

require 'vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;

$user = $_SESSION['auth_user']['user'];
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];

$avatar_profile = "SELECT avatar FROM uzytkownicy WHERE user = '$user' LIMIT 1";
$result = mysqli_query($conn, $avatar_profile);

if($result && mysqli_num_rows($result) > 0)
{
    $row = mysqli_fetch_assoc($result);
    $avatar = $row['avatar'];
}
##imie
$name_profile = mysqli_query($conn, "SELECT imie FROM uzytkownicy WHERE user = '$user' LIMIT 1");
$row = mysqli_fetch_assoc($name_profile);
$value_imie = $row['imie'];
##nazwisko
$subname_profile = mysqli_query($conn, "SELECT nazwisko FROM uzytkownicy WHERE user = '$user' LIMIT 1");
$row = mysqli_fetch_assoc($subname_profile);
$value_nazwisko = $row['nazwisko'];
##numer_telefonu
$phone_profile = mysqli_query($conn, "SELECT telefon FROM uzytkownicy WHERE user = '$user' LIMIT 1");
$row = mysqli_fetch_assoc($phone_profile);
$value_telefon = $row['telefon'];
##adres
$wojewodztwo_profile = mysqli_query($conn, "SELECT wojewodztwo FROM uzytkownicy WHERE user = '$user' LIMIT 1");
$row = mysqli_fetch_assoc($wojewodztwo_profile);
$value_wojewodztwo = $row['wojewodztwo'];
##adres
$miasto_profile = mysqli_query($conn, "SELECT miasto FROM uzytkownicy WHERE user = '$user' LIMIT 1");
$row = mysqli_fetch_assoc($miasto_profile);
$value_miasto = $row['miasto'];
##adres
$ulica_profile = mysqli_query($conn, "SELECT ulica FROM uzytkownicy WHERE user = '$user' LIMIT 1");
$row = mysqli_fetch_assoc($ulica_profile);
$value_ulica = $row['ulica'];
##adres
$kodpocztowy_profile = mysqli_query($conn, "SELECT kodpocztowy FROM uzytkownicy WHERE user = '$user' LIMIT 1");
$row = mysqli_fetch_assoc($kodpocztowy_profile);
$value_kodpocztowy = $row['kodpocztowy'];
##adres
$zgodauzytkownika_profile = mysqli_query($conn, "SELECT zgodauzytkownika FROM uzytkownicy WHERE user = '$user' LIMIT 1");
$row = mysqli_fetch_assoc($zgodauzytkownika_profile);
$value_zgodauzytkownika = $row['zgodauzytkownika'];
##opis
$opis_profile = mysqli_query($conn, "SELECT opis FROM profil_zawodowy, uzytkownicy WHERE user = '$user' AND profil_zawodowy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_assoc($opis_profile);
$value_opis = $row['opis'];
##wyksztalcenie
$wyksztalcenie_profile = mysqli_query($conn, "SELECT wyksztalcenie FROM profil_zawodowy, uzytkownicy WHERE user = '$user' AND profil_zawodowy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_assoc($wyksztalcenie_profile);
$value_wyksztalcenie = $row['wyksztalcenie'];
##tytul
$tytul_profile = mysqli_query($conn, "SELECT tytul FROM profil_zawodowy, uzytkownicy WHERE user = '$user' AND profil_zawodowy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$row = mysqli_fetch_assoc($tytul_profile);
$value_tytul = $row['tytul'];
##umiejetnosci
$skill_profile = mysqli_query($conn, "SELECT skills FROM profil_zawodowy, uzytkownicy WHERE user = '$user' AND profil_zawodowy.id_uzytkownicy = uzytkownicy.id_uzytkownicy");
$skills = '';
if(mysqli_num_rows($skill_profile) > 0){
    $row = mysqli_fetch_assoc($skill_profile);
    $skills_array = explode(',', $row['skills']);
    $skills = implode(',', $skills_array);
}
$sql = "SELECT cv, filecontent FROM profil_zawodowy, uzytkownicy WHERE user = '$user' AND profil_zawodowy.id_uzytkownicy = uzytkownicy.id_uzytkownicy";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$filename = $row['cv'];

if(isset($_POST['zapisz'])){

    $imie = mysqli_real_escape_string($conn, $_POST['imie']);
    $nazwisko = mysqli_real_escape_string($conn, $_POST['nazwisko']);
    $region = mysqli_real_escape_string($conn, $_POST['region']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $street = mysqli_real_escape_string($conn, $_POST['street']);
    $kodpocztowy = mysqli_real_escape_string($conn, $_POST['kodpocztowy']);
    $zgoda = mysqli_real_escape_string($conn, $_POST['zgoda']);
    $telefon = mysqli_real_escape_string($conn, $_POST['telefon']);
    $tytul = mysqli_real_escape_string($conn, $_POST['tytul']);
    $opis = mysqli_real_escape_string($conn, $_POST['opis']);
    $wyksztalcenie = mysqli_real_escape_string($conn, $_POST['wyksztalcenie']);
    $skill = mysqli_real_escape_string($conn, $_POST['skill']);
    $target_dir = "uploads/";
    $folder = 'profilowe/'; 
    $target_file = $target_dir . basename($_FILES["resume"]["name"]);

    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["resume"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    $update_profile = "UPDATE uzytkownicy SET imie = '$imie', nazwisko = '$nazwisko', wojewodztwo = '$region', miasto = '$city', ulica = '$street', kodpocztowy = '$kodpocztowy', zgodauzytkownika = '$zgoda',  telefon = '$telefon' WHERE user = '$user' LIMIT 1";
    $update_profile_2 = "UPDATE profil_zawodowy SET tytul = '$tytul', opis = '$opis', wyksztalcenie = '$wyksztalcenie', skills = '$skill' WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1"; 
    $update_profile_run = mysqli_query($conn, $update_profile);
    $update_profile_run_2 = mysqli_query($conn, $update_profile_2);

    $filename = $_FILES["resume"]["name"];
    $filecontent = file_get_contents($target_file);
    $filecontent = mysqli_real_escape_string($conn, $filecontent);
    
    if(!empty($_FILES["resume"]) && $_FILES["resume"]["size"] > 0){
        $update_cv = "UPDATE profil_zawodowy SET cv = '$filename', filecontent = '$filecontent' WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1"; 
        $update_cv_run = mysqli_query($conn, $update_cv);
    }
    if(!empty($_FILES['userImage']['tmp_name']) && $_FILES['userImage']['size'] > 0) {
        $tmpPath = $_FILES['userImage']['tmp_name'];
        $compressedImagePath = 'profilowe/obrazka.jpg';
        $image = Image::make($tmpPath);
        $image->save($compressedImagePath, 80);
       $image = mysqli_real_escape_string($conn, $image);
       $update_profile_avatar = "UPDATE uzytkownicy SET avatar = '$image' WHERE user = '$user' LIMIT 1";
       $update_profile_avatar_run = mysqli_query($conn, $update_profile_avatar);
    }


    if($update_profile_run){
        $_SESSION['status'] = "Nowe dane zostały pomyślnie ustawione";
        $_SESSION['aktualne_dane'] = $_POST;
        header('Location: profil.php?id=' . $id_uzytkownicy);
    }else{
        $_SESSION['status'] = "Coś poszło nie tak";
        header('Location: profil.php?id=' . $id_uzytkownicy);
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
        <title>Profil</title>
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
                    <p>Opowiedz nam coś o sobie</p>
                </div>
                
                <div class="form-container">
                    <h1 id="user", name="user"><?= $_SESSION['auth_user']['user']. '  Twój numer ID:'.  $id_uzytkownicy;?></h1>
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
                        <label for="imie">Imię</label>
                        <input type="text" name="imie" id="imie" disabled pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" value="<?php echo $value_imie; ?>">
                        <label for="nazwisko">Nazwisko</label>
                        <input type="text" name="nazwisko" id="nazwisko" disabled pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" value="<?php echo $value_nazwisko; ?>">
                        <label for="telefon">Numer telefonu</label>
                        <input type="text" name="telefon" id="telefon" disabled pattern="^[1-9][0-9]{8}$" maxlength="9" value ="<?php echo $value_telefon; ?>">
                        <label for="adres">Województwo</label>
                        <input type="text" name="region" id="region" disabled pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" value ="<?php echo $value_wojewodztwo; ?>">
                        <label for="adres">Miasto</label>
                        <input type="text" name="city" id="city" disabled pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" value ="<?php echo $value_miasto; ?>">
                        <label for="adres">Ulica</label>
                        <input type="text" name="street" id="street" disabled value ="<?php echo $value_ulica; ?>">
                        <label for="adres">Kod pocztowy</label>
                        <input type="text" name="kodpocztowy" id="kodpocztowy" disabled pattern="[0-9]{2}-[0-9]{3}" value ="<?php echo $value_kodpocztowy; ?>">

                    </div>
                    <div class="sideByside">
                        <div class="form-item">
                            <label for="wyksztalcenie">Stopień wykształcenia</label>
                            <select name="wyksztalcenie" id="wyksztalcenie" value=<?php echo $value_wyksztalcenie; ?> disabled onchange ="enableTytul()">
                                <option ><?php echo $value_wyksztalcenie; ?></option>
                                <option value=""></option>
                                <option value="Podstawowe">Podstawowe</option>
                                <option value="Średnie">Średnie</option>
                                <option value="Wyższe">Wyższe</option>
                                <option value="Podyplomowe">Podyplomowe</option>
                                <option value="Ustawiczne">Ustawiczne</option>
                            </select>
                        </div>
                        <div class="form-item">
                            <label for="tytul">Tytuł naukowy</label>
                            <select name="tytul" id="tytul" disabled value=<?php echo $value_tytul; ?> disabled>
                                <option ><?php echo $value_tytul; ?></option>
                                <option value=""></option>
                                <option value="Licencjat">Licencjat</option>
                                <option value="Inżynier">Inżynier</option>
                                <option value="Magister">Magister</option>
                                <option value="Doktor">Doktor</option>
                                <option value="Doktor habilitowany">Doktor habilitowany</option>
                                <option value="Profesor">Profesor</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-item">
                        <label for="opis">Opowiedz nam o sobie</label>
                        <textarea id="opis" name="opis" id="" rows="7" disabled><?php echo $value_opis; ?></textarea>
                    </div>
                    <div class="form-item">
                            <label for="wyksztalcenie">Czy chcesz aby twoje konto wyświetlało się w propozycjach dla pracodawców zgodnie z &nbsp<a href="regulamin.php"> regulaminem. </a> </label>
                            <select name="zgoda" id="zgoda" value=<?php echo $value_zgodauzytkownika; ?> disabled onchange ="enableTytul()">
                                <option ><?php echo $value_zgodauzytkownika; ?></option>
                                <option value=""></option>
                                <option value="Tak">Tak</option>
                                <option value="Nie">Nie</option>
                            </select>
                        </div>

                </div>
                <hr />
                <div class="description">
                    <h4>Twoje umiejętności</h4>
                    <p>Podaj swoje umiejętności</p>
                </div>
                <div class="form-container">
                    <div class="form-item1">
                    <input type="text" name="skill" id="skill" value="<?php echo $skills; ?>"/>
                    </div>
                </div>
                <hr />
                <div class="description">
                    <h4>Załączniki</h4>
                    <p>Dodaj swoje CV</p>
                    <p>Obsługiwane pliki: PDF, DOCX, PPTX</p>
                </div>
                <div class="form-container resume-container">
                    <input type="file" name="resume" id="resume" class="custom-file-input file-resume" accept=".pdf ,.docx,.pptx" />
                </div>
                <p>Pobierz plik: <a href="uploads/<?php echo $filename; ?>" download="<?php echo $filename; ?>"><?php echo $filename; ?></a></p>
                <?php
                if (!empty($resume)) {
                echo '<a href="download.php?id='.$file['id'].'">Pobierz plik</a>';
                }
                ?>
                <button type="submit" name="zapisz" id="zapisz" class="btn btn-primary" disabled>Zapisz</button>
                <button type="button" name="edytuj" id="edytuj" class="btn btn-primary" onclick ="enableInput()">Edytuj</button>
            </form>
        </div>
    </body>

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
    function enableInput() {
    document.getElementById("imie").disabled = false;
    document.getElementById("nazwisko").disabled = false;
    document.getElementById("telefon").disabled = false;
    document.getElementById("city").disabled = false;
    document.getElementById("street").disabled = false;
    document.getElementById("region").disabled = false;
    document.getElementById("kodpocztowy").disabled = false;
    document.getElementById("opis").disabled = false;
    document.getElementById("zgoda").disabled = false;
    document.getElementById("wyksztalcenie").disabled = false;
    var select1 = document.getElementById("tytul");
    var select2 = document.getElementById("wyksztalcenie");
        if (select2.value == "Wyższe" || select2.value == "Podyplomowe" || select2.value == "Ustawiczne") {
        select1.disabled = false;
        } else {
        select1.disabled = true;
        }
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
