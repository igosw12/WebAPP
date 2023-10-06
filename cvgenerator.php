<?php
session_start();
@include 'authentication.php';
@include 'config.php';
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
##error_reporting(0);

$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];

$sql = "SELECT cv, filecontent FROM cvgenerator WHERE id_uzytkownicy = '$id_uzytkownicy'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$filename1 = $row['cv'];

require_once('tfpdf/tfpdf.php');




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $subname = $_POST['subname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $region = $_POST['region'];
    $doswiadczenie = $_POST['doswiadczenie'];
    $skill = $_POST['skill'];
    $zainteresowania = $_POST['zainteresowania'];
    $jezyki = $_POST['jezyki'];
    $wyksztalcenia = $_POST['wyksztalcenia'];
    $data_urodzenia = $_POST['data_urodzenia'];

    
    $pdf = new tFPDF();
    $pdf->AddPage();

    $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
    $pdf->SetFont('DejaVu','',14);
    $pdf->Cell(0, 10, 'Curriculum Vitae', 0, 1, 'C');

    $pdf->SetFont('DejaVu','',22);
    $tekst = $name . " " . $subname;
    $pdf->SetTextColor(0, 118, 222);
    $pdf->Cell(0, 10, $tekst, 0, 1);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('DejaVu','',12);
    

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tempFilePath = $_FILES['image']['tmp_name'];
        $convertedFilePath = 'converted.jpg'; 
        $image = imagecreatefromstring(file_get_contents($tempFilePath));
        imagejpeg($image, $convertedFilePath, 75);
        imagedestroy($image);

        list($width, $height) = getimagesize($convertedFilePath);
            
            $maxWidth = 70; 
            $maxHeight = 70; 
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $scaledWidth = $width * $ratio;
            $scaledHeight = $height * $ratio;
            

            $x = 130; 
            $y = 18; 
        $pdf->Image('converted.jpg', $x, $y, $scaledWidth, $scaledHeight, 'JPEG');
    }
    $pdf->SetFont('DejaVu','',10);
    $pdf->Cell(40, 10, 'Email: ', 0);
    $pdf->Cell(0, 10, $email, 0, 1);

    $pdf->Cell(40, 10, 'Telefon: ', 0);   
    $pdf->Cell(0, 10, $phone, 0, 1);
    
    $pdf->Cell(40, 10, 'Data urodzenia: ', 0);   
    $pdf->Cell(0, 10, $data_urodzenia, 0, 1);

    $pdf->Cell(40, 10, 'Miasto: ', 0);   
    $pdf->Cell(0, 10, $city, 0, 1);

    $pdf->Cell(40, 10, 'Region: ', 0);   
    $pdf->Cell(0, 10, $region, 0, 1);

    $pdf->Cell(40, 10, 'Kraj: ', 0);   
    $pdf->Cell(0, 10, $country, 0, 1);

    $pageWidth = $pdf->GetPageWidth();
    $pageHeight = $pdf->GetPageHeight();

    $lineWidth = 0.91 * $pageWidth; 
    $lineX = ($pageWidth - $lineWidth) / 2;
    $lineY = $pdf->GetY();

    $pdf->SetDrawColor(25, 19, 182);
    $pdf->SetFillColor(0, 19, 182);
    $pdf->SetLineWidth(1.5);

    $pdf->SetFont('DejaVu', '', 14);
    $pdf->SetTextColor(255, 255, 255); 

    $sectionText = 'Znajomość języków obcych';

    $lineY = $pdf->GetY(); 

    $pdf->Cell($lineWidth, 8, $sectionText, 1, 0, 'C', true); 

    $pdf->SetTextColor(0, 0, 0); 
    $pdf->Ln(); 


    $szerokosc_komorek = 50;
    $wysokosc_linii = 10;
    $pdf->SetFont('DejaVu', '', 12);
    foreach ($jezyki as $jezyk) {
        $nazwa = $jezyk['nazwa'];
        $poziom = $jezyk['poziom'];
        $pdf->Cell($szerokosc_komorek, 10, '•' .$nazwa, 0, 0);
        $pdf->Cell(10, 10, '', 0, 0);
        $pozycja_x = $pdf->GetX();
        $pozycja_y = $pdf->GetY();
        $pozycja_x = $pdf->GetX();
        $pozycja_y = $pdf->GetY();
        $pdf->Cell($szerokosc_komorek, 10, '- poziom ' . $poziom, 0, 1);
    }

    $pageWidth = $pdf->GetPageWidth(); 
    $pageHeight = $pdf->GetPageHeight(); 

    $lineWidth = 0.91 * $pageWidth; 
    $lineX = ($pageWidth - $lineWidth) / 2; 
    $lineY = $pdf->GetY(); 

    $pdf->SetDrawColor(25, 19, 182); 
    $pdf->SetFillColor(0, 19, 182);
    $pdf->SetLineWidth(1.5); 

    $pdf->SetFont('DejaVu', '', 14);
    $pdf->SetTextColor(255, 255, 255); 

    $sectionText = 'Wykształcenie';
    $sectionWidth = $pdf->GetStringWidth($sectionText);

    $lineY = $pdf->GetY(); 

    $pdf->Cell($lineWidth, 8, $sectionText, 1, 0, 'C', true); 

    $pdf->SetTextColor(0, 0, 0); 
    $pdf->Ln(); 
    $niestandardowy_znak = "•";
    
   
    foreach ($wyksztalcenia as $wyksztalcenie) {
        $pdf->SetFont('DejaVu', '', 12);
        $name = $wyksztalcenie['name'];
        $kierunek = $wyksztalcenie['kierunek'];
        $level = $wyksztalcenie['level'];
        $title = $wyksztalcenie['title'];
        $pdf->SetTextColor(0, 118, 222);
        $pdf->Cell(0, 10, '•' . $name, 0, 1);
        $pdf->Cell(0, 6, '  ' . $kierunek, 0, 1);
        $pdf->SetTextColor(114, 113, 118);
        $pdf->SetFont('DejaVu', '', 10);
        $pdf->Cell(0, 6, '   ' .$title, 0, 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln(5); 
        }

        $pageWidth = $pdf->GetPageWidth(); 
        $pageHeight = $pdf->GetPageHeight(); 
    
        $lineWidth = 0.91 * $pageWidth; 
        $lineX = ($pageWidth - $lineWidth) / 2; 
        $lineY = $pdf->GetY(); 
    
        $pdf->SetDrawColor(25, 19, 182); 
        $pdf->SetFillColor(0, 19, 182);
        $pdf->SetLineWidth(1.5); 
    
        $pdf->SetFont('DejaVu', '', 14);
        $pdf->SetTextColor(255, 255, 255); 
    
        $sectionText = 'Doświadczenie zawodowe';
        $sectionWidth = $pdf->GetStringWidth($sectionText);
    
        $lineY = $pdf->GetY(); 
    
        $pdf->Cell($lineWidth, 8, $sectionText, 1, 0, 'C', true); 
    
        $pdf->SetTextColor(0, 0, 0); 
        $pdf->Ln(); 

        $pdf->SetFont('DejaVu', '', 10);
        $pdf->MultiCell(0, 10, $doswiadczenie);
        $pdf->Ln(10);


        $pageWidth = $pdf->GetPageWidth(); 
        $pageHeight = $pdf->GetPageHeight(); 
    
        $lineWidth = 0.91 * $pageWidth; 
        $lineX = ($pageWidth - $lineWidth) / 2; 
        $lineY = $pdf->GetY(); 
    
        $pdf->SetDrawColor(25, 19, 182); 
        $pdf->SetFillColor(0, 19, 182);
        $pdf->SetLineWidth(1.5); 
    
        $pdf->SetFont('DejaVu', '', 14);
        $pdf->SetTextColor(255, 255, 255); 
    
        $sectionText = 'Umiejętności';
        $sectionWidth = $pdf->GetStringWidth($sectionText);
    
        $lineY = $pdf->GetY(); 
    
        $pdf->Cell($lineWidth, 8, $sectionText, 1, 0, 'C', true); 
    
        $pdf->SetTextColor(0, 0, 0); 
        $pdf->Ln(); 
        $pdf->SetFont('DejaVu', '', 10);
        $pdf->MultiCell(0, 10, $skill);
        $pdf->Ln(10);



        $pageWidth = $pdf->GetPageWidth(); 
        $pageHeight = $pdf->GetPageHeight(); 
    
        $lineWidth = 0.91 * $pageWidth; 
        $lineX = ($pageWidth - $lineWidth) / 2; 
        $lineY = $pdf->GetY(); 
    
        $pdf->SetDrawColor(25, 19, 182); 
        $pdf->SetFillColor(0, 19, 182);
        $pdf->SetLineWidth(1.5); 
    
        $pdf->SetFont('DejaVu', '', 14);
        $pdf->SetTextColor(255, 255, 255);
    
        $sectionText = 'Zainteresowania';
        $sectionWidth = $pdf->GetStringWidth($sectionText);
    
        $lineY = $pdf->GetY(); 
    
        $pdf->Cell($lineWidth, 8, $sectionText, 1, 0, 'C', true); 
    
        $pdf->SetTextColor(0, 0, 0); 
        $pdf->Ln(); 
        $pdf->SetFont('DejaVu', '', 10);
        $pdf->SetLineWidth(0.1);
        $pdf->MultiCell($lineWidth, 10, $zainteresowania, 0, 0);
        $pdf->Ln(10);
    $filename = 'cv_' . uniqid() . '.pdf';
    $pdfContent = $pdf->Output('cv-generator/' . $filename, 'F');

    $sql = "SELECT * FROM cvgenerator WHERE id_uzytkownicy=$id_uzytkownicy";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $query = "UPDATE cvgenerator SET cv = ?, filecontent = ? WHERE id_uzytkownicy=$id_uzytkownicy";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sb", $filename, $pdfContent);
        $stmt->execute();
        header('Location: cvgenerator.php');
        $stmt->close();
    } else {
        $query = "INSERT INTO cvgenerator (cv, filecontent, id_uzytkownicy) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sbi", $filename, $pdfContent, $id_uzytkownicy);
        $stmt->execute();
        header('Location: cvgenerator.php');
        $stmt->close();    
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
        <link rel="stylesheet" href="css/style6.css" />
        
        <script src="profil.js"></script>
        <script src="cv.js"></script>
    <title>CV Generator</title>
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
    <h1>CV Generator</h1>
    <section class="cv-details">
    <form method="POST" action="" enctype="multipart/form-data">
    <h2>Stwórz swoje CV</h2>
    <?php
    $sql = "SELECT * FROM cvgenerator WHERE id_uzytkownicy=$id_uzytkownicy";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
    ?>
    <p>Ostatnio wygenerowane CV: <a href="cv-generator/<?php echo $filename1; ?>" download="<?php echo $filename1; ?>"><?php echo $filename1; ?></a></p>
    <embed src="cv-generator/<?php echo $filename1; ?>" width="100%" height="300px" type="application/pdf">
    <?php } ?>
    <hr class="line-style-1">
    <div class="form-group">
    <label for="image">Dodaj zdjęcie:</label>
    <input type="file" name="image" id="image">
    </div>            
    <div class="form-group">
        <label for="name">Imię:</label>
        <input type="text" id="name" name="name" placeholder="Wprowadź swoje imię" pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$"  required>
    </div>
    <div class="form-group">
        <label for="subname">Nazwisko:</label>
        <input type="text" id="subname" name="subname" placeholder="Wprowadź swoje nazwisko" pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" required>
    </div>
    <div class="form-group">
        <label for="phone">Numer telefonu:</label>
        <input type="text" id="phone" name="phone" placeholder="Wprowadź numer telefonu" pattern="^[1-9][0-9]{8}$" maxlength="9" required>
    </div>
    <div class="form-group">
        <label for="email">Adres email:</label>
        <input type="email" id="email" name="email" placeholder="Wprowadź swój adres email"required>
    </div> 
    <div class="form-group">
        <label for="data_urodzenia">Data urodzenia:</label>
        <input type="date" id="data_urodzenia" name="data_urodzenia" required>
    </div> 
    <div class="form-group">
        <label for="country">Kraj:</label>
        <input type="text" id="country" name="country" placeholder="Wprowadź swój Kraj zamieszkania" pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" required>
    </div>
    <div class="form-group">
        <label for="city">Miasto:</label>
        <input type="text" id="city" name="city" placeholder="Wprowadź miasto zamieszkania" pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" required>
    </div>
    <div class="form-group">
        <label for="region">Województwo:</label>
        <input type="text" id="region" name="region" placeholder="Wprowadź swoje województwo" pattern="^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ\s]+$" required>
    </div>
    <hr class="line-style-1">

    <div class="form-group">
    <label for="lang">Wykształcenie:</label>
    <div id="wyksztalcenie-container">
        <div class="wyksztalcenie">
        <label for="wyksztalcenie1">Nazwa szkoły/uczelni:</label>
        <input type="text" id="wyksztalcenie1" name="wyksztalcenia[0][name]" placeholder="Wprowadź nazwę szkoły/uczelni">
        <label for="wyksztalcenie-info1">Wprowadź nazwę kierunku/specjalności:</label>
        <input type="text" id="wyksztalcenie-info1" name="wyksztalcenia[0][kierunek]" placeholder="Wprowadź nazwę kierunku/specjalności">
        <label for="poziom-wyk1">Poziom:</label>
        <select id="poziom-wyk1" name="wyksztalcenia[0][level]" placeholder="Wprowadź nazwę kierunku/specjalności" onchange ="enableTytul()">
            <option disabled selected value="">Wybierz poziom wykształcenia</option>
            <option value="podstawowe">Podstawowe</option>
            <option value="średnie">Średnie</option>
            <option value="wyższe">Wyższe</option>
            <option value="podyplomowe">Podyplomowe</option>
            <option value="ustawiczne">Ustawiczne</option>
        </select>
        <label for="tytul-wyk1">Tytuł:</label>
        <select id="tytul-wyk1" name="wyksztalcenia[0][title]">
            <option disabled selected value="">Wybierz tytuł</option>
            <option value="brak">Brak</option>
            <option value="licencjat">Licencjat</option>
            <option value="inżynier">Inżynier</option>
            <option value="magister">Magister</option>
            <option value="doktor">Doktor</option>
            <option value="doktor habilitowany">Doktor habilitowany</option>
            <option value="profesor">Profesor</option>
        </select>
        </div>
    </div>
    </div>  
    <button type="button" id="dodaj-wyksztalcenie">Dodaj kolejne wykształcenie</button>

    <hr class="line-style-1">
    <div class="form-group">
    <label for="lang">Znajomości języków obcych:</label>
    <div id="jezyki-container">
        <div class="jezyk">
        <label for="jezyk1">Język:</label>
        <select id="jezyk1" name="jezyki[0][nazwa]" required>
            <option disabled selected value="">Wybierz język</option>
            <option value="angielski">Angielski</option>
            <option value="niemiecki">Niemiecki</option>
        </select>
        <label for="poziom1">Poziom:</label>
        <select id="poziom1" name="jezyki[0][poziom]" required>
            <option disabled selected value="">Wybierz poziom</option>
            <option value="podstawowy">Podstawowy</option>
            <option value="średniozaawansowany">Średniozaawansowany</option>
            <option value="zaawansowany">Zaawansowany</option>
        </select>
        </div>
    </div>
    </div>  
    <button type="button" id="dodaj-jezyk">Dodaj kolejny język</button>

    <hr class="line-style-1">
    <div class="form-group">
        <label for="doswiadczenie">Doświadczenie zawodowe:</label>
        <textarea id="doswiadczenie" name="doswiadczenie" placeholder="Wprowadź swoje doświadczenie zawodowe" required></textarea>
    </div>
    <div class="form-group">
        <label for="zainteresowania">Zainteresowania:</label>
        <textarea id="zainteresowania" name="zainteresowania" placeholder="Wprowadź swoje zainteresowania" required></textarea>
    </div>
    <div class="form-group">
        <label for="skill">Umiejętności:</label>
        <div class="form-item1">
            <input type="text" name="skill" id="skill" value=""/>
        </div>
    </div>



    <button type="submit">Generuj CV</button>
    </form>
</section>
</main>

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
</body>
</html>