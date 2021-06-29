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
    <link rel="icon" 
      type="image/png" 
      href="https://f1-forum.nl/assets/img/logo.png" />
</head>

<body>
    <div class="container_main">
        <?php include 'header.php'; ?>
        <div class="container_form">
            <!-- Adding Subcategory Form -->
            <form action="" method="POST">
                <!-- Category Input -->
                <label for="category">Category</label>
                <select name="category" id="category">
                    <?php
                    //this loop will place a option attribute inside of the select attribute for every category in the categories db table
                    $sql_get_categories_from_db = "SELECT * FROM categories";
                    $result_get_categories_from_db = $conn->query($sql_get_categories_from_db);
                    //loop through all the categories and show all current categories
                    while ($row_get_categories_from_db = $result_get_categories_from_db->fetch_assoc()) {
                        echo "<option value='" . $row_get_categories_from_db['cat_title'] . "'>" . $row_get_categories_from_db['cat_title'] . "</option>";
                    }
                    ?>
                </select>
                <!-- Subcategory Input -->
                <label for="subcategory">Subcategory</label>
                <input id="subcategory" type="text" name="subcategory">
                <!-- Add Subcategoryategory Submit -->
                <input id="addsubcat" type="submit" name="addsubcat">
            </form>
        </div>
        <?php include '../footer.php'; ?>
    </div>
</body>

</html>