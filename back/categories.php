<?php
include "../handler.php";

//if login in session is  set
if(isset($_SESSION['user'])){ 
    //check if the user has admin rights
    if($_SESSION['user']['logged_in_as'] != "admin"){
        //if the user does not have admin rights, send it back to the public home page
        header("Location: ../index.php");
    }else{
        //stuff happening if you are visiting this page and have admin access
        $_SESSION['admin_page'] = 'cats';
    }
}else{
    //if the user is not logged in at all, send it straight back to the login page
    header("Location: ../login.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1-Forum - Ontwikkelaarsomgeving</title>
</head>
<body>
    Dit Zie je als je ingelogd bent en een admin bent. Aan deze site wordt gewerkt.<br>
    Groen wordt het gedeelte waar alle categorieën getoont worden<br>
    Zwart is een categorie met de titel, en een kopje aantal onderwerpen(threads)<br>
    Grijs zijn alle subcategorieën met het aantal onderwerpen eronder
    <div class='categories_container'>
    <?php 
    echo getCategoryTree($conn);
     ?>
     </div>
</body>
</html>
