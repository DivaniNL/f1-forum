
<?php
include "../handler.php";
if(!isset($_SESSION['user'])){ //if login in session is not set
    header("Location: ../login.php");
    
}
if($_SESSION['user']['logged_in_as'] != "admin"){
        header("Location: ../index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1-Forum</title>
</head>
<body>
    Dit Zie je als je ingelogd bent. Aan deze site wordt gewerkt.
</body>
</html>
