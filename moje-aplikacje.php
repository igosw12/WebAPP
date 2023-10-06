<?php
session_start();
@include 'authentication.php';
@include 'config.php';
@include 'obslugujace.php';
$offset = 0;
header("Cache-Control: no-cache, must-revalidate");
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style5.css">
    <script src="skrypty.js"></script>
    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <!-- icons -->
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

<section class="job-list" id="praca">

<?php
$sql = "SELECT oferty_pracy.id_oferty_pracy, oferty_pracy.tytul, oferty_pracy.opis, oferty_pracy.miasto, oferty_pracy.zdjecie_promo, DATE(aplikacje.data_aplikacji) as data_dodania, aplikacje.status_aplikacji FROM aplikacje, oferty_pracy WHERE aplikacje.id_uzytkownicy = $id_uzytkownicy AND aplikacje.id_oferty_pracy = oferty_pracy.id_oferty_pracy";   
$sql1 = "SELECT COUNT(*) as count FROM aplikacje, oferty_pracy WHERE aplikacje.id_uzytkownicy = $id_uzytkownicy AND aplikacje.id_oferty_pracy = oferty_pracy.id_oferty_pracy";
$result = mysqli_query($conn, $sql);
$result1 = mysqli_query($conn, $sql1);
$row = $result1->fetch_assoc();
echo '<div class="info-aplikacje">' . "Ilość złożonych aplikacji: " . $row["count"] . '</div>';
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
        if($row['status_aplikacji'] == 'Dostarczona'){
        echo '<div class="job-status1">'. $row['status_aplikacji'] . '</div>';
        }else if($row['status_aplikacji'] == 'Przyjęta'){
        echo '<div class="job-status2">'. $row['status_aplikacji'] . '</div>';
        }else {
        echo '<div class="job-status3">'. $row['status_aplikacji'] . '</div>';
        }
        echo '<div class="job-posted">' . "Złożono: " . $row['data_dodania'] . '</div>';
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
}
?>
</div>

</div>                                
</div>

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