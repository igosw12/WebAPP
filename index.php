<?php
session_start();
@include 'config.php';
@include 'obslugujace.php';
$offset = 0;
error_reporting(0);
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];
header("Cache-Control: no-cache, must-revalidate");

$miasto_profile = mysqli_query($conn, "SELECT miasto FROM uzytkownicy WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1");
$row = mysqli_fetch_assoc($miasto_profile);
$value_miasto = $row['miasto'];
$wojewodztwo_profile = mysqli_query($conn, "SELECT wojewodztwo FROM uzytkownicy WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1");
$row = mysqli_fetch_assoc($wojewodztwo_profile);
$value_wojewodztwo = $row['wojewodztwo'];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/styletemp.css">
    <script src="skrypty.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Job Finder</title>
</head>
<body>
<nav>
  <div class="navbar-brand">
    <a href="index.php">Job Portal</a>
  </div>
  <ul class="navbar-menu">
    <li><a href="cvgenerator.php">Generator CV</a></li>
    <li><a href="pracodawca.php">Dla przedsiębiorców</a></li>
    <li class="navbar-dropdown">
      <a href="#">Profil</a>
      <ul class="navbar-dropdown-menu">
        <li><a href="profil.php">Edytuj Profil</a></li>
        <li><a href="moje-aplikacje.php">Złożone aplikacje</a></li>
        <li><a href="otrzymane-aplikacje.php">Otrzymane aplikacje</a></li>
        <li><a href="skrzynka-odbiorcza.php">Skrzynka odbiorcza</a></li>
      </ul>
    </li>
    <li><a href="dodajoferte.php">Dodaj oferte pracy</a></li>
    <?php
    if(isset($_SESSION['authenticated'])){
    $query = "SELECT * FROM wiadomosci, uzytkownicy WHERE wiadomosci.id_odbiorcy = '$id_uzytkownicy'"; 
    $result = $conn->query($query);
    $flagaNieprzeczytane = false;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['przeczytane'] == 'Nieprzeczytane') {
                $flagaNieprzeczytane = true;
                break; 
            }
        }
    }
    if ($flagaNieprzeczytane) {
        echo '<a href="skrzynka-odbiorcza.php"><i class="fa-solid fa-comments"></i></a>';
    } else {
        echo '<a href="skrzynka-odbiorcza.php"><i class="fa-regular fa-comments"></i></a>';
    }
    }
    ?>
    <?php if(!isset($_SESSION['authenticated'])) : ?>
        <li><a href="login.php">Logowanie</a></li>
            <?php endif ?>

            <?php if(isset($_SESSION['authenticated'])) : ?>
                <li><a href="logout.php">Wyloguj</a></li>
            <?php endif ?>
  </ul>
</nav>
    <header>
        <h1 class="header-title">
            POZWÓL NAM <br> <span> ZNALEŹĆ TOBIE </span> <br> PRACĘ MARZEŃ
        </h1>
    </header>

    <form name="myForm" id="myForm" action="search.php" method="POST">
    
    <div name="search-wrapper" class="search-wrapper">
        <div name="search-box" class="search-box">
            <div name="search-card" class="search-card">
            
                <input class="search-input" name="search-input" id="search-input" type="text" placeholder="Wprowadź słowa kluczowe">
                <button name="search-button" id="search-button" class="search-button">Wyszukaj</button>
            </div>
        </div>
    </div>
    <div class="filter-box">
        <div class="filter-dropdown">
            <select class="filter-select" id="Poziom-stanowiska[]" name="Poziom-stanowiska[]" multiple>
                <option disabled>Poziom Stanowiska</option>
                <option value="Młodszy specjalista(Junior)">Młodszy specjalista(Junior)</option>
                <option value="Specjalista(Mid)">Specjalista(Mid)</option>
                <option value="Starszy specjalista(Senior)">Starszy specjalista(Senior)</option>
                <option value="Dyrektor">Dyrektor</option>
            </select>
            <select class="filter-select" id="Wymiar-pracy[]" name="Wymiar-pracy[]" multiple>
                <option disabled>Wymiar pracy</option>
                <option value="Część etatu">Część etatu</option>
                <option value="Dodatkowa/Tymczasowa">Dodatkowa/Tymczasowa</option>
                <option value="Pełny etat">Pełny etat</option>
            </select>
            <select class="filter-select" id="Rodzaj-umowy[]" name="Rodzaj-umowy[]" multiple>
                <option disabled>Rodzaj umowy</option>
                <option value="Umowa o pracę">Umowa o pracę</option>
                <option value="Umowa o dzieło">Umowa o dzieło</option>
                <option value="Umowa zlecenie">Umowa zlecenie</option>
                <option value="Kontrakt B2B">Kontrakt B2B</option>
                <option value="Umowa o staż/praktyki">Umowa o staż/praktyki</option>
            </select>
            <select class="filter-select" id="Tryb-pracy[]" name="Tryb-pracy[]" multiple>
                <option disabled>Tryb pracy</option>
                <option value="Praca Stacjonarna">Praca Stacjonarna</option>
                <option value="Praca Zdalna">Praca Zdalna</option>
                <option value="Praca Hybrydowa">Praca Hybrydowa</option>
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
            

    <script>
    </script>
    <section class="job-list" id="praca">

                    <?php
                    $obecnaData = new DateTime();
                    $obecnaDataStr = $obecnaData->format('Y-m-d');

                    $sql = "SELECT id_oferty_pracy, tytul, opis, miasto, zdjecie_promo, DATE(data_dodania) as data_dodania, wynagrodzenie FROM oferty_pracy 
                    WHERE data_waznosci > '$obecnaDataStr' AND wojewodztwo LIKE '%$value_wojewodztwo%' AND status LIKE '%aktywne%' ORDER BY RAND()  LIMIT 2";
                    $result = mysqli_query($conn, $sql);
                    ##echo $sql;

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
        url: 'get_jobs.php',
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