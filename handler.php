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
    ///PREPARED STATEMENTS V2
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
    $sql_auth_normaluser_login = "SELECT * FROM users INNER JOIN credentials ON credentials.user_id = users.id WHERE credentials.email = '".$post_login_username_email."' OR users.username = '".$post_login_username_email."'";
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
                
            'ID'=> $row_auth_normaluser_login["user_id"],
            'Username'=> $row_auth_normaluser_login["username"],
            'Firstname'=> $row_auth_normaluser_login["firstname"],
            'Lastname'=> $row_auth_normaluser_login["lastname"],
            'Admin'=> $row_auth_normaluser_login["admin"],
            'favourite_driver'=> $row_auth_normaluser_login["favourite_driver"],
            'date_registered'=> $row_auth_normaluser_login["date_registered"],
            'threads_count'=> $row_auth_normaluser_login["threads_count"],
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


if(isset($_POST['newthread'])){
    //getting the post values from the form at addtopic.php
    ///PREPARED STATEMENTS V2
    $post_username = mysqli_real_escape_string($conn, $_POST['username']);
    $post_firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $post_lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $post_favourite_driver = mysqli_real_escape_string($conn, $_POST['favourite_driver']);
    $post_date_registered = mysqli_real_escape_string($conn, $_POST['date_registered']);
    $post_threads_count = mysqli_real_escape_string($conn, $_POST['threads_count']);
    $post_thread_title = mysqli_real_escape_string($conn, $_POST['thread_title']);
    $post_first_reply = mysqli_real_escape_string($conn, $_POST['first_reply']);
    $author_id = $_SESSION['user']['ID'];
    //saves current date for time_created column in the threads table
    $time_created = date('Y-m-d H:i:s');
    $sql_check_existing_thread = "SELECT * FROM threads WHERE title = '".$post_thread_title."'";
    $result_check_existing_thread = $conn->query($sql_check_existing_thread);
    //check if there is already a thread with the same title
    if($result_check_existing_thread->num_rows < 1 ){
        //making the query to fill the threads table
        $sql_insert_thread_threads = "INSERT INTO `threads`(title, author_id, cat_id, subcat_id, `replies`, views, time_created) VALUES('$post_thread_title', $author_id, $cat_id, $subcat_id,0, 0, '$time_created')";
        if($result_insert_thread_threads = $conn->query($sql_insert_thread_threads)){
            //if the threads table is filled                
            
            
            //GET THREAD ID
            $sql_get_thread_id = "SELECT * FROM threads ORDER BY id DESC LIMIT 1";
            //do the query
            $result_get_thread_id = $conn->query($sql_get_thread_id);
            //get the thread_id and assign it to the variable thread_id
            $row_get_thread_id = $result_get_thread_id->fetch_assoc();
            $thread_id = $row_get_thread_id['id'];
            $sql_insert_thread_replies = "INSERT INTO `replies`(`user_id`, thread_id, date_time, post_body) VALUES($author_id, $thread_id, '$time_created', '$post_first_reply')";
            if($result_insert_thread_replies = $conn->query($sql_insert_thread_replies)){
                //if the replies table is filled
                //Up user topic count by one
                $sql_update_threads_count = "UPDATE users SET threads_count = threads_count + 1 WHERE id=".$author_id."";
                echo $sql_update_threads_count;
                if($result_update_threads_count = $conn->query($sql_update_threads_count)){
                    //if the thread count is updated in users table
                    $sql_update_subcat_threads_count = "UPDATE subcategories SET subcat_threads_count = subcat_threads_count + 1 WHERE id=".$subcat_id."";
                    if($result_update_subcat_threads_count = $conn->query($sql_update_subcat_threads_count)){
                    //if the thread count is updated in subcategories table
                    }else{
                        $error .=  "Er is iets misgegaan: Errorcode[144]";
                    }
                    
                }else{
                    
                    //errorcode nog toevoegen
                    echo $sql_update_threads_count;
                    $error .=  "Er is iets misgegaan: Errorcode[143]";
                }
  
            }else{
                
                //errorcode nog toevoegen
                $error .=  "Er is iets misgegaan: Errorcode[142]";
            }
           
        }else{
                
                //Fout bij invoeren threads tabel
                $error .=  "Er is iets misgegaan: Errorcode[141]";
                echo $sql_insert_thread_threads;
            }
    }else{
                
        //same title thread error
        $error .=  "Er is iets misgegaan: Errorcode[140]";
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
                <div class = 'subcat_title_container'><a class='subcat_link' href= 'topic.php?cat=".$row_get_categories_from_db['id']."&subcat=".$row_get_subcategories_from_db['id']."'>".$row_get_subcategories_from_db['subcat_title']."</a></div>
                <div class = 'subcat_thread_count_container'>".$row_get_subcategories_from_db['subcat_threads_count']."</div>
            </div>";
        } $cat_div.= '
        </div>';
    }
    $cat_div.= "</div><br>";
    return $cat_div;
}

function getThreads($conn, $cat_id, $subcat_id){

    //the outer div for all the threads
    $threads_div = "";
    $threads_div.= "<table class='threads_container_inner'>";
        $sql_get_all_threads_from_subcat = "SELECT * FROM threads INNER JOIN subcategories ON subcategories.id = threads.subcat_id INNER JOIN categories ON categories.id = threads.cat_id INNER JOIN users ON users.id = threads.author_id WHERE threads.subcat_id = ".$subcat_id."";
        echo $sql_get_all_threads_from_subcat;
        $result_get_all_threads_from_subcat = $conn->query($sql_get_all_threads_from_subcat);
        $threads_div.= "<tr class='thread_header'>
                <th class='cat_title_container'>Thread</th>
                <th class='subcat_thread_count_head_container'>Replies</th>
            </tr>";
        while($row_get_all_threads_from_subcat = $result_get_all_threads_from_subcat->fetch_assoc()){
            //get all variables 
            $thread_title = $row_get_all_threads_from_subcat['title'];
            $thread_replies = $row_get_all_threads_from_subcat['replies'];
            $time_created = $row_get_all_threads_from_subcat['time_created'];
            $thread_id = $row_get_all_threads_from_subcat['id'];
            $username = $row_get_all_threads_from_subcat['username'];




            $threads_div.= "
            <tr class='thread_container'>
                <td class='thread_left'>
                    <a class='thread_link' href='thread.php?thread=".$thread_id."'>".$thread_title."</a><br>
                    posted by: <span class='author_title'>".$username."</span> at ".$time_created."
                </td>
                
                <td class='thread_right'>
                    $thread_replies
                </td>
            </tr>";
            //sql query to get all the subcategories
        }


    $threads_div.= "</table>";
    return $threads_div;



}

function showSubcatHeader($conn, $subcat_id){
    $sql_get_categories_from_db = "SELECT * FROM subcategories INNER JOIN categories ON categories.id = subcategories.cat_id WHERE subcategories.id=".$subcat_id."";
    $result_get_categories_from_db = $conn->query($sql_get_categories_from_db);
    $row_get_categories_from_db = $result_get_categories_from_db->fetch_assoc();
    $cat_title = $row_get_categories_from_db['cat_title'];
    return $cat_title;

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