<?php
@include 'authentication.php';
@include 'config.php';
##session_start();
error_reporting(0);
ini_set('display_errors', 0);
$id_uzytkownicy = $_SESSION['auth_user']['id_uzytkownicy'];
header("Cache-Control: no-cache, must-revalidate");


##$row = $result->fetch_assoc();
##$value_wiadomosc = $row['wiadomosc'];
##$value_user = $row['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style3.css">
    <link rel="stylesheet" href="css/style4.css">
    <link rel="stylesheet" href="css/style8.css">
    <script src="skrypty.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
    <br></br>   
    <br></br>
    <title>Przeglądanie wiadomości</title>
</head>
<body>
    <h2>Przeglądanie wiadomości</h2>
    
    <form method="POST">
    <select class="filter-select" id="view" name="view">
    <option value="otrzymane" <?php if (isset($_POST['view']) && $_POST['view'] === 'otrzymane') echo 'selected'; ?>>Odebrane wiadomości</option>
    <option value="wyslane" <?php if (isset($_POST['view']) && $_POST['view'] === 'wyslane') echo 'selected'; ?>>Wysłane wiadomości</option>
    <option value="wyslanepra" <?php if (isset($_POST['view']) && $_POST['view'] === 'wyslanepra') echo 'selected'; ?>>Wysłane wiadomości firmowe</option>
    </select>
    <input type="submit" value="Pokaż">
    </form> 

    <?php
    $view = $_POST["view"] ?? "otrzymane";

    if ($view === "otrzymane") {
        $query = "SELECT wiadomosci.id_wiadomosci, wiadomosci.przeczytane, wiadomosci.wiadomosc, wiadomosci.czas_wyslania, wiadomosci.id_aplikacji, oferty_pracy.id_oferty_pracy, uzytkownicy.user FROM wiadomosci, uzytkownicy, aplikacje, oferty_pracy WHERE wiadomosci.id_odbiorcy = $id_uzytkownicy AND uzytkownicy.id_uzytkownicy = wiadomosci.id_wysylajacego AND wiadomosci.id_aplikacji = aplikacje.id_aplikacji and oferty_pracy.id_oferty_pracy = aplikacje.id_oferty_pracy ORDER BY wiadomosci.czas_wyslania DESC";
    } else if ($view === "wyslane") {
        $query = "SELECT wiadomosci.wiadomosc, wiadomosci.czas_wyslania, wiadomosci.id_aplikacji, wiadomosci.id_odbiorcy, uzytkownicy.user, oferty_pracy.id_oferty_pracy FROM wiadomosci, uzytkownicy, aplikacje, oferty_pracy WHERE wiadomosci.id_wysylajacego = $id_uzytkownicy AND uzytkownicy.id_uzytkownicy = wiadomosci.id_odbiorcy AND wiadomosci.id_aplikacji = aplikacje.id_aplikacji and oferty_pracy.id_oferty_pracy = aplikacje.id_oferty_pracy ORDER BY wiadomosci.czas_wyslania DESC";
    } else if ($view === "wyslanepra")  {
        $query = "SELECT wiadomosci.wiadomosc, wiadomosci.czas_wyslania, wiadomosci.id_aplikacji, wiadomosci.id_odbiorcy, oferty_pracy.id_oferty_pracy,      
        (
            SELECT uzytkownicy.user FROM uzytkownicy WHERE uzytkownicy.id_uzytkownicy = wiadomosci.id_wysylajacego
        ) AS nazwa_odbiorca,
        (
            SELECT uzytkownicy.user FROM uzytkownicy WHERE uzytkownicy.id_uzytkownicy = wiadomosci.id_odbiorcy
        ) AS nazwa_wysylajacy
         FROM wiadomosci, uzytkownicy, pracodawcy, aplikacje, oferty_pracy WHERE wiadomosci.id_aplikacji = aplikacje.id_aplikacji AND aplikacje.id_oferty_pracy = oferty_pracy.id_oferty_pracy AND oferty_pracy.id_pracodawcy = pracodawcy.id_pracodawcy AND pracodawcy.id_uzytkownicy = uzytkownicy.id_uzytkownicy AND uzytkownicy.id_uzytkownicy = $id_uzytkownicy ORDER BY wiadomosci.czas_wyslania DESC";
    }
    $podstrona = "http://localhost:3000/demo/job-details.php?id=";
    $podstrona1 = "http://localhost:3000/demo/pracownik-detail.php?id=";

    $result = $conn->query($query);
                    ?>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()){
            echo '<div class="message">';
            if ($view === "otrzymane"){
            echo '<div class="sender">Nadawca: ' . $row['user'] . ' dnia: '. $row['czas_wyslania']. '</div>';
            } else if ($view === "wyslane") {
                echo '<div class="sender">Odbiorca: ' . $row['user'] . ' dnia: '. $row['czas_wyslania']. '</div>'; 
            } else if ($view === "wyslanepra") {
                echo '<div class="sender">Odbiorca: ' . $row['nazwa_wysylajacy'] . ' dnia: '. $row['czas_wyslania']. ' Nadawca:' . $row['nazwa_odbiorca'] . '</div>'; 
            }
            echo '<div class="message-content">' . nl2br($row['wiadomosc']) . '</div>';
            if ($view === "otrzymane"){
            if ($row['id_oferty_pracy'] != 0){
            echo '<div class="sender">W nawiązaniu do aplikowanej oferty pracy: <a href="' . $podstrona . '' . $row['id_oferty_pracy'] .'">Link</a></div>';
            } elseif ($row['id_oferty_pracy'] == 0) {
            echo '<div class="sender">Wiadomość uzyskana za pomocą propozycji dla pracodawców</div>';    
            }
            ?>
            <?php echo '<select class="status-select" data-id="' . $row['id_wiadomosci'] . '">'; ?>
            <option ><?php echo $row['przeczytane']; ?></option>
            <option value="Nieprzeczytane">Nieprzeczytane</option>
            <option value="Przeczytane">Przeczytane</option>  
            </select>
            <input type ="hidden" id="id_wiadomosci" value="<?php echo $row['id_wiadomosci']; ?>">
            <?php
            }
            if ($view === "wyslane" || $view === "wyslanepra"){
                if ($row['id_oferty_pracy'] != 0){
                echo '<div class="sender">W nawiązaniu do aplikowanej oferty pracy: <a href="' . $podstrona . '' . $row['id_oferty_pracy'] .'">Link</a></div>';
                } elseif ($row['id_oferty_pracy'] == 0) {
                echo '<div class="sender">Wiadomość wysłana za pomocą propozycji dla pracodawców do osoby: <a href="' . $podstrona1 . '' . $row['id_odbiorcy'] .'">Link</a></div>';
                }
            }
            echo '</div>';
    }}
    ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selects = document.getElementsByClassName('status-select');
        for (var i = 0; i < selects.length; i++) {
            selects[i].addEventListener('change', function() {
                var id = this.getAttribute('data-id');
                var status = this.value;
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update-przeczytane.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log('Status wiadomości został zaktualizowany');
                    }
                };
                xhr.send('id=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(status));
            });
        }
    });
</script>
</body>
</html>