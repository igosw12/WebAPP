<?php

$email = $_POST['email'];
$conn = mysqli_connect('localhost', 'root', 'password', 'magisterka');


$sql = "SELECT * FROM uzytkownicy WHERE email='" . $email . "'";
$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
    echo "Taka wartość już istnieje!";
} else {
    echo "";
}

?>