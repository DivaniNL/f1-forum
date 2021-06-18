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
    <div class='replies_container_outer'>
    <!-- Get full directory -->
    <?php echo showThreadFamily($conn, $cat_id, $subcat_id, $thread_id) ?>
    <button class="reply_btn"> <a href="reply.php?cat=<?php echo $cat_id?>&subcat=<?php echo $subcat_id?>&thread=<?php echo $thread_id?>">Reply</a></button>
    <?php 
    //Show replies
    

    echo getReplies($conn, $cat_id, $subcat_id, $thread_id);
     ?>
     </div>
</body>
</html>
