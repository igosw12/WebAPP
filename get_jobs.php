<?php
@include 'config.php';
session_start();
error_reporting(0);

$offset = $_GET['offset'];
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];
$displayed_job_ids = $_SESSION['displayed_job_ids']; 

$miasto_profile = mysqli_query($conn, "SELECT miasto FROM uzytkownicy WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1");
$row = mysqli_fetch_assoc($miasto_profile);
$value_miasto = $row['miasto'];

$wojewodztwo_profile = mysqli_query($conn, "SELECT wojewodztwo FROM uzytkownicy WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1");
$row = mysqli_fetch_assoc($wojewodztwo_profile);
$value_wojewodztwo = $row['wojewodztwo'];

$obecnaData = new DateTime();
$obecnaDataStr = $obecnaData->format('Y-m-d');
$sql = "SELECT id_oferty_pracy, tytul, opis, miasto, wojewodztwo, zdjecie_promo, DATE(data_dodania) as data_dodania, wynagrodzenie FROM oferty_pracy WHERE id_oferty_pracy NOT IN (" . implode(',', $displayed_job_ids) . ") AND wojewodztwo LIKE '%$value_wojewodztwo%' AND data_waznosci > '$obecnaDataStr' AND status LIKE '%aktywne%' ORDER BY RAND() LIMIT 2";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
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


