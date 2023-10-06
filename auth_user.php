<?php 
session_start();
$_SESSION['auth_user'] = [
    'id_uzytkownicy' => $row['id_uzytkownicy'],
    'id_pracodawcy' => $row['id_pracodawcy'],
    'user' => $row['user'],
    'telefon' => $row['telefon'],
    'email' => $row['email'],
    'imie' => $row['imie'],
    'nazwisko' => $row['nazwisko'],
    'Adres' => $row['Adres'],
    'Opis' => $row['Opis'],
  ];

?>