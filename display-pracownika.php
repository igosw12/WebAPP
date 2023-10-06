<?php
session_start();
@include 'authentication.php';
@include 'config.php';
@include 'obslugujace.php';
$offset = 0;
error_reporting(0);
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
header("Cache-Control: no-cache, must-revalidate");
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];
$miasto_profile = mysqli_query($conn, "SELECT miasto FROM uzytkownicy WHERE id_uzytkownicy = '$id_uzytkownicy' LIMIT 1");
$row = mysqli_fetch_assoc($miasto_profile);
$value_miasto = $row['miasto'];
$klucz1 = $_POST['search-input'];
$klucz = explode(' ', $klucz1);
$klucz = array_map('trim', $klucz);
$klucz = array_filter($klucz);
$_SESSION['search-input'] = $klucz;


$conditions = [];
foreach ($klucz as $keyword) {
  $conditions[] = "(uzytkownicy.miasto LIKE '%$keyword%' OR profil_zawodowy.skills LIKE '%$keyword%' OR uzytkownicy.wojewodztwo LIKE '%$keyword%' OR uzytkownicy.kodpocztowy LIKE '%$keyword%')";
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

<form name="myForm" id="myForm" action="display-pracownika.php" method="POST">
    
    <div name="search-wrapper" class="search-wrapper">
        <div name="search-box" class="search-box">
            <div name="search-card" class="search-card">
            
                <input class="search-input" name="search-input" id="search-input" type="text" value="<?php echo $klucz1; ?>">
                <button name="search-button" id="search-button" class="search-button">Wyszukaj</button>
            </div>
        </div>
    </div>
</form>

<section class="job-list" id="praca">

                    <?php

                    if (!empty($conditions)) {
                    $conditionsString = implode(' AND ', $conditions);
                    $sql = "SELECT uzytkownicy.id_uzytkownicy, uzytkownicy.email, uzytkownicy.imie, uzytkownicy.nazwisko, profil_zawodowy.skills, uzytkownicy.miasto, uzytkownicy.avatar FROM uzytkownicy, profil_zawodowy WHERE $conditionsString AND uzytkownicy.id_uzytkownicy = profil_zawodowy.id_uzytkownicy AND uzytkownicy.id_uzytkownicy != '$id_uzytkownicy' AND uzytkownicy.zgodauzytkownika = 'Tak' 
                    ORDER BY RAND()";
                    }else{
                        $sql = "SELECT uzytkownicy.id_uzytkownicy, uzytkownicy.email, uzytkownicy.miasto, uzytkownicy.imie, uzytkownicy.nazwisko, profil_zawodowy.skills, uzytkownicy.avatar 
                        FROM uzytkownicy, profil_zawodowy 
                        WHERE uzytkownicy.miasto = '$value_miasto' AND uzytkownicy.id_uzytkownicy = profil_zawodowy.id_uzytkownicy AND uzytkownicy.id_uzytkownicy != '$id_uzytkownicy' AND uzytkownicy.zgodauzytkownika = 'Tak' 
                        ORDER BY RAND()";   
                    }
                    $result = mysqli_query($conn, $sql);
                    ##echo $sql;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $excluded_ids[] = $row['id_uzytkownicy'];
                            $avatar = $row['avatar'];
                            echo '<div id="job-card" class="job-card" data-url="pracownik-detail.php?id=' . $row['id_uzytkownicy'] . '">';
                            echo '<div clas="job-name">' ;
                            if (isset($avatar)) {
                              echo '<img class="job-profile" src="data:avatar/jpeg;base64,'.base64_encode($avatar).'" alt="user-image" id="user-image" />';
                          } else {
                              echo '<img class="job-profile" src="img/User-avatar.svg.png" alt="user-image" id="user-image" />';
                          }
                            echo '<div class="job-detail">';
                            ##echo '<h3>' . $row['email'] . '</h3>';
                            echo '<p>' . $row['imie'] . '</p>';
                            echo '<p>' . $row['nazwisko'] . '</p>';
                            echo '<p>' . $row['miasto'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="job-title">';
                            echo '</div>';
                            echo '<div class="job-more-detail">';
                            ##echo '<div class="job-posted">' . "Miasto: " . $row['miasto'] . '</div>';
                            echo '<div class="job-posted">' . "Umiejętności: " . $row['skills'] . '</div>';

                            echo '</div>';
                            echo '</div>';
                    
                    
                            echo '</div>';
                            echo '<div id="myModal-" class="modal">' ;
                            echo   '<div class="modal-content">';
                            echo'<form action="" method="post" enctype="multipart/form-data">' ;
                            echo'<div class="form-item">' ;
                                            echo'</div>' ;
                                            echo'</div>' ;
                        }
                    } else {
                        echo '<div class="job-card">';
                        echo '<div clas="job-name">' ;
                        echo "Brak dostępnych propozycji dla twojego miasta.";
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>

            </div>                                
        </div>
        <?php
        ##echo '<button class="wiecej-ofert" button id="wiecej-ofert" data-offset="' . ($offset + 2) . '">Więcej ofert</button>';
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