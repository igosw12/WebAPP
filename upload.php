<?php
  $targetDir = "uploads/";
  $targetFile = $targetDir . basename($_FILES["image"]["name"]);
  $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

  if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
      if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        echo $targetFile;
      }
    }
  }
?>