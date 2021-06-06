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
        $_SESSION['admin_page'] = 'addsubcat';

    }
}else{
    //if the user is not logged in at all, send it straight back to the login page
    header("Location: ../login.php");
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
        <div class="container">
            <!-- Adding Subcategory Form container -->
            <div class="form-container">
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
                    while($row_get_categories_from_db = $result_get_categories_from_db->fetch_assoc()){
                        echo "<option value='".$row_get_categories_from_db['cat_title']."'>".$row_get_categories_from_db['cat_title']."</option>";
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
            <!-- Errors occouring while trying to log in will appear here -->
            <div class="error-container"><?php echo $error ?> </div>
        </div>
    </body>
</html>