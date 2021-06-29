<?php
include 'conn/conn.php';

if (isset($_GET['cat'])) {
    $cat_id = $_GET['cat'];
}
if (isset($_GET['subcat'])) {
    $subcat_id = $_GET['subcat'];
}
if (isset($_GET['thread'])) {
    $thread_id = $_GET['thread'];
}



if (!isset($_SESSION['user'])) {
    header('Location: thread.php?thread=' . $thread_id . '&cat=' . $cat_id . '&subcat=' . $subcat_id);
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
    <link rel="icon" 
      type="image/png" 
      href="https://f1-forum.nl/assets/img/logo.png" />
</head>

<body>
    <div class="container_main">
        <?php include 'header.php'; ?>
        <!-- Outer div of the reply page -->
        <div class="container_reply">
            <div class="container_reply_header">
                <h2>Reply to <?php echo showThreadHeader($conn, $cat_id, $subcat_id, $thread_id) ?></h2>
            </div>
            <div class="reply">
                <!-- The container of the reply form -->
                <div class="container_form">
                    <form class="replyform" action="#" method="POST">
                        <!-- Thread Id Input-->
                        <input id="thread_id" type="hidden" name="thread_id" value=<?php echo $thread_id ?>>
                        <!-- HERE WILL COME AVATAR LATER -->

                        <!-- Message Input -->
                        <div class="contact-row column-right">
                            <label for="post_body">Bericht</label>
                            <textarea rows="4" cols="50" name="post_body" id="post_body" placeholder="Typ hier je reactie" class="reply_input_box"></textarea>
                            <!-- Submit And Post -->
    	                </div>
                        <div class="submit">
                        <button id="newreply" type="submit" class="button_submit"  name="newreply">Plaats je reactie
                        </div>
                    </form>


                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</body>

</html>