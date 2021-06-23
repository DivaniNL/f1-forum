<?php
include 'conn/conn.php';
include "handler.php";


if(isset($_GET['cat'])){
    $cat_id = $_GET['cat'];
}
if(isset($_GET['subcat'])){
    $subcat_id = $_GET['subcat'];
}
if(isset($_GET['thread'])){
    $thread_id = $_GET['thread'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1-Forum - <?php echo showThreadHeader($conn, $cat_id, $subcat_id, $thread_id) ?> </title>
    <!-- Hier komt nog get thread title in title -->
    
</head>
<body>
<div class="container_main">
<?php include 'header.php';?>
    <div class='replies_container_outer'>
    <!-- Get full directory -->
    <?php echo showThreadFamily($conn, $cat_id, $subcat_id, $thread_id) ?>
    <?php if(isset($_SESSION['user'])){ 
        echo"<button class='reply_btn'> <a href='reply.php?thread=".$thread_id."&cat=".$cat_id."&subcat=".$subcat_id."'>Reply</a></button>";
    }
    ?>
    <?php 
    //Show replies
    

    echo getReplies($conn, $cat_id, $subcat_id, $thread_id);
     ?>
     </div>
</div>
</body>
</html>
