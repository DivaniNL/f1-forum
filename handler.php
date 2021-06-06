<?php
include 'conn/conn.php';
?>
<style>
<?php include 'assets/css/style.min.css'; ?>
</style>
<?php
//the error variable stores all the errors and displays it in the div "error-container"
$error = "";
//registering a user
if(isset($_POST['register'])){
    //getting the post values from the form at register.php
    $post_username = mysqli_real_escape_string($conn, $_POST['username']);
    $post_firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $post_lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $post_email = mysqli_real_escape_string($conn, $_POST['email']);
    $post_password = mysqli_real_escape_string($conn, $_POST['password']);
    $post_favourite_driver = mysqli_real_escape_string($conn, $_POST['favourite_driver']);
    $post_email_confirm = mysqli_real_escape_string($conn, $_POST['confirm_email']);
    $post_password_confirm = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    //saves current date for date_registered column in the users table
    $date=date('Y-m-d');  
    //checks if the same email-adresses and passwords are entered twice
    if($post_email != $post_email_confirm){
        $error .= "the emails are not the same";
    }elseif($post_password != $post_password_confirm){
        $error .= "the passwords are not the same";
    }else{
        //hashing the password
        $password_hashed = password_hash($post_password, PASSWORD_DEFAULT);
        //query to check if there is already a user with either the inputted username or the inputted email-adress
        $sql_check_existing_user = "SELECT users.id, users.username, credentials.user_id, credentials.email, credentials.password FROM users INNER JOIN credentials ON credentials.user_id = users.id WHERE credentials.email = '".$post_email."' OR users.username = '".$post_username."'";
        $result_check_existing_user = $conn->query($sql_check_existing_user);
        //check if there is already a user with either the inputted username or the inputted email-adress. If the amount of rows is samaller then 1 there isn't
        if($result_check_existing_user->num_rows < 1 ){
            //making the query to fill the users table
            $sql_insert_user = "INSERT INTO `users`(username, firstname, lastname, date_registered, `admin`, favourite_driver) VALUES('$post_username', '$post_firstname', '$post_lastname', '$date',0, '$post_favourite_driver')";
            //if the record was saved
            if($result_insert_user = $conn->query($sql_insert_user)){
                // $sql_credentials
                //query to get most recent user_id from user table
                $get_user_id = "SELECT * FROM users ORDER BY id DESC LIMIT 1";
                //do the query
                $result_get_user_id = $conn->query($get_user_id);
                //get the user_id and assign it to the variable user_id
                $row_get_user_id = $result_get_user_id->fetch_assoc();
                $user_id = $row_get_user_id['id'];
                //making the query to fill in users credentials with the correct user_id
                $sql_insert_user_credentials = "INSERT INTO `credentials`(`user_id`, email, `password`) VALUES('$user_id', '$post_email', '$password_hashed')";
                //test if the record was saved
                if($result_insert_user_credentials = $conn->query($sql_insert_user_credentials)){
                }
                else{
                    $error .=  "Er is iets misgegaan: Errorcode[12]";
                    fwrite('logs.txt', $sql_insert_user_credentials);
                }
            }
            else{
                $error .=  "Er is iets misgegaan: Errorcode[11]";
                fwrite('logs.txt', $sql_insert_user);
            }
        }
        else{

            // QUERY || checks if there is already a user with the same EMAIL
            $sql_check_existing_email = "SELECT users.id, users.username, credentials.user_id, credentials.email, credentials.password FROM users INNER JOIN credentials ON credentials.user_id = users.id WHERE credentials.email = '".$post_email."' ";
            $result_check_existing_email = $conn->query($sql_check_existing_email);
            // QUERY || checks if there is already a user with the same USERNAME
            $sql_check_existing_username = "SELECT users.id, users.username, credentials.user_id, credentials.email, credentials.password FROM users INNER JOIN credentials ON credentials.user_id = users.id WHERE users.username = '".$post_username."' ";
            $result_check_existing_username = $conn->query($sql_check_existing_username);
            //checks if there is already a user with the same EMAIL
            if($result_check_existing_email->num_rows >= 1 ){
                $error .= "Er bestaat al een gebruiker met dit mailadres.";
                //checks if there is already a user with the same USERNAME
            }elseif($result_check_existing_username->num_rows >= 1 ){
                $error .= "Er bestaat al een gebruiker met deze gebruikersnaam.";
            }else{
                $error .= "Er is iets misgegaan: Errorcode[13]";
            }
        }
    }
}
//logging in    
if(isset($_POST['login'])){
    //retrieves post values from login form
    $post_login_username_email = mysqli_real_escape_string($conn, $_POST['login_username_email']);
    $post_login_password = mysqli_real_escape_string($conn, $_POST['login_password']);
    //getting password from db
    //get all users from the db with either the same username or the same email-adress as entered in the input field
    $sql_auth_normaluser_login = "SELECT users.id, users.username, users.firstname, users.lastname, users.admin, credentials.user_id, credentials.email, credentials.password FROM users INNER JOIN credentials ON credentials.user_id = users.id WHERE credentials.email = '".$post_login_username_email."' OR users.username = '".$post_login_username_email."'";
    $result_auth_normaluser_login = $conn->query($sql_auth_normaluser_login);
    //checks if there are users with the email-adress or username entered in the inputfield
    if($result_auth_normaluser_login->num_rows < 1 ){
        $error .=  'Er bestaat geen gebruiker met dit email-adres of gebruikersnaam!';
    }else{
        //gets the hashed password from the user which is trying to login to compare
        $row_auth_normaluser_login = $result_auth_normaluser_login->fetch_assoc();
        //compare the inserted password in the input field with the hashed password from the database
        if (password_verify($post_login_password, $row_auth_normaluser_login['password'])) {
            //log in successful
            //sets session variable

            $_SESSION['user'] = array(
                
            'ID'=> "'".$row_auth_normaluser_login["id"]."'",
            'Username'=> "'".$row_auth_normaluser_login["username"]."'",
            'Firstname'=> "'".$row_auth_normaluser_login["firstname"]."'",
            'Lastname'=> "'".$row_auth_normaluser_login["lastname"]."'",
            'Admin'=> "".$row_auth_normaluser_login["admin"]."",
            );
            if($_SESSION['user']["Admin"] == 1){
                $_SESSION['user']['logged_in_as'] = "admin";
            }elseif ($_SESSION['user']["Admin"] == 0) {
                $_SESSION['user']['logged_in_as'] = "client";
            }
            //redirects you to the home page after logging in
            header("Location: index.php");            
        } else {
        $error .=  'Invalid password.';
        }
    }
}



//adding category
if(isset($_POST['addcat'])){
    //getting the category name from the form input
    $post_category_name = mysqli_real_escape_string($conn, $_POST['category']);
    //query to check if there is already a category with this name
    $sql_check_existing_category = "SELECT * FROM categories WHERE cat_title = '".$post_category_name."'";
    $result_check_existing_category = $conn->query($sql_check_existing_category);
    //check if there is no category with this name
    if($result_check_existing_category->num_rows < 1 ){
        //making the query to fill the category table
        $sql_insert_category = "INSERT INTO `categories`(cat_title) VALUES('$post_category_name')";
        //if the record was saved
        if($result_insert_category = $conn->query($sql_insert_category)){
            header("Location: index.php");    
        }else{
            $error .=  "Er is iets misgegaan: Errorcode[120]";
            fwrite('logs.txt', $sql_insert_category);
        }
        
    }else{
        $error .=  "Er is iets misgegaan: Errorcode[121]";
        fwrite('logs.txt', $sql_insert_category);
    }


}
if(isset($_POST['addsubcat'])){

//DUMMY: SELECT * FROM CATEGORIES INNER JOIN SUBCATEGORIES WHERE TITLE = CATEGORY NAME
//GET CATEGORY ID 
//INSERT INTO SUBCATEGORIES WITH CATEGORY ID


    //getting the subcategory name
    $post_subcategory_name = mysqli_real_escape_string($conn, $_POST['subcategory']);
    //getting the category that the subcategory must be placed underneath
    $post_category_name = mysqli_real_escape_string($conn, $_POST['category']);
    //query to check if there is already a subcategory with this name
    $sql_check_existing_subcategory = "SELECT * FROM subcategories WHERE subcat_title = '".$post_subcategory_name."'";
    $result_check_existing_subcategory = $conn->query($sql_check_existing_subcategory);
    //check if there is no subcategory with this name
    if($result_check_existing_subcategory->num_rows < 1 ){
        //query to get all categories with the category name which was chosen in the select attribute of the form.
        $sql_get_cat_id = "SELECT * FROM categories WHERE cat_title = '".$post_category_name."'";
        $result_get_cat_id = $conn->query($sql_get_cat_id);
        $row_get_cat_id = $result_get_cat_id->fetch_assoc();
        //getting the category id, which can be used to fill in category_id when inserting a subcategory into the database
        $cat_id = $row_get_cat_id['id'];

        //query to input subcategory into the database
        $sql_insert_subcategory = "INSERT INTO subcategories(cat_id, subcat_title) VALUES($cat_id, '$post_subcategory_name')";
        if($result_insert_subcategory = $conn->query($sql_insert_subcategory)){
            

            header("Location: index.php");    
        }else{
            $error .=  "Er is iets misgegaan: Errorcode[122]";
            fwrite('logs.txt', $sql_insert_category);
        }


    }


}

//function to display all the categories and subcategories
function getCategoryTree($conn) {
    //sql query to get all the categories
    $cat_div = "";
    $sql_get_categories_from_db = "SELECT * FROM categories";
    $result_get_categories_from_db = $conn->query($sql_get_categories_from_db);
    //loop through all the categories and show all current categories
    $cat_div.= "<div class='cats_container'>";
    while($row_get_categories_from_db = $result_get_categories_from_db->fetch_assoc()){
        //Fill the categories variable and display this for every category found
        $cat_title = $row_get_categories_from_db['cat_title'];
        $cat_div.= "
        <div class='cat_container'>
            <div class='cat_header'>
                <div class='cat_title_container'>".$cat_title."</div>
                <div class='subcat_thread_count_head_container'>Threads</div>
            </div>";
        //sql query to get all the subcategories
        $sql_get_subcategories_from_db = "SELECT * FROM categories INNER JOIN subcategories ON subcategories.cat_id = categories.id WHERE categories.cat_title ='".$cat_title."'";
        $result_get_subcategories_from_db = $conn->query($sql_get_subcategories_from_db);
        //loop through all the subcategories and show all current subcategories onderneath the categories
        while($row_get_subcategories_from_db = $result_get_subcategories_from_db->fetch_assoc()){
            $cat_div.= "
            <div class = 'subcat_container'>
                <div class = 'subcat_title_container'>".$row_get_subcategories_from_db['subcat_title']."</div>
                <div class = 'subcat_thread_count_container'>0</div>
            </div>";
        } $cat_div.= '
        </div>';
    }
    $cat_div.= "</div><br>";
    return $cat_div;
}
// if($_SESSION['user']['logged_in_as'] == "admin"){
    //     //setting the json categories value to blank
    //     $_SESSION['categories'] = [];
    //     //query to get all categories from the database
    
    
// }
?>
<!-- The script below makes sure a form is not submitted when reloading a page -->
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>