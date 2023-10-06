<?php 
session_start();
@include 'config.php';
header("Cache-Control: no-cache, must-revalidate");

if(isset($_POST['zaloguj']))
{
  if(!empty(trim($_POST['user'])) && !empty(trim($_POST['password'])))
  {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $haselko = mysqli_query($conn, "SELECT password FROM uzytkownicy WHERE user = '$user' LIMIT 1");
    $row = mysqli_fetch_assoc($haselko);
    $value_haslo = $row['password'];

    $login_query = "SELECT * FROM uzytkownicy WHERE user = '$user' LIMIT 1";
    $login_query_run = mysqli_query($conn, $login_query);

    if(mysqli_num_rows($login_query_run) > 0){
      $row = mysqli_fetch_array($login_query_run);
      if($row['verify_status'] == "1")
      {
        if (password_verify($password, $value_haslo)) {
        $_SESSION['authenticated'] = TRUE;
        @include 'auth_user.php'; 
        header("Location: index.php");
        exit(0);
        }
      }else{
        $_SESSION['status'] = "Proszę zweryfikować swoje konto by móc się zalogować.";
        header("location: login.php");
        exit(0);
      }

    }else{
      $_SESSION['status'] = "Podany błędną nazwę użytkownika lub hasło";
      header("Location: login.php");
      exit(0);
    }

  }else{
    $_SESSION['status'] = "Nie oczekiwany problem";
    header("Location: login.php");
    exit(0);
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
    <div class="login-container">
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
    <h2>Logowanie</h2>
    <label for="user">Login:</label>
    <input type="text" id="user" name="user" required>
    <label for="password">Hasło:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit" id="zaloguj" name="zaloguj">Zaloguj</button>
    <div class="links">
      <a href="forgot.php">Zapomniałem hasła</a>
      <a href="register.php">Rejestracja</a>
    </div>
    <div class="link">
    <a href="resend-email-verification.php">Nie otrzymałem maila weryifukjącego</a> 
    </div>
  </form>
</div>
    </body_login>
</html>