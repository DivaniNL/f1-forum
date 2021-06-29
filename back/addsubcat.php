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
        <div class="container_addsubcat">
            <div class="container_addsubcat_header">
                <h2>Voeg hier een categorie toe</h2>
            </div>
            <div class="addsubcat">
                <div class="container_form">
                    <!-- Adding Category Form -->
                    <form class='addsubcatform' action="" method="POST">
                        <!-- Category Input -->
                        <div class="contact-row column-right">
                            <label for="category">Category</label>
                            <select class="addsubcat_input_box" name="category" id="category">
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
                        </div>
                        <div class="contact-row column-right">
                            <label for="subcategory">Subcategory</label>
                            <input class="addsubcat_input_box" id="subcategory" type="text" name="subcategory">
                        </div>
                        <div class='submit'>
                            <button id="addsubcat" class="button_submit" type="submit" name="addsubcat">Voeg een categorie toe!</button>
                        </div>
                    </form>
                </div>
                <!-- Add Category Submit -->
                
            </div>
        </div>
        <!-- Errors occouring while trying to log in will appear here -->
        <div class="container_error"><?php echo $error ?> </div>
        <?php include '../footer.php'; ?>
    </div>
</body>

</html>