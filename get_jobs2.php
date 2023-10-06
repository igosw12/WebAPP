<?php
@include 'config.php';
@include 'obslugujace.php';
session_start();
error_reporting(0);

$offset = $_GET['offset'];
$displayed_job_ids = $_SESSION['displayed_job_ids']; 

$Poziomstanowiska = $_SESSION['Poziom-stanowiska'];
$Wymiarpracy = $_SESSION['Wymiar-pracy'];
$Rodzajumowy = $_SESSION['Rodzaj-umowy'];
$Trybpracy = $_SESSION['Tryb-pracy'];
$klucz1 = $_SESSION['search-input'];
$klucz = explode(' ', $klucz1);
$klucz = array_map('trim', $klucz);
$klucz = array_filter($klucz);

$conditions = [];
foreach ($klucz as $keyword) {
  $conditions[] = "(tytul LIKE '%$keyword%' OR opis LIKE '%$keyword%' OR miasto LIKE '%$keyword%' OR wojewodztwo LIKE '%$keyword%' OR wymagania LIKE '%$keyword%' OR kwalfikacje LIKE '%$keyword%')";
}

if (isset($_SESSION['Poziom-stanowiska']) && is_array($_SESSION['Poziom-stanowiska'])) {
$opcjestonowisko = implode("', '", $_SESSION['Poziom-stanowiska']);
}
if (isset($_SESSION['Wymiar-pracy']) && is_array($_SESSION['Wymiar-pracy'])) {
$opcjewymiar = implode("', '", $_SESSION['Wymiar-pracy']);
}
if (isset($_SESSION['Rodzaj-umowy']) && is_array($_SESSION['Rodzaj-umowy'])) {
$opcjerodzaj = implode("', '", $_SESSION['Rodzaj-umowy']);
}
if (isset($_SESSION['Tryb-pracy']) && is_array($_SESSION['Tryb-pracy'])) {
$opcjetryb = implode("', '", $_SESSION['Tryb-pracy']);
}


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
                        $obecnaData = new DateTime();
                        $obecnaDataStr = $obecnaData->format('Y-m-d');
                        $conditionsString = implode(' AND ', $conditions);
                        $sql = "SELECT id_oferty_pracy, tytul, opis, miasto, zdjecie_promo, DATE(data_dodania) as data_dodania, wynagrodzenie FROM oferty_pracy $warunek AND $conditionsString AND id_oferty_pracy NOT IN (" . implode(',', $displayed_job_ids) . ") AND data_waznosci > '$obecnaDataStr' AND status LIKE '%aktywne%' LIMIT 2";
                        }else{
                            $obecnaData = new DateTime();
                            $obecnaDataStr = $obecnaData->format('Y-m-d');
                            $sql = "SELECT id_oferty_pracy, tytul, opis, miasto, zdjecie_promo, DATE(data_dodania) as data_dodania, wynagrodzenie FROM oferty_pracy $warunek AND id_oferty_pracy NOT IN (" . implode(',', $displayed_job_ids) . ") AND data_waznosci > '$obecnaDataStr' AND status LIKE '%aktywne%' LIMIT 2";   
                        }
                        ##echo $sql;
                    $result = mysqli_query($conn, $sql);
                    

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id_oferty = $row['id_oferty_pracy'];
        $zdjecie_promo = $row['zdjecie_promo'];
        ##echo $sql;
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
    }
} else {
    echo '<div class="job-card">';
    echo '<div clas="job-name">' ;
    echo "Brak ofert pracy do wyświetlenia.";
    echo '</div>';
    echo '</div>';
    echo '<script>document.getElementById("wiecej-ofert").disabled = true;</script>';
    echo '<style>#wiecej-ofert { display: none; }</style>';
}
?>

<script>
    if (!scriptExecuted) {
    const elements = document.querySelectorAll('.job-card');

    elements.forEach(function(element) {
        element.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        window.location.href = url;
        scriptExecuted = true;
        });
    });
};
</script>


