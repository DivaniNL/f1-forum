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
</head>

<body>
    <div class="container_main">
        <?php include 'header.php'; ?>
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
        <?php include '../footer.php'; ?>
    </div>
</body>

</html>