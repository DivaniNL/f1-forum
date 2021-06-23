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
        $_SESSION['admin_page'] = 'addcat';

    }
}else{
    //if the user is not logged in at all, send it straight back to the login page
    header("Location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>F1-Forum.nl - Ontwikkelaarsomgeving</title>
    </head>
    <body>
        <!-- Main container -->
        <div class="container_main">
            <!-- Adding Category Form container -->
            <div class="container_form">
                <!-- Adding Category Form -->
                <form action="" method="POST">
                    <!-- Category Input -->
                    <label for="category">Category</label>
                    <input id="category" type="text" name="category">
                    <!-- Add Category Submit -->
                    <input id="addcat" type="submit" name="addcat">
                </form>
            </div>
            <!-- Errors occouring while trying to log in will appear here -->
            <div class="container_error"><?php echo $error ?> </div>
        </div>
    </body>
</html>