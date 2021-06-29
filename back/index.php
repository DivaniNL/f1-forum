<?php
include '../conn/conn.php';
include "../handler.php";

//if login in session is  set
if(!isset($_SESSION['user']) || $_SESSION['user']['logged_in_as'] != "admin"){ 
    header("Location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1-Forum - Ontwikkelaarsomgeving</title>
    <link rel="icon" 
      type="image/png" 
      href="https://f1-forum.nl/assets/img/logo.png" />
</head>
<body>
<div class="container_main">
<?php include '../header.php';?>
    <?php include '../footer.php';?>
</div>
</body>
</html>
