
<?php
include "handler.php";


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
<div class="container_main">
    <div class='container_threads_outer'>
    <?php echo showSubcatHeader($conn, $subcat_id, $cat_id) ?>
    <button class="addtopic_btn"> <a href="addtopic.php?cat=<?php echo $cat_id?>&subcat=<?php echo $subcat_id?>">Add a Topic</a></button>
    <?php 
    //Show subtopic title
    

    echo getThreads($conn, $cat_id, $subcat_id);
     ?>
     </div>
</div>
</body>
</html>
