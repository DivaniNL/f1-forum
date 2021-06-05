<?php
include 'conn/conn.php';
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
            'Admin'=> "'".$row_auth_normaluser_login["admin"]."'",
            );
            if($_SESSION['user']["Admin"] == 1){
                $_SESSION['user']['logged_in_as'] = "admin";
            }elseif ($_SESSION['user']["Admin"] == 0) {
                $_SESSION['user']['logged_in_as'] = "client";
            }
            var_dump( $_SESSION['user']);
            //redirects you to the home page after logging in
            // header("Location: index.php");            
        } else {
        $error .=  'Invalid password.';
        }
    }
}
?>
<!-- The script below makes sure a form is not submitted when reloading a page -->
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>