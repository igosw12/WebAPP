<?php
@include 'authentication.php';
@include 'config.php';
header("Cache-Control: no-cache, must-revalidate");
session_start();
error_reporting(0);
##ini_set('display_errors', 0);
$id_oferty = $_GET['id'];
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];

$pobraniehasla = mysqli_query($conn, "SELECT haslo FROM hasla WHERE id_hasla = 2");
$row = mysqli_fetch_assoc($pobraniehasla);
$value_haslo = $row['haslo'];
$query = "SELECT * FROM oferty_pracy WHERE id_oferty_pracy = $id_oferty";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$value_miasto = $row['miasto'];
$value_ulica = $row['ulica'];
$value_kodpocztowy = $row['kod_pocztowy'];
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

$expiry_timestamp = strtotime($value_data_waznosci);
$today_timestamp = time();
$days_remaining = round(($expiry_timestamp - $today_timestamp) / (60 * 60 * 24) + 1);
?>
<?php
if(isset($_POST['wyslij'])){
    $list = mysqli_real_escape_string($conn, $_POST['list']);
    $sql = "SELECT * FROM pracodawcy WHERE id_uzytkownicy=$id_uzytkownicy";
    $result = mysqli_query($conn, $sql);
    $current_date_time = date('Y-m-d H:i:s');
    $target_dir = "uploads/";
    $folder = 'profilowe/'; 
    $target_file = $target_dir . basename($_FILES["resume"]["name"]);

    $filename = $_FILES["resume"]["name"];
    $filecontent = file_get_contents($target_file);
    $filecontent = mysqli_real_escape_string($conn, $filecontent);


    $insert1 = "INSERT INTO aplikacje (id_oferty_pracy, id_uzytkownicy, data_aplikacji, status_aplikacji, list_motywacyjny) VALUES('$id_oferty', '$id_uzytkownicy', '$current_date_time', 'Dostarczona', '$list')";
    $query_run = mysqli_query($conn, $insert1);

    if(!empty($_FILES["resume"]) && $_FILES["resume"]["size"] > 0){
        if(mysqli_num_rows($result) > 0){
        $update_cv = "UPDATE profil_zawodowy SET cv = '$filename', filecontent = '$filecontent' WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1"; 
        $update_cv_run = mysqli_query($conn, $update_cv);
        }else{
            $insert1 = "INSERT INTO pracodawcy (id_uzytkownicy) VALUES('$id_uzytkownicy')";
            $query_run = mysqli_query($conn, $insert1);
            $update_profile_2 = "UPDATE profil_zawodowy SET cv = '$filename', filecontent = '$filecontent' WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1";  
            $update_profile_run_2 = mysqli_query($conn, $update_profile_2);
        }
    }

    if($query_run){
        $_SESSION['status'] = "Aplikacja została wysłana";
        header('Location: moje-aplikacje.php');
        exit(0);
    }else{
        $_SESSION['status'] = "Coś poszło nie tak";
        header('Location: moje-aplikacje.php');
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
    <link rel="stylesheet" href="css/style10.css">
    <script src="skrypty.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />
    <script type='text/javascript' src='http://www.bing.com/api/maps/mapcontrol?callback=loadMapScenario' async defer></script>
    <script type='text/javascript'>
        function loadMapScenario() {
            var credentials = "<?php echo $value_haslo; ?>";
            var map = new Microsoft.Maps.Map(document.getElementById('myMap'), {
                credentials: credentials
            });
            Microsoft.Maps.loadModule('Microsoft.Maps.Search', function () {
                var searchManager = new Microsoft.Maps.Search.SearchManager(map);
                var miasto = '<?php echo $value_miasto; ?>';
                var ulica = '<?php echo $value_ulica; ?>';
                var kod_pocztowy = '<?php echo $value_kodpocztowy; ?>';
                var address = ulica + ', ' + miasto + ', ' + kod_pocztowy;
                searchManager.geocode({
                    where: address,
                    callback: function (r) {
                        map.setView({ center: r.results[0].location, zoom: 15 });
                        var pushpin = new Microsoft.Maps.Pushpin(r.results[0].location);
                        map.entities.push(pushpin);
                    }
                });
            });
        }
    </script>
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
            $sql = "SELECT cv, filecontent FROM profil_zawodowy, uzytkownicy WHERE uzytkownicy.id_uzytkownicy = $id_uzytkownicy AND profil_zawodowy.id_uzytkownicy = uzytkownicy.id_uzytkownicy";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $filename = $row['cv'];
            ?>
                <div class="form-item">
                        <label for="list">Dodaj list motywacyjny</label>
                        <textarea id="list" name="list" id="" rows="7" required></textarea>
                    <h4>Załącznik</h4>
                    <?php
                    if($filename > 0){
                    ?>
                    <p>Pobierz plik: <a class="pobierz" href="uploads/<?php echo $filename; ?>" download="<?php echo $filename; ?>" class="pobierz" ><?php echo $filename; ?> </a></p>
                    
                    <?php }else{
                        echo '<p>Brak wgranego pliku z CV, użyj przycisku poniżej by je wgrać</p>';
                    }
                    ?>
                    <p>Dodaj swoje CV</p>
                    <p>Obsługiwane pliki: PDF, DOCX, PPTX</p>
                    <div class="form-container resume-container">
                    <?php
                    if($filename > 0){
                    ?>
                    <input type="file" name="resume" id="resume" class="custom-file-input file-resume" accept=".pdf ,.docx,.pptx" />
                    <?php }else{
                        ?>
                    <input type="file" required name="resume" id="resume" class="custom-file-input file-resume" accept=".pdf ,.docx,.pptx" />
                        <?php
                    }
                    ?>
                    </div>
                    <hr class="line-style-1">
                    <a class="czy">Czy napewno chcesz aplikować na to stanowisko?</a> 
                    <button class="wyslij" name="wyslij" id="wyslij" type="submit">Wyślij</button>
                </div>
                </form>
                </div>

    </div>
</div>
		<section class="job-details">
			<h2></h2>
            <b class="job-title"><?php echo $value_tytul; ?></b>
            <hr class="line-style-1">
            <?php
            if (!empty($value_nazwa_pracy)){
            ?>
            <p class="job-info-nazwa"><i class="fa-solid fa-building"></i>  </i></i><span class="job-location"><?php echo $value_nazwa_pracy; ?></span></p>
            <?php
            }?>
            <?php
            if ($days_remaining > 0) {
                echo '<p class="job-info-data"><i class="fa-solid fa-clock"></i><span class="job-contract-type">'; echo"  Do końca zostało " . $days_remaining . " dni"; '</span></p>';
            }else{
                echo '<p class="job-info-data"><i class="fa-solid fa-clock"></i><span class="job-contract-type">'; echo "Oferta pracy została zakończona"; '</span></p>';
            }
            ?>
			<p class="job-placa"><i class="fa-solid fa-money-bill-1-wave">&nbsp</i><span class="job-contract-type"><?php echo $value_wynagrodzenie; ?>zł </span></p>
            <hr class="line-style-1">
			<p class="job-umowa"><i class="fa-solid fa-handshake"></i>&nbsp</i><span class="job-contract-type"><?php echo $value_rodzaj_umowy; ?></span></p>
            <p class="job-tryb"><i class="fa-solid fa-building"></i>&nbsp</i><span class="job-contract-type"><?php echo $value_tryb_pracy; ?></span></p>
            <p class="job-wymiar"><i class="fa-solid fa-calendar-days"></i>&nbsp</i><span class="job-contract-type"><?php echo $value_wymiar_pracy; ?></span></p>
            <p class="job-poziom"><i class="fa-solid fa-person"></i>&nbsp</i><span class="job-contract-type"><?php echo $value_poziom_stanowiska; ?></span></p>
            <hr class="line-style-1">
            <p class="job-info-dojazd"><i class="fa-solid fa-location-dot"></i>  </i></i><span class="job-location"><?php echo $value_miasto; ?>, ul. <?php echo $value_ulica; ?></span></p>
            <div id='myMap', class='myMap' style='width: 400px; height: 300px;'></div>
            <hr class="line-style-1">
			<ul>
            <c class="job-title">Wymagane kwalfikacje</c>
            <hr class="line-style-3" width="200">
            <ul>
            <d class="job-kwalfi"><?php echo $value_kwalfikacje?></d>
            <ul>
            <c class="job-title">Opis</c>
            <hr class="line-style-3" width="55">
			<ul>
            <d class="job-kwalfi"><?php echo $value_opis?></d>
            <ul>
            <c class="job-title">Wymagania</c>
            <hr class="line-style-3" width="105">
			<ul>
            <d class="job-opis"><?php echo $value_wymagania?></d>
            <ul>
            <ul>
            <hr class="line-style-1">
            <?php
            $query1 = "SELECT id_uzytkownicy FROM oferty_pracy WHERE id_oferty_pracy = $id_oferty";
            $result = mysqli_query($conn, $query1);
            $row = $result->fetch_assoc();
            $value = $row['id_uzytkownicy'];

            $query = "SELECT * FROM pracodawcy, oferty_pracy WHERE id_oferty_pracy = $id_oferty AND oferty_pracy.id_pracodawcy = pracodawcy.id_pracodawcy AND FIND_IN_SET('$id_uzytkownicy', REPLACE(pracodawcy.uprawnienia, ' ', ''))";
            $result5 = mysqli_query($conn, $query);


            $query2 = "SELECT aplikacje.id_aplikacji FROM aplikacje, oferty_pracy WHERE aplikacje.id_uzytkownicy = $id_uzytkownicy AND aplikacje.id_oferty_pracy = $id_oferty";
            $result2 = mysqli_query($conn, $query2); 

            $query3 = "SELECT * FROM oferty_pracy WHERE id_oferty_pracy = $id_oferty AND status = 'aktywne'";
            $result3 = mysqli_query($conn, $query3);
            
            if($value == $id_uzytkownicy OR mysqli_num_rows($result5) > 0) {
                if(mysqli_num_rows($result3) > 0 AND $days_remaining > 0){
			    echo '<a href="job-edit.php?id=' . $id_oferty . '" class="edit-now">Edytuj ofertę pracy</a>';
                echo '<c href="#" class="delete-now" data-id="' . $id_oferty . '">Usuń ofertę pracy</c>';
                }else{echo '<a disabled class="apply-now1" id="apply-now1" >Oferta zakończona lub usunięta</a>';}
            }else{
                if($days_remaining > 0 && mysqli_num_rows($result2) <= 0 && mysqli_num_rows($result3)){
                    echo '<a class="apply-now" id="apply-now">Aplikuj</a>';
                }
                elseif(mysqli_num_rows($result2) > 0){
                        echo '<a disabled class="apply-now1" id="apply-now1" >Aplikowałeś już na to stanowisko</a>';
                        }
                else{
                    echo '<a disabled class="apply-now1">Oferta zakończona lub usunięta</a>';
                }
            }
            ?>

		</section>
	</main>

    <script>
  function pokazOkno2() {
    document.getElementById("okno2").style.display = "block";
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


    <script>
    const elements = document.querySelectorAll('.edit-now');

    elements.forEach(function(element) {
        element.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        window.location.href = url;
        });
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  var deleteLinks = document.querySelectorAll(".delete-now");

  deleteLinks.forEach(function(link) {
    link.addEventListener("click", function(event) {
      event.preventDefault();

      var confirmDelete = confirm("Czy na pewno chcesz usunąć ofertę pracy?");

      if (confirmDelete) {
        var id = this.getAttribute("data-id");
        window.location.href = "job-delete.php?id=" + id;
      }
    });
  });
});
</script>
</body>
</html>