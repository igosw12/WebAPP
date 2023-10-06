<?php
session_start();
@include 'config.php';

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception; 
header("Cache-Control: no-cache, must-revalidate");
require 'vendor/autoload.php';

$pobraniehasla = mysqli_query($conn, "SELECT haslo, email FROM hasla WHERE id_hasla = 1");
$row = mysqli_fetch_assoc($pobraniehasla);
$value_haslo = $row['haslo'];
$value_email = $row['email'];

function sendemail_verify($user, $email, $verify_token, $value_haslo, $value_email)
{
    $mail = new PHPMailer(true);
    $mail -> isSMTP();
    $mail -> SMTPAuth = true;

    $mail -> Host = "smtp.gmail.com";
    $mail -> Username = $value_email;
    $mail -> Password = $value_haslo;

    $mail -> SMTPSecure = "tls";
    $mail -> Port = 587;

    $mail -> setFrom($value_email, $user);
    $mail -> addAddress($email);
    
    $mail -> isHTML(true);
    $mail -> Subject = "Email Verification from Job Finder";
    
    $email_template = "
        <h2>Zarejestrowales sie na stronie Job Finder</h2>
        <h5>Zweryfikuj swoje konto za pomoca ponizszego linku</h5>
        <br/><br/>
        <a href='http://localhost:3000/demo/verify-email.php?token=$verify_token'> Click Me </a>
    ";
    $mail->Body = $email_template;
    $mail->send();
}

if(isset($_POST['zarejestruj'])){   

    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['password-con']);
    $verify_token = md5(rand());
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $select = " SELECT * FROM uzytkownicy WHERE user = '$user' ";
    $select1 = " SELECT * FROM uzytkownicy WHERE email = '$email' ";

    $result = mysqli_query($conn, $select);
    $result1 = mysqli_query($conn, $select1);

    if(mysqli_num_rows($result1) > 0 ) {
        $error[] = 'Podany adres email jest już zajęty';
    }else {
        if(mysqli_num_rows($result) > 0) {
            $error[] = 'Podany login jest już zajęty';
        }else{
            $insert = "INSERT INTO uzytkownicy (user, password, email, verify_token) VALUES('$user', '$hashedPassword', '$email', '$verify_token')";
            $conn->autocommit(FALSE);
            $query_run = mysqli_query($conn, $insert);
            $uzytkownik_id = mysqli_insert_id($conn);
            $insert1 = "INSERT INTO profil_zawodowy (id_uzytkownicy) VALUES('$uzytkownik_id')";
            $query_run = mysqli_query($conn, $insert1);
            $conn->commit();

            if($query_run)
            {
                sendemail_verify("$user", "$email", "$verify_token", $value_haslo, $value_email);
                $_SESSION['status'] = "Rejestracja powiodła się! Zweryfikuj swój adres email.";
            }else{
                $_SESSION['status'] = "Rejestracja nie powiodła się! Spróbuj ponownie.";
                
            }
        }
};
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <title>Job Finder</title>
</head>
    <body_register> 
    <div class="register-container">
    <form action="" method="post">
    <div class="alert">
        <?php
        if(isset($_SESSION['status']))
        {
            echo "<h4>".$_SESSION['status']."</h4>";
            unset($_SESSION['status']);
        }
        ?>
    </div>
    <h2>Rejestracja</h2>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required <?php if(isset($_POST['email'])) echo 'value="' . $_POST['email'] . '"'; ?> >
    
    <label for="user">Login:</label>
    <input type="text" id="user" name="user" required <?php if(isset($_POST['user'])) echo 'value="' . $_POST['user'] . '"'; ?> >
    
    <label for="password">Hasło:</label>
    <input type="password" id="password" name="password" required pattern=".{8,}" onkeyup="sprawdz_haslo()">
    
    <label for="password-con">Powtórz hasło:</label>
    <input type="password" id="password-con" name="password-con" required onkeyup="sprawdz_haslo()">
    
    <div class="regulamin-akc">
      <input type="checkbox" required id="regulamin" name="regulamin">
      <label for="regulamin">Zapoznałem się z &nbsp<a href="regulamin.php"> regulaminem. </a> </label>
    </div>
    <button type="submit" id="zarejestruj" name="zarejestruj" disabled >Zarejestruj</button>
    <div class="links">
      <a href="login.php">Masz już konto?</a>
    </div>
    <span type="komunikat" id="komunikat"></span>
    <?php
    ?>
    <?php
    if(isset($error)){
        foreach($error as $error){
            echo '<span type="komunikat1" class="komunikat1" name="komunikat1">'.$error.'</span>';
        }
    }
    ?>
    </form>
    </div>
    </body_register>

    <script>
        function sprawdz_haslo() {
            var password = document.getElementById("password");
            var cpassword = document.getElementById("password-con");
            var komunikat = document.getElementById("komunikat");
        if (password.value !== cpassword.value) {
            zarejestruj.disabled = true;
            komunikat.innerHTML = "Hasła nie są takie same"  
        } else {
            zarejestruj.disabled = false;
            komunikat.innerHTML = "  ";
        }
        }
    </script>

    <script>
    function sprawdzusername() {
    var username = document.getElementById("username").value;

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "sprawdzusername.php?user=" + username, true);

    xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
    document.getElementById("komunikat").innerHTML = xhr.responseText;
    }
    };
    xhr.send();
    }
    </script>
</html>