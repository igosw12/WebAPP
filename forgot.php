<?php 
session_start();
@include 'config.php';
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception; 
header("Cache-Control: no-cache, must-revalidate");
require 'vendor/autoload.php';
error_reporting(0);

$pobraniehasla = mysqli_query($conn, "SELECT haslo, email FROM hasla WHERE id_hasla = 1");
$row = mysqli_fetch_assoc($pobraniehasla);
$value_haslo = $row['haslo'];
$value_email = $row['email'];
function send_password_reset($user, $email, $token, $value_haslo, $value_email){
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
    $mail -> Subject = "Job Finder - password reset";
    
    $email_template = "
        <h2>Poproszono o zmianę hasła dla konta przypisanego dla tego adreasu email</h2>
        <h5>Zmień swoje hasło za pomocą poniższego linku</h5>
        <br/><br/>
        <a href='http://localhost:3000/demo/password-change.php?token=$token&email=$email'> Click Me </a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}
if(isset($_POST['wyslij']))
{
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $token = md5(rand());

    $checkmail_email = "SELECT email FROM uzytkownicy WHERE email = '$email' LIMIT 1";
    $checkmail_email_run = mysqli_query($conn, $checkmail_email);

    if(mysqli_num_rows($checkmail_email_run) > 0)
    {
      $row = mysqli_fetch_array($checkmail_email_run);
      $email = $row['email']; 
      $user = $row['user'];
      

      $update_token = "UPDATE uzytkownicy SET verify_token='$token' WHERE email='$email' LIMIT 1";
      $update_token_run = mysqli_query($conn, $update_token);

      if($update_token_run){
        send_password_reset($user, $email, $token, $value_haslo, $value_email);
        $_SESSION['status'] = "Na podany email została wysłana możliwość zmiany hasła";
        header("Location: forgot.php");
        exit(0);

      }else{
        $_SESSION['status'] = "Nie oczekiwany problem, spróbuj ponownie";
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
    <h2>Resetowanie hasła</h2>
    <label for="email">Podaj adres email:</label>
    <input type="email" id="email" name="email" required>
    <button type="submit" id="wyslij" name="wyslij">Wyślij</button>
  </form>
</div>
    </body_login>
</html>