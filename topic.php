
<?php
include "handler.php";
if(!isset($_SESSION['user'])){ //if login in session is not set
    header("Location: login.php");
}

if(isset($_GET['cat'])){
    $cat_id = $_GET['cat'];
}
if(isset($_GET['subcat'])){
    $subcat_id = $_GET['subcat'];
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
    Dit Zie je als je ingelogd bent en geen admin bent. Aan deze site wordt gewerkt.<br>
    Groen wordt het gedeelte waar alle categorieën getoont worden<br>
    Zwart is een categorie met de titel, en een kopje aantal onderwerpen(threads)<br>
    Grijs zijn alle subcategorieën met het aantal onderwerpen eronder
    <div class='threads_container_outer'>
    <?php echo showSubcatHeader($conn, $subcat_id, $cat_id) ?>
    <button class="addtopic_btn"> <a href="addtopic.php?cat=<?php echo $cat_id?>&subcat=<?php echo $subcat_id?>">Add a Topic</a></button>
    <?php 
    //Show subtopic title
    

    echo getThreads($conn, $cat_id, $subcat_id);
     ?>
     </div>
</body>
</html>
