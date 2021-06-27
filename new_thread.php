<?php
include 'conn/conn.php';

if(isset($_GET['cat'])){
    $cat_id = $_GET['cat'];
}
if(isset($_GET['subcat'])){
    $subcat_id = $_GET['subcat'];
}

if (empty($_SESSION['user'])) {
    header("Location: subcategory.php?cat=".$cat_id."&subcat=".$subcat_id."");
}

include "handler.php";





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
<?php include 'header.php';?>
        <!-- Subcat Header -->
        <div class="subcat_header"><?php echo showSubcatHeader($conn, $subcat_id, $cat_id) ?></div>
        
        <div class="container_form">
            <!-- Register form -->
            <form action="" method="POST">
                <!-- Username Input -->
                <input id="username" type="hidden" name="username" value="<?php echo $_SESSION['user']['Username']?>">
                <!-- First Name Input -->
                <input id="firstname" type="hidden" name="firstname" value="<?php echo $_SESSION['user']['Firstname']?>">
                <!-- Last Name Input -->
                <input id="lastname" type="hidden" name="lastname" value="<?php echo $_SESSION['user']['Firstname']?>">
                <!-- Subcategoriy title Input -->
                <input id="subcat_title" type="hidden" name="subcat_title" value="<?php echo showSubcatHeader($conn, $subcat_id, $cat_id) ?>">
                <!-- Favourite Driver Input -->
                <input id="favourite_driver" type="hidden" name="favourite_driver" value="<?php echo $_SESSION['user']['favourite_driver']?>">
                
                
                <!-- HERE WILL COME AVATAR LATER -->
                
                <!-- Date Registered Input -->
                <input id="date_registered" type="hidden" name="date_registered" value="<?php echo $_SESSION['user']['date_registered']?>">
                <!-- Posts Made Input -->
                <input id="threads_count" type="hidden" name="threads_count" value="<?php echo $_SESSION['user']['threads_count']?>">
                <!-- Thread Name Input -->
                <label for= "thread_title">Name of the Tread</label>
                <input id="thread_title" type="text" name="thread_title"><br>
                <!-- Message Input -->
                <label for= "first_reply">Message</label>
                <textarea rows="4" cols="50" name="first_reply" id="first_reply">Enter text here...</textarea>
                <!-- Submit And Post -->
                <input id="newthread" type="submit" name="newthread">
            </form>
        </div>
        <!-- Errors occouring while registering will appear here -->
        <div class="container_error"><?php echo $error ?> </div>
        <?php include 'footer.php';?>
    </div>
</body>
</html>
<!-- Register form container -->
