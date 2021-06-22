<?php
if(isset($_GET['cat'])){
    $cat_id = $_GET['cat'];
}
if(isset($_GET['subcat'])){
    $subcat_id = $_GET['subcat'];
}
if(isset($_GET['thread'])){
    $thread_id = $_GET['thread'];
}
include "handler.php";



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1-Forum - Reply to <?php echo showThreadHeader($conn, $cat_id, $subcat_id, $thread_id) ?> </title>
    <!-- Hier komt nog get thread title in title -->
    
</head>
<body>
<div class="container_main">
    <!-- Outer div of the reply page -->
    <div class="reply_page_container_outer">
        <h2>Reply to <?php echo showThreadHeader($conn, $cat_id, $subcat_id, $thread_id) ?></h2>
        <!-- The container of the reply form -->
        <div class="container_form_reply">
            <form action="#" method="POST">
            <!-- Thread Id Input-->
            <input id="thread_id" type="hidden" name="thread_id" value= <?php echo $thread_id ?>>
            <!-- HERE WILL COME AVATAR LATER -->
                
            <!-- Message Input -->
            <label for= "post_body">Message</label>
            <textarea rows="4" cols="50" name="post_body" id="post_body">Enter text here...</textarea>
            <!-- Submit And Post -->
            <input id="newreply" type="submit" name="newreply">

            </form>


        </div>
    </div>
</div>
</body>
</html>
