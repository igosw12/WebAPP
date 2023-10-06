<?php
session_start();
@include 'authentication.php';
@include 'config.php';
@include 'obslugujace.php';
header("Cache-Control: no-cache, must-revalidate");
$offset = 0;
error_reporting(0);
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style5.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style3.css">
    <link rel="stylesheet" href="css/style4.css">
    <script src="skrypty.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
<form method="post" action="">
    <select name="selected_company" id="selected_company">
        <?php
        $query = "SELECT * FROM pracodawcy WHERE id_uzytkownicy = '$id_uzytkownicy' OR CONCAT(',', uprawnienia, ',') LIKE '%$id_uzytkownicy%'";
        $result1 = mysqli_query($conn, $query);
        $companies = array();
        while ($row1 = mysqli_fetch_assoc($result1)) {
            $companies[] = $row1;
        }
        $selectedCompanyId = isset($_POST['selected_company']) ? $_POST['selected_company'] : null;
        foreach ($companies as $company) {
            $companyId = $company["id_pracodawcy"];
            $selected = ($companyId == $selectedCompanyId) ? 'selected' : '';

            echo '<option value="' . $companyId . '" ' . $selected . '>' . $company["nazwa"] . '</option>';
        }
        echo '<option value=0';
        if ($selectedCompanyId == 0) {
            echo ' selected';
        }
        echo '>Oferty prywatne</option>';
        ?>
        ?>
    </select>
    <input type="submit" value="Pokaż oferty">
</form>

                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $selectedCompanyId = $_POST["selected_company"];
                    if ($selectedCompanyId == 0){
                    $sql = "SELECT id_oferty_pracy, tytul, opis, status, miasto, zdjecie_promo,DATE(data_waznosci) as data_waznosci, DATE(data_dodania) as data_dodania, wynagrodzenie 
                    FROM oferty_pracy WHERE id_uzytkownicy = '$id_uzytkownicy' AND id_pracodawcy IS NULL ORDER BY data_dodania DESC LIMIT 5";
                    }else{
                    $sql = "SELECT id_oferty_pracy, tytul, opis, status, miasto, zdjecie_promo,DATE(data_waznosci) as data_waznosci, DATE(data_dodania) as data_dodania, wynagrodzenie 
                    FROM oferty_pracy WHERE id_pracodawcy = '$selectedCompanyId' ORDER BY data_dodania DESC LIMIT 5";
                    }
                    $result = mysqli_query($conn, $sql);
                    



                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $excluded_ids[] = $row['id_oferty_pracy'];
                            $id_oferty = $row['id_oferty_pracy'];
                            $value_data_waznosci = $row['data_waznosci'];
                            $value_status = $row['status'];   
                            $expiry_timestamp = strtotime($value_data_waznosci);
                            $today_timestamp = time();
                            $days_remaining = round(($expiry_timestamp - $today_timestamp) / (60 * 60 * 24) + 1);
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
                            if ($days_remaining > 0  && $value_status == "aktywne") {
                                echo '<p class="job-info-data-akt"><span class="job-contract-type">'; echo" Oferta pracy aktywna"; '</span></p>';
                            }else{
                                echo '<p class="job-info-data-wyg"><span class="job-contract-type">'; echo "Oferta pracy zakończona"; '</span></p>';
                            }
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
                }
                    ?>
                </div>

            </div>                                
        </div>
    </section>
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