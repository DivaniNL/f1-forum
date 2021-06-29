<?php
include '../conn/conn.php';
include "../handler.php";

//if login in session is  set
if (!isset($_SESSION['user']) || $_SESSION['user']['logged_in_as'] != "admin") {
    header("Location: ../index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1-Forum - Ontwikkelaarsomgeving</title>
    <link rel="icon" type="image/png" href="https://f1-forum.nl/assets/img/logo.png" />
</head>

<body>
    <div class="container_main">
        <?php include 'header.php'; ?>
        <div class="container_addcat">
            <div class="container_addcat_header">
                <h2>Voeg hier een categorie toe</h2>
            </div>
            <div class="addcat">
            <div class="container_form">
                <!-- Adding Category Form -->
                <form class='addcatform' action="" method="POST">
                    <!-- Category Input -->
                    <div class="contact-row column-right">
                        <label for="category">Category</label>
                        <input class="addcat_input_box" id="category" type="text" name="category">
                    </div>
                    <!-- Add Category Submit -->
                    <div class='submit'>
                        <button id="addcat" class="button_submit" type="submit" name="addcat">Voeg een categorie toe!</button>
                    </div>
                </form>
            </div>
            </div>
            <!-- Errors occouring while trying to log in will appear here -->
            <div class="container_error"><?php echo $error ?> </div>
            <?php include '../footer.php'; ?>
        </div>
    </div>
</body>

</html>