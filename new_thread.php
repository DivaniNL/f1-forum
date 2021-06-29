<?php
include 'conn/conn.php';

if (isset($_GET['cat'])) {
    $cat_id = $_GET['cat'];
}
if (isset($_GET['subcat'])) {
    $subcat_id = $_GET['subcat'];
}

if (empty($_SESSION['user'])) {
    header("Location: subcategory.php?cat=" . $cat_id . "&subcat=" . $subcat_id . "");
}

include "handler.php";





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1-Forum - Start een Topic</title>
    <link rel="icon" 
      type="image/png" 
      href="https://f1-forum.nl/assets/img/logo.png" />
</head>

<body>
    <div class="container_main">
        <?php include 'header.php'; ?>
        <!-- Subcat Header -->
        <div class="container_new_thread">
            <div class="container_new_thread_header">
                <h2>Start een nieuw topic</h2>
            </div>
            <div class="new_thread">
                <div class="container_form">
                    <!-- new_thread form -->
                    <form class='new_threadform' action="" method="POST">
                        <!-- Username Input -->
                        <input id="username" type="hidden" name="username" value="<?php echo $_SESSION['user']['Username'] ?>">
                        <!-- First Name Input -->
                        <input id="firstname" type="hidden" name="firstname" value="<?php echo $_SESSION['user']['Firstname'] ?>">
                        <!-- Last Name Input -->
                        <input id="lastname" type="hidden" name="lastname" value="<?php echo $_SESSION['user']['Firstname'] ?>">
                        <!-- Subcategoriy title Input -->
                        <input id="subcat_title" type="hidden" name="subcat_title" value="<?php echo showSubcatHeader($conn, $subcat_id, $cat_id) ?>">
                        <!-- Favourite Driver Input -->
                        <input id="favourite_driver" type="hidden" name="favourite_driver" value="<?php echo $_SESSION['user']['favourite_driver'] ?>">


                        <!-- HERE WILL COME AVATAR LATER -->

                        <!-- Date new_threaded Input -->
                        <input id="date_new_threaded" type="hidden" name="date_new_threaded" value="<?php echo $_SESSION['user']['date_new_threaded'] ?>">
                        <!-- Posts Made Input -->
                        <input id="threads_count" type="hidden" name="threads_count" value="<?php echo $_SESSION['user']['threads_count'] ?>">
                        <!-- Thread Name Input -->
                        <div class="contact-row column-right">
                            <label for="thread_title">Titel van het topic</label>
                            <input id="thread_title" class="new_thread_input_box" type="text" name="thread_title"><br>
                        </div>
                        <!-- Message Input -->
                        <div class="contact-row column-right">
                            <label for="first_reply">Eerste bericht</label>
                            <textarea class="new_thread_input_box" rows="4" cols="50" name="first_reply" id="first_reply" placeholder="Typ hier je bericht"></textarea>
                        </div>
                        <!-- Submit And Post -->
                        <div class='submit'>
                            <button id="newthread" type="submit" class="button_submit" name="newthread">Start Topic</button>
                        </div>
                    </form>
                </div>
                <!-- Errors occouring while new_threading will appear here -->
                <div class="container_error"><?php echo $error ?> </div>
                
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
</body>

</html>
<!-- new_thread form container -->