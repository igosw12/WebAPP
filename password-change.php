<?php 
session_start();
@include 'config.php';
header("Cache-Control: no-cache, must-revalidate");
if(isset($_POST['wyslij']))
{
    $new_password = mysqli_real_escape_string($conn, $_POST['password-new']);
    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $token = $_GET['token'];

    if(!empty($token))
    {
        if(!empty($new_password)){
            $check_token = "SELECT verify_token FROM uzytkownicy WHERE verify_token = '$token' LIMIT 1";
            $check_token_run = mysqli_query($conn, $check_token);

            if(mysqli_num_rows($check_token_run) > 0){
                $update_password = "UPDATE uzytkownicy SET password = '$hashedPassword' WHERE verify_token = '$token' LIMIT 1";
                $update_password_run = mysqli_query($conn, $update_password);

                if($update_password_run){
                    $_SESSION['status'] = "Nowe hasło zostało pomyślnie ustawione";
                    header("Location: login.php");
                    exit(0);
                }else{
                    $_SESSION['status'] = "Coś poszło nie tak";
                }
            }
            else{
                $_SESSION['status'] = "Invalid Token";
            }
        }
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&family=Roboto&display=swap" rel="stylesheet">
    <!-- icons -->
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
    <h2>Zmiana hasła</h2>
    <input style="display: none;" type="text" name="password_token" id="password_token" value="<?php if(isset($_GET['token'])){echo $_GET['token'];} ?>">
    <label for="password-new">Podaj nowe hasło:</label>
    <input type="text" id="password-new" name="password-new" required pattern=".{8,}">
    <button type="submit" id="wyslij" name="wyslij">Zmiana hasła</button>
  </form>
</div>
    </body_login>
</html>