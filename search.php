<?php
session_start();
@include 'config.php';
@include 'obslugujace.php';
$offset = 0;
error_reporting(0);
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache"); 
header("Expires: 0"); 
header("Cache-Control: no-cache, must-revalidate");
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];

$Poziomstanowiska = $_POST['Poziom-stanowiska'];
$Wymiarpracy = $_POST['Wymiar-pracy'];
$Rodzajumowy = $_POST['Rodzaj-umowy'];
$Trybpracy = $_POST['Tryb-pracy'];
$klucz1 = $_POST['search-input'];
$klucz = explode(' ', $klucz1);
$klucz = array_map('trim', $klucz);
$klucz = array_filter($klucz);

$_SESSION['Poziom-stanowiska'] = $Poziomstanowiska;
$_SESSION['Wymiar-pracy'] = $Wymiarpracy;
$_SESSION['Rodzaj-umowy'] = $Rodzajumowy;
$_SESSION['Tryb-pracy'] = $Trybpracy;
$_SESSION['search-input'] = $klucz;

$conditions = [];
foreach ($klucz as $keyword) {
  $conditions[] = "(tytul LIKE '%$keyword%' OR opis LIKE '%$keyword%' OR miasto LIKE '%$keyword%' OR wojewodztwo LIKE '%$keyword%' OR wymagania LIKE '%$keyword%' 
  OR kwalfikacje LIKE '%$keyword%' OR tags LIKE '%$keyword%')";
}


if (isset($_POST['Poziom-stanowiska']) && is_array($_POST['Poziom-stanowiska'])) {
$opcjestonowisko = implode("', '", $_POST['Poziom-stanowiska']);
}
if (isset($_POST['Wymiar-pracy']) && is_array($_POST['Wymiar-pracy'])) {
$opcjewymiar = implode("', '", $_POST['Wymiar-pracy']);
}
if (isset($_POST['Rodzaj-umowy']) && is_array($_POST['Rodzaj-umowy'])) {
$opcjerodzaj = implode("', '", $_POST['Rodzaj-umowy']);
}
if (isset($_POST['Tryb-pracy']) && is_array($_POST['Tryb-pracy'])) {
$opcjetryb = implode("', '", $_POST['Tryb-pracy']);
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style7.css">
    
    <script src="skrypty.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

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

<form name="myForm" id="myForm" action="search.php" method="POST">
    
    <div name="search-wrapper" class="search-wrapper">
        <div name="search-box" class="search-box">
            <div name="search-card" class="search-card">
            
                <input class="search-input" name="search-input" id="search-input" type="text" value="<?php echo $klucz1; ?>">
                <button name="search-button" id="search-button" class="search-button">Wyszukaj</button>
            </div>
        </div>
    </div>
    
<div class="filter-box">
        <div class="filter-dropdown">
            <select class="filter-select" id="Poziom-stanowiska[]" name="Poziom-stanowiska[]" multiple>
                <option disabled>Poziom Stanowiska</option>
                <option value="Młodszy specjalista(Junior)" <?php if (isset($_POST['Poziom-stanowiska']) && in_array('Młodszy specjalista(Junior)', $_POST['Poziom-stanowiska'])) echo 'selected'; ?>>Młodszy specjalista(Junior)</option>
                <option value="Specjalista(Mid)" <?php if (isset($_POST['Poziom-stanowiska']) && in_array('Specjalista(Mid)', $_POST['Poziom-stanowiska'])) echo 'selected'; ?>>Specjalista(Mid)</option>
                <option value="Starszy specjalista(Senior)" <?php if (isset($_POST['Poziom-stanowiska']) && in_array('Starszy specjalista(Senior)', $_POST['Poziom-stanowiska'])) echo 'selected'; ?>>Starszy specjalista(Senior)</option>
                <option value="Dyrektor" <?php if (isset($_POST['Poziom-stanowiska']) && in_array('Dyrektor', $_POST['Poziom-stanowiska'])) echo 'selected'; ?>>Dyrektor</option>
            </select>
            <select class="filter-select" id="Wymiar-pracy[]" name="Wymiar-pracy[]" multiple>
                <option disabled>Wymiar pracy</option>
                <option value="Część etatu" <?php if (isset($_POST['Wymiar-pracy']) && in_array('Część etatu', $_POST['Wymiar-pracy'])) echo 'selected'; ?>>Część etatu</option>
                <option value="Dodatkowa/Tymczasowa" <?php if (isset($_POST['Wymiar-pracy']) && in_array('Dodatkowa/Tymczasowa', $_POST['Wymiar-pracy'])) echo 'selected'; ?>>Dodatkowa/Tymczasowa</option>
                <option value="Pełny etat" <?php if (isset($_POST['Wymiar-pracy']) && in_array('Pełny etat', $_POST['Wymiar-pracy'])) echo 'selected'; ?>>Pełny etat</option>
            </select>
            <select class="filter-select" id="Rodzaj-umowy[]" name="Rodzaj-umowy[]" multiple>
                <option disabled>Rodzaj umowy</option>
                <option value="Umowa o pracę" <?php if (isset($_POST['Rodzaj-umowy']) && in_array('Umowa o pracę', $_POST['Rodzaj-umowy'])) echo 'selected'; ?>>Umowa o pracę</option>
                <option value="Umowa o dzieło" <?php if (isset($_POST['Rodzaj-umowy']) && in_array('Umowa o dzieło', $_POST['Rodzaj-umowy'])) echo 'selected'; ?>>Umowa o dzieło</option>
                <option value="Umowa zlecenie" <?php if (isset($_POST['Rodzaj-umowy']) && in_array('Umowa zlecenie', $_POST['Rodzaj-umowy'])) echo 'selected'; ?>>Umowa zlecenie</option>
                <option value="Kontrakt B2B" <?php if (isset($_POST['Rodzaj-umowy']) && in_array('Kontrakt B2B', $_POST['Rodzaj-umowy'])) echo 'selected'; ?>>Kontrakt B2B</option>
                <option value="Umowa o staż/praktyki" <?php if (isset($_POST['Rodzaj-umowy']) && in_array('Umowa o staż/praktyki', $_POST['Rodzaj-umowy'])) echo 'selected'; ?>>Umowa o staż/praktyki</option>
            </select>
            <select class="filter-select" id="Tryb-pracy[]" name="Tryb-pracy[]" multiple>
                <option disabled>Tryb pracy</option>
                <option value="Praca Stacjonarna" <?php if (isset($_POST['Tryb-pracy']) && in_array('Praca Stacjonarna', $_POST['Tryb-pracy'])) echo 'selected'; ?>>Praca Stacjonarna</option>
                <option value="Praca Zdalna" <?php if (isset($_POST['Tryb-pracy']) && in_array('Praca Zdalna', $_POST['Tryb-pracy'])) echo 'selected'; ?>>Praca Zdalna</option>
                <option value="Praca Hybrydowa" <?php if (isset($_POST['Tryb-pracy']) && in_array('Praca Hybrydowa', $_POST['Tryb-pracy'])) echo 'selected'; ?>>Praca Hybrydowa</option>
            </select>
        </div>
        <div class="filter-chosen">
            <div class="chosen-card" hidden>
            <p id="chosen-text"></p>
                <i class="fas fa-times-circle" if aria-hidden="true"></i>
            </div>
        </div>
    </div>
</form>

<section class="job-list" id="praca">

                    <?php
                    $Poziomstanowiska = isset($_POST['Poziom-stanowiska']) ? $_POST['Poziom-stanowiska'] : [];
                    $Wymiarpracy = isset($_POST['Wymiar-pracy']) ? $_POST['Wymiar-pracy'] : [];
                    $Rodzajumowy = isset($_POST['Rodzaj-umowy']) ? $_POST['Rodzaj-umowy'] : [];
                    $Trybpracy = isset($_POST['Tryb-pracy']) ? $_POST['Tryb-pracy'] : [];
                    $klucz = $_POST['search-input'];

                    $warunek = "WHERE 1";

                      if (!empty($Rodzajumowy)) {
                        $rodzaje = "'" . implode("', '", $Rodzajumowy) . "'";
                        $warunek .= " AND (";
                        
                        $warunkiRodzaje = [];
                        foreach ($Rodzajumowy as $rodzaj) {
                          $warunkiRodzaje[] = "rodzaj_umowy LIKE '%$rodzaj%'";
                        }
                        
                        $warunek .= implode(" OR ", $warunkiRodzaje);
                        $warunek .= ")";
                      }
                      if (!empty($Wymiarpracy)) {
                        $wymiary = "'" . implode("', '", $Wymiarpracy) . "'";
                        $warunek .= " AND (";
                        
                        $warunkiWymiary = [];
                        foreach ($Wymiarpracy as $wymiar) {
                          $warunkiWymiary[] = "wymiar_pracy LIKE '%$wymiar%'";
                        }
                        
                        $warunek .= implode(" OR ", $warunkiWymiary);
                        $warunek .= ")";
                      }
                      if (!empty($Poziomstanowiska)) {
                        $poziomy = "'" . implode("', '", $Poziomstanowiska) . "'";
                        $warunek .= " AND (";
                        
                        $warunkiPoziomy = [];
                        foreach ($Poziomstanowiska as $poziom) {
                          $warunkiPoziomy[] = "poziom_stanowiska LIKE '%$poziom%'";
                        }
                        
                        $warunek .= implode(" OR ", $warunkiPoziomy);
                        $warunek .= ")";
                      }
                      if (!empty($Trybpracy)) {
                        $tryby = "'" . implode("', '", $Trybpracy) . "'";
                        $warunek .= " AND (";
                        
                        $warunkiTryby = [];
                        foreach ($Trybpracy as $tryb) {
                          $warunkiTryby[] = "tryb_pracy LIKE '%$tryb%'";
                        }
                        
                        $warunek .= implode(" OR ", $warunkiTryby);
                        $warunek .= ")";
                      }
                    

                    if (!empty($conditions)) {
                    $conditionsString = implode(' AND ', $conditions);
                    $obecnaData = new DateTime();
                    $obecnaDataStr = $obecnaData->format('Y-m-d');
                    $sql = "SELECT id_oferty_pracy, tytul, opis, miasto, zdjecie_promo, DATE(data_dodania) as data_dodania, wynagrodzenie FROM oferty_pracy $warunek 
                    AND $conditionsString AND data_waznosci > '$obecnaDataStr' AND status LIKE '%aktywne%' LIMIT 2";
                    }else{
                        $obecnaData = new DateTime();
                        $obecnaDataStr = $obecnaData->format('Y-m-d');
                        $sql = "SELECT id_oferty_pracy, tytul, opis, miasto, zdjecie_promo, DATE(data_dodania) as data_dodania, wynagrodzenie FROM oferty_pracy $warunek 
                        AND data_waznosci > '$obecnaDataStr' AND status LIKE '%aktywne%' LIMIT 2";   
                    }
                    ##echo $sql;
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $excluded_ids[] = $row['id_oferty_pracy'];
                            $id_oferty = $row['id_oferty_pracy'];
                            $zdjecie_promo = $row['zdjecie_promo'];
                            echo '<div class="job-card" data-url="job-details.php?id=' . $id_oferty . '">';
                            echo '<div clas="job-name">' ;
                            if($zdjecie_promo > 0){
                              echo '<img class="job-profile" src="data:image/jpeg;base64,' . base64_encode($row['zdjecie_promo']) . '">';
                              }elseif($zdjecie_promo == 0){
                                  echo '<img class="job-profile" src="img/job-seeker-icon.png" />';
                              }
                            echo '<div class="job-detail">';
                            echo '<h4>' . $row['tytul'] . '</h4>';
                            echo '<h3>' . $row['miasto'] . '</h3>';
                            echo '<p>' . $row['opis'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="job-more-detail">';
                            echo '<div class="job-zarobki">' . "Zarobki: " . $row['wynagrodzenie'] . "zł" . '</div>';
                            echo '<div class="job-posted">' . "Opublikowano: " . $row['data_dodania'] . '</div>';
                            echo '</div>';
                            echo '</div>';
                            $displayed_job_ids[] = $row['id_oferty_pracy'];
                            $_SESSION['displayed_job_ids'] = $displayed_job_ids;
                            $_SESSION['Poziom-stanowiska'] = $Poziomstanowiska;
                            $_SESSION['Wymiar-pracy'] = $Wymiarpracy;
                            $_SESSION['Rodzaj-umowy'] = $Rodzajumowy;
                            $_SESSION['Tryb-pracy'] = $Trybpracy;
                            $_SESSION['search-input'] = $klucz;
                        }
                    } else {
                        echo '<div class="job-card">';
                        echo '<div clas="job-name">' ;
                        echo "Brak ofert pracy do wyświetlenia.";
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>

            </div>                                
        </div>
        <?php
        echo '<button class="wiecej-ofert" button id="wiecej-ofert" data-offset="' . ($offset + 2) . '">Więcej ofert</button>';
        ?>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <script>
    let scriptExecuted = false;
    </script>
    <script>
        $(document).on('click', '#wiecej-ofert', function() {
    var offset = $(this).data('offset');
    var excluded_ids = $(this).data('excluded-ids');
    $.ajax({
        url: 'get_jobs2.php',
        data: { offset: offset, excluded_ids: excluded_ids, },
        success: function(response) {
        if (response.trim() === '') {
            $('#pokaz-wiecej').attr('disabled', 'disabled');
        }
        var jobs = $(response); 
        jobs.insertBefore('#wiecej-ofert');
        $('#wiecej-ofert').data('offset', offset + 2);
        $('#show-more').data('excluded-ids', excluded_ids.concat(response.excluded_ids));
            if (response.is_last_batch) {
                $('#show-more').hide();
            }
        }
    });
});
    </script>

    
<script>
document.addEventListener("DOMContentLoaded", function () {
  const elements = document.querySelectorAll(".job-card");
  
  elements.forEach(function (element) {
    element.addEventListener("click", function () {
      const url = this.getAttribute("data-url");

      window.location.href = url;
    });
  });
});
</script>

</body>
</html>