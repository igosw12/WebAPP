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
    <select name="selected_company">
        <?php
        $query = "SELECT * FROM pracodawcy WHERE id_uzytkownicy = '$id_uzytkownicy' OR CONCAT(',', uprawnienia, ',') LIKE '%$id_uzytkownicy%'";
        $result1 = mysqli_query($conn, $query);
        $companies = array();

        while ($row1 = mysqli_fetch_assoc($result1)) {
            $companies[] = $row1;
        }

        usort($companies, function ($a, $b) use ($id_uzytkownicy) {
            if ($a["id_uzytkownicy"] == $id_uzytkownicy) {
                return -1;
            } elseif ($b["id_uzytkownicy"] == $id_uzytkownicy) {
                return 1;
            }
            return 0;
        });

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
    </select>
    <input type="submit" value="Pokaż oferty">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$selectedCompanyId = $_POST["selected_company"];
if ($selectedCompanyId == 0){
$sql = "SELECT oferty_pracy.id_oferty_pracy, oferty_pracy.tytul,
 aplikacje.status_aplikacji, aplikacje.id_aplikacji, uzytkownicy.email, uzytkownicy.imie, uzytkownicy.nazwisko, uzytkownicy.avatar, DATE(aplikacje.data_aplikacji) as data_dodania 
 FROM aplikacje JOIN oferty_pracy JOIN
 uzytkownicy WHERE oferty_pracy.id_uzytkownicy = '$id_uzytkownicy' AND oferty_pracy.id_pracodawcy IS NULL AND aplikacje.id_oferty_pracy = oferty_pracy.id_oferty_pracy 
 AND uzytkownicy.id_uzytkownicy = aplikacje.id_uzytkownicy";
}else{
$sql = "SELECT oferty_pracy.id_oferty_pracy, oferty_pracy.tytul,
 aplikacje.status_aplikacji, aplikacje.id_aplikacji, uzytkownicy.email, uzytkownicy.imie, uzytkownicy.nazwisko, uzytkownicy.avatar, DATE(aplikacje.data_aplikacji) as data_dodania 
 FROM aplikacje JOIN oferty_pracy JOIN
 uzytkownicy WHERE oferty_pracy.id_pracodawcy = '$selectedCompanyId' AND aplikacje.id_oferty_pracy = oferty_pracy.id_oferty_pracy 
 AND uzytkownicy.id_uzytkownicy = aplikacje.id_uzytkownicy";
}
$result = mysqli_query($conn, $sql);
##echo $sql;
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $excluded_ids[] = $row['id_oferty_pracy'];
        $id_aplikacji = $row['id_aplikacji'];
        $id_oferty = $row['id_oferty_pracy'];
        $avatar = $row['avatar'];
        echo '<div id="job-card" class="job-card" data-url="aplikacja-detail.php?id=' . $id_aplikacji . '">';
        echo '<div clas="job-name">' ;
        if (isset($avatar)) {
          echo '<img class="job-profile" src="data:avatar/jpeg;base64,'.base64_encode($avatar).'" alt="user-image" id="user-image" />';
      } else {
          echo '<img class="job-profile" src="img/User-avatar.svg.png" alt="user-image" id="user-image" />';
      }
        echo '<div class="job-detail">';
        echo '<h3>' . $row['email'] . '</h3>';
        echo '<p>' . $row['imie'] . '</p>';
        echo '<p>' . $row['nazwisko'] . '</p>';
        echo '</div>';
        echo '</div>';
        echo '<div class="job-title">';
        echo '<a href="job-details.php?id=' . $id_oferty . '">' . $row['tytul'] .'</a>';
        echo '</div>';
        echo '<div class="job-more-detail">';
        if($row['status_aplikacji'] == 'Dostarczona'){
          echo '<div class="job-status1">'. "Otrzymana" . '</div>';
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


        echo '</div>';
        echo '<div id="myModal-" class="modal">' ;
        echo   '<div class="modal-content">';
        echo'<span class="close">&times;</span>' ;
        echo'<form action="" method="post" enctype="multipart/form-data">' ;
        echo'<div class="form-item">' ;
        echo'<label for="list"></label>' ;
                        echo'<textarea id="list" name="list" id="" rows="7"></textarea>' ;
                        echo'</div>' ;
                        echo'<hr class="line-style-1">' ;
                        echo '<h3>' . $row['email'] . '</h3>';
                        echo'<a class="czy">Czy napewno chcesz aplikować na to stanowisko?</a>' ; 
                        echo'<button class="wyslij" name="wyslij" id="wyslij" type="submit">Wyślij</button>' ;
                        echo'</form>' ;
                        echo'</div>' ;
                        echo'</div>' ;
    }
} else {
    echo '<div class="job-card">';
    echo '<div clas="job-name">' ;
    echo "Brak złożonych dotychczas aplikacji.";
    echo '</div>';
    echo '</div>';
}
}
?>


</div>                                
</div>

<script>
  function pokazOkno2() {
    document.getElementById("okno2").style.display = "block";
  } 
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