<?php
include 'conn/conn.php';



//registering a user
if(isset($_POST['register'])){
    
    //getting the post values from the form at register.php
    $post_username = $_POST['username'];
    $post_firstname = $_POST['firstname'];
    $post_lastname = $_POST['lastname'];
    $post_email = $_POST['email'];
    $post_password = $_POST['password'];
    $post_favourite_driver = $_POST['favourite_driver'];
    $date=date('Y-m-d');  

    $post_email_confirm = $_POST['confirm_email'];
    $post_password_confirm = $_POST['confirm_password'];
    if($post_email != $post_email_confirm){
        echo "the emails are not the same";
    }elseif($post_password != $post_password_confirm){
        echo "the passwords are not the same";
    }else{
        $password_hashed = password_hash($post_password, PASSWORD_DEFAULT);
    //making the query to fill the users table
    $sql_insert_user = "INSERT INTO `users`(username, firstname, lastname, date_registered, `admin`, favourite_driver) VALUES('$post_username', '$post_firstname', '$post_lastname', '$date',0, '$post_favourite_driver')";
    echo $sql_insert_user;
    //test if the record was saved
    if($result_insert_user = mysqli_query($conn, $sql_insert_user)){
    }
    else{
        echo "Er is iets misgegaan: Errorcode[11]";
        fwrite('logs.txt', $sql_insert_user);
    }
}
    // $sql_credentials
    //get user_id
    $get_user_id = "SELECT * FROM users ORDER BY id DESC LIMIT 1";
    $result_get_user_id = $conn->query($get_user_id);
    $row_get_user_id = $result_get_user_id->fetch_assoc();
    $user_id = $row_get_user_id['id'];
    $sql_insert_user_credentials = "INSERT INTO `credentials`(`user_id`, email, `password`) VALUES('$user_id', '$post_email', '$password_hashed')";
    if($result_insert_user_credentials = mysqli_query($conn, $sql_insert_user_credentials)){
    }
    else{
        echo "Er is iets misgegaan: Errorcode[12]";
        fwrite('logs.txt', $sql_insert_user_credentials);
    }
}


?>