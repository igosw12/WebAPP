<?php
@include 'config.php';
  $id_oferty = $_GET['id'];

      $query_update_status = "UPDATE oferty_pracy SET status = 'nieaktywny' WHERE id_oferty_pracy = $id_oferty";
      $query_run = mysqli_query($conn, $query_update_status);
      header("Location: display-moje.php");
      exit();

?>