<?php 
session_start();
@include 'config.php';
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception; 
header("Cache-Control: no-cache, must-revalidate");
require 'vendor/autoload.php';
error_reporting(0);

$pobraniehasla = mysqli_query($conn, "SELECT haslo FROM hasla WHERE id_hasla = 1");
$row = mysqli_fetch_assoc($pobraniehasla);
$value_haslo = $row['haslo'];
$value_email = $row['email'];
function resend_email_verify($user, $email, $verify_token, $value_haslo, $value_email){
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
    $mail -> Subject = "Resend - Email Verification from Job Finder";
    
    $email_template = "
        <h2>Zarejestrowałeś się na stronie Job Finder</h2>
        <h5>Zweryfikuj swoje konto za pomocą poniższego linku</h5>
        <br/><br/>
        <a href='http://localhost:3000/demo/verify-email.php?token=$verify_token'> Click Me </a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}
if(isset($_POST['wyslij']))
{
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $checkmail_query = "SELECT * FROM uzytkownicy WHERE email = '$email' LIMIT 1";
    $checkmail_query_run = mysqli_query($conn, $checkmail_query);

    if(mysqli_num_rows($checkmail_query_run) > 0)
    {
        $row = mysqli_fetch_array($checkmail_query_run);
        if($row['verify_status'] == "0"){

            $user = $row['user'];
            $email = $row['email'];
            $verify_token = $row['verify_token'];
            resend_email_verify($user, $email, $verify_token, $value_haslo, $value_email);
            $_SESSION['status'] = "Email został wysłany";

        }else{
            $_SESSION['status'] = "Email już jest zweryfikowany!";
        }
    }else {
        $_SESSION['status'] = "Email nie jest jeszcze zarejestrowany";
    }
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
    <body_login> 
    <div class="resend-container">
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
    <h2>Ponowne wysłanie kodu weryfikującego</h2>
    <label for="email">Podaj adres email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit" id="wyslij" name="wyslij">Wyślij</button>
  </form>
</div>
    </body_login>
</html>