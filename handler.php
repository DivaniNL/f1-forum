<?php
// include 'conn/conn.php';
?>

<?php
//the error variable stores all the errors and displays it in the div "error-container"
$error = "";
//registering a user
if (isset($_POST['register'])) {
    //preparing the query to fill the users table
    $sql_insert_user = $conn->prepare("INSERT INTO `users`(username, firstname, lastname, avatar, date_registered, `admin`, favourite_driver) VALUES(?,?,?,?,?,?,?)");
    $sql_insert_user->bind_param("sssssis", $post_username, $post_firstname, $post_lastname, $avatar, $date, $var, $post_favourite_driver);
    //preparing the query to fill the credentials table
    $sql_insert_user_credentials = $conn->prepare("INSERT INTO `credentials`(`user_id`, email, `password`) VALUES(?, ?, ?)");
    $sql_insert_user_credentials->bind_param("iss", $user_id, $post_email, $password_hashed);
    //var is to fill up admin variable
    $var = 0;
    //getting the post values from the form at register.php
    $post_username = $_POST['username'];
    $post_firstname = $_POST['firstname'];
    $post_lastname = $_POST['lastname'];
    $post_email = $_POST['email'];
    $post_password = $_POST['password'];
    $post_favourite_driver = $_POST['favourite_driver'];
    $post_email_confirm = $_POST['confirm_email'];
    $post_password_confirm = $_POST['confirm_password'];
    //creating an avatar
    $file = $_FILES["avatar"]["name"];
    $check_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    echo $check_ext;
    //if png
    
    if ($check_ext == "png") {
        $im = imagecreatefrompng($_FILES['avatar']['tmp_name']);
        $size = min(imagesx($im), imagesy($im));
        //this will be optimizeed, the crop
        $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
        if ($im2 !== false) {
            //place the avatar in the folder
            imagepng($im2, "assets/avatars/_avatar_".$post_username.".png");
            imagedestroy($im2);
        }
        imagedestroy($im);
        
        //placing the avatar name under a variable
        $avatar = "_avatar_".$post_username.".png";
    } elseif ($check_ext == "jpg" ||$check_ext == "jpeg") {
        $im = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
        $size = min(imagesx($im), imagesy($im));
        //this will be optimizeed, the crop
        $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
        if ($im2 !== false) {
            //place the avatar in the folder
            imagejpeg($im2, "assets/avatars/_avatar_".$post_username.".jpg");
            imagedestroy($im2);
        }
        imagedestroy($im);
        echo "hoi jpg of jpeg";
        //placing the avatar name under a variable
        $avatar = "_avatar_".$post_username.".jpg";
    }
    
    //saves current date for date_registered column in the users table
    $date = date('Y-m-d');

    //checks if the same email-adresses and passwords are entered twice
    if ($post_email != $post_email_confirm) {
        $error .= "the emails are not the same";
    } elseif ($post_password != $post_password_confirm) {
        $error .= "the passwords are not the same";
    } else {
        //hashing the password
        $password_hashed = password_hash($post_password, PASSWORD_DEFAULT);
        //preparing query to check if there is already a user with either the inputted username or the inputted email-adress
        $sql_check_existing_user = $conn->prepare("SELECT users.id, users.username, credentials.user_id, credentials.email, credentials.password FROM users INNER JOIN credentials ON credentials.user_id = users.id WHERE credentials.email = ? OR users.username = ?");
        $sql_check_existing_user->bind_param("ss", $post_email, $post_username);
        //check if there is already a user with either the inputted username or the inputted email-adress. If the amount of rows is samaller then 1 there isn't
        $sql_check_existing_user->execute();
        $sql_check_existing_user->store_result();
        $sql_check_existing_user_num_rows = $sql_check_existing_user->num_rows;
        if ($sql_check_existing_user_num_rows < 1) {
            //if there is not a user with the same username or email
            if ($sql_insert_user->execute()) {
                //if the users table is filled

                //query to get most recent user_id from user table
                $get_user_id = "SELECT * FROM users ORDER BY id DESC LIMIT 1";
                //do the query
                $result_get_user_id = $conn->query($get_user_id);
                //get the user_id and assign it to the variable user_id
                $row_get_user_id = $result_get_user_id->fetch_assoc();
                $user_id = $row_get_user_id['id'];

                if ($sql_insert_user_credentials->execute()) {
                    //if the credentials table is filled, go to the login page.
                    header("Location: login.php");
                } else {
                    $error .= "Er is iets misgegaan: Errorcode[12]";
                }
            } else {
                echo "Er is iets misgegaan: Errorcode[11]";
            }
        } else {
            //if there is already a user with either the same username or email
            // QUERY || checks if there is already a user with the same EMAIL
            $sql_check_existing_email = $conn->prepare("SELECT users.id, users.username, credentials.user_id, credentials.email, credentials.password FROM users INNER JOIN credentials ON credentials.user_id = users.id WHERE credentials.email = ? ");
            $sql_check_existing_email->bind_param("s", $post_email);
            //check if there is already a user with either the inputted email-adress.
            $sql_check_existing_email->execute();
            $sql_check_existing_email->store_result();
            $sql_check_existing_email_num_rows = $sql_check_existing_email->num_rows;
            // QUERY || checks if there is already a user with the same USERNAME
            $sql_check_existing_username = $conn->prepare("SELECT users.id, users.username, credentials.user_id, credentials.email, credentials.password FROM users INNER JOIN credentials ON credentials.user_id = users.id WHERE users.username = ? ");
            $sql_check_existing_username->bind_param("s", $post_username);
            //check if there is already a user with either the inputted USERNAME.
            $sql_check_existing_username->execute();
            $sql_check_existing_username->store_result();
            $sql_check_existing_username_num_rows = $sql_check_existing_username->num_rows;
            //checks if there is already a user with the same EMAIL If the amount of rows is samaller then 1 there isn't
            if ($sql_check_existing_email_num_rows >= 1) {
                $error .= "Er bestaat al een gebruiker met dit mailadres.";
            //checks if there is already a user with the same USERNAME If the amount of rows is samaller then 1 there isn't
            } elseif ($sql_check_existing_username_num_rows >= 1) {
                $error .= "Er bestaat al een gebruiker met deze gebruikersnaam.";
            } else {
                $error .= "Er is iets misgegaan: Errorcode[13]";
            }
        }
    }
}
//logging in
if (isset($_POST['login'])) {
    //preparing query to check if there is a user with the filled in email or username.
    $sql_auth_normaluser_login = $conn->prepare("SELECT * FROM users INNER JOIN credentials ON credentials.user_id = users.id WHERE credentials.email = ? OR users.username = ?");
    $sql_auth_normaluser_login->bind_param("ss", $post_login_username_email, $post_login_username_email2);
    //retrieves post values from login form
    $post_login_username_email = $_POST['login_username_email'];
    $post_login_username_email2 = $post_login_username_email;
    $post_login_password = $_POST['login_password'];
    //check if there is a user with the filled in email or username.
    $sql_auth_normaluser_login->execute();
    $sql_auth_normaluser_login->store_result();
    $sql_auth_normaluser_login_num_rows = $sql_auth_normaluser_login->num_rows;
    //checks if there are users with the email-adress or username entered in the inputfield
    if ($sql_auth_normaluser_login_num_rows < 1) {
        $error .= 'Er bestaat geen gebruiker met dit email-adres of gebruikersnaam!';
    } else {
        $sql_auth_normaluser_login = $conn->prepare("SELECT * FROM users INNER JOIN credentials ON credentials.user_id = users.id WHERE credentials.email = ? OR users.username = ?");
        $sql_auth_normaluser_login->bind_param("ss", $post_login_username_email, $post_login_username_email2);
        $sql_auth_normaluser_login->execute();
        $result_auth_normaluser_login = $sql_auth_normaluser_login->get_result();
        $row_auth_normaluser_login = $result_auth_normaluser_login->fetch_assoc();
        if (password_verify($post_login_password, $row_auth_normaluser_login['password'])) {
            $_SESSION['user'] = array(
                'ID' => $row_auth_normaluser_login["user_id"],
                'Username' => $row_auth_normaluser_login["username"],
                'Firstname' => $row_auth_normaluser_login["firstname"],
                'Lastname' => $row_auth_normaluser_login["lastname"],
                'Admin' => $row_auth_normaluser_login["admin"],
                'favourite_driver' => $row_auth_normaluser_login["favourite_driver"],
                'date_registered' => $row_auth_normaluser_login["date_registered"],
                'threads_count' => $row_auth_normaluser_login["threads_count"],
            );
            if ($_SESSION['user']["Admin"] == 1) {
                $_SESSION['user']['logged_in_as'] = "admin";
            } elseif ($_SESSION['user']["Admin"] == 0) {
                $_SESSION['user']['logged_in_as'] = "client";
            }
            header("Location: index.php");
        } else {
            $error .= 'Invalid password.';
        }
    }
}
//adding category
if (isset($_POST['addcat'])) {
    //prepare query to insert a category
    $sql_insert_category = $conn->prepare("INSERT INTO `categories`(cat_title) VALUES(?)");
    $sql_insert_category->bind_param("s", $post_category_name);
    //query to check if there is already a category with this name
    $sql_check_existing_category = $conn->prepare("SELECT * FROM categories WHERE cat_title = ?");
    $sql_check_existing_category->bind_param("s", $post_category_name);
    //getting the category name from the form input
    $post_category_name = $_POST['category'];
    //check if there is already a category with this name
    $sql_check_existing_category->execute();
    $sql_check_existing_category->store_result();
    $sql_check_existing_category_num_rows = $sql_check_existing_category->num_rows;
    if ($sql_check_existing_category_num_rows < 1) {
        //if there is no category with the same name
        if ($sql_insert_category->execute()) {
            //if the record was saved
        } else {
            $error .= "Er is iets misgegaan: Errorcode[120]";
            fwrite('logs.txt', $sql_insert_category);
        }
    } else {
        //there is already a category with the same name
        $error .= "Er is iets misgegaan: Errorcode[121]";
        fwrite('logs.txt', $sql_insert_category);
    }
}
if (isset($_POST['addsubcat'])) {
    //INSERT INTO SUBCATEGORIES WITH CATEGORY ID
    //preparing query to insert a subcategory
    $sql_insert_subcategory = $conn->prepare("INSERT INTO subcategories(cat_id, subcat_title) VALUES(?, ?)");
    $sql_insert_subcategory->bind_param("is", $cat_id, $post_subcategory_name);
    //getting the subcategory name
    $post_subcategory_name = $_POST['subcategory'];
    //getting the category that the subcategory must be placed underneath
    $post_category_name = $_POST['category'];
    //query to check if there is already a subcategory with this name
    $sql_check_existing_subcategory = $conn->prepare("SELECT * FROM subcategories WHERE subcat_title = ?");
    $sql_check_existing_subcategory->bind_param("s", $post_subcategory_name);
    //check if there is already a subcategory with this name
    $sql_check_existing_subcategory->execute();
    $sql_check_existing_subcategory->store_result();
    $sql_check_existing_subcategory_num_rows = $sql_check_existing_subcategory->num_rows;
    if ($sql_check_existing_subcategory_num_rows < 1) {
        //if there is no subcategory with this name
        //preparing query to get all categories with the category name which was chosen in the select attribute of the form.
        $sql_get_cat_id = $conn->prepare("SELECT * FROM categories WHERE cat_title = ?");
        $sql_get_cat_id->bind_param("s", $post_category_name);
        //get all categories with the category name which was chosen in the select attribute of the form.
        $sql_get_cat_id->execute();
        $result_get_cat_id = $sql_get_cat_id->get_result();
        $row_get_cat_id = $result_get_cat_id->fetch_assoc();
        //getting the category id, which can be used to fill in category_id when inserting a subcategory into the database
        $cat_id = $row_get_cat_id['id'];
        if ($sql_insert_subcategory->execute()) {
            //if subcategory is inserted
            header("Location: index.php");
        } else {
            $error .= "Er is iets misgegaan: Errorcode[122]";
            fwrite('logs.txt', $sql_insert_category);
        }
    } //there is already a subcategory with this name
}
//adding a new thread
if (isset($_POST['newthread'])) {
    //preparing query to insert thread info in the threads table
    $sql_insert_thread_threads = $conn->prepare("INSERT INTO `threads`(title, author_id, cat_id, subcat_id, `replies`, views, time_created) VALUES(?,?,?,?,?,?,?)");
    $sql_insert_thread_threads->bind_param("siiiiis", $post_thread_title, $author_id, $cat_id, $subcat_id, $replies, $views, $time_created);
    //getting the post values from the form at addtopic.php
    $post_username = $_POST['username'];
    $post_firstname = $_POST['firstname'];
    $post_lastname = $_POST['lastname'];
    $post_favourite_driver = $_POST['favourite_driver'];
    $post_date_registered = $_POST['date_registered'];
    $post_threads_count = $_POST['threads_count'];
    $post_thread_title = $_POST['thread_title'];
    $post_first_reply = $_POST['first_reply'];
    $author_id = $_SESSION['user']['ID'];
    $post_subcat_title = $_POST['subcat_title'];
    $replies = 0;
    $views = 0;
    //saves current date for time_created column in the threads table
    $time_created = date('Y-m-d H:i:s');
    if ($sql_insert_thread_threads->execute()) {
        //if the threads table is filled
        //GET THREAD ID
        $thread_id = $conn->insert_id;
        //preparing query to insert thread info in the replies table
        $sql_insert_thread_replies = $conn->prepare("INSERT INTO `replies`(`user_id`, thread_id, date_time, post_body) VALUES(?,?,?,?)");
        $sql_insert_thread_replies->bind_param("iiss", $author_id, $thread_id, $time_created, $post_first_reply);
        // echo mysqli_error($conn);exit;
        if ($sql_insert_thread_replies->execute()) {
            //if the replies table is filled
            //preparing query to get threads count from the user which is logged in
            $sql_update_get_threads_count = $conn->prepare("SELECT * FROM users WHERE id=?");
            $sql_update_get_threads_count->bind_param("i", $author_id);
            //get threads count from the user which is logged in
            $sql_update_get_threads_count->execute();
            $result_update_get_threads_count = $sql_update_get_threads_count->get_result();
            $row_update_get_threads_count = $result_update_get_threads_count->fetch_assoc();
            //adding 1 to the user's threads count
            $threads_count_user = $row_update_get_threads_count['threads_count'] + 1;
            //preparing query to update thread count in the users table
            $sql_update_threads_count = $conn->prepare("UPDATE users SET threads_count = ? WHERE id=?");
            $sql_update_threads_count->bind_param("ii", $threads_count_user, $author_id);
            if ($sql_update_threads_count->execute()) {
                //if the thread count is updated in users table
                //preparing query to get threads count from the subcategory which the thread was placed in
                $sql_update_subcat_get_threads_count = $conn->prepare("SELECT * FROM subcategories WHERE id=?");
                $sql_update_subcat_get_threads_count->bind_param("i", $subcat_id);
                //get threads count from the subcategory which the thread was placed in
                $sql_update_subcat_get_threads_count->execute();
                $result_update_subcat_get_threads_count = $sql_update_subcat_get_threads_count->get_result();
                $row_update_subcat_get_threads_count = $result_update_subcat_get_threads_count->fetch_assoc();
                //adding 1 to the subcat's threads count
                $threads_count = $row_update_subcat_get_threads_count['subcat_threads_count'] + 1;
                //preparing query to update thread count in the subcategories table
                $sql_update_subcat_threads_count = $conn->prepare("UPDATE subcategories SET subcat_threads_count = ? WHERE id=?");
                $sql_update_subcat_threads_count->bind_param("ii", $threads_count, $subcat_id);
                //update thread count in the subcategories table
                if ($sql_update_subcat_threads_count->execute()) {
                    //if the thread count is updated in subcategories table
                    header("Location: topic.php?cat=".$cat_id."&subcat=".$subcat_id."");
                } else {
                    $error .= "Er is iets misgegaan: Errorcode[144]";
                }
            } else {
                //errorcode nog toevoegen
                echo $sql_update_threads_count;
                $error .= "Er is iets misgegaan: Errorcode[143]";
            }
        } else {
            //errorcode nog toevoegen
            $error .= "Er is iets misgegaan: Errorcode[142]";
            // echo mysqli_error($conn);exit;
        }
    } else {
        //Fout bij invoeren threads tabel
        $error .= "Er is iets misgegaan: Errorcode[141]";
        echo $sql_insert_thread_threads;
    }
}
//function when a new reply is submitted
if (isset($_POST['newreply'])) {
    //preparing query to insert thread info in the threads table
    //getting the post values from the form at addtopic.php
    $thread_id = $_POST['thread_id'];
    $post_body = $_POST['post_body'];
    $author_id = $_SESSION['user']['ID'];
    $replies = 0;
    $views = 0;
    //saves current date for time_created column in the threads table
    $time_created = date('Y-m-d H:i:s');
    //preparing query to insert thread info in the replies table
    $sql_insert_thread_replies = $conn->prepare("INSERT INTO `replies`(`user_id`, thread_id, date_time, post_body) VALUES(?,?,?,?)");
    $sql_insert_thread_replies->bind_param("iiss", $author_id, $thread_id, $time_created, $post_body);
    if ($sql_insert_thread_replies->execute()) {
        //if the replies table is filled

        //preparing query to get replies count from the thread which the reply was placed in
        $sql_update_thread_get_threads_count = $conn->prepare("SELECT * FROM threads WHERE id=?");
        $sql_update_thread_get_threads_count->bind_param("i", $thread_id);
        //get replies count from the thread which the reply was placed in
        $sql_update_thread_get_threads_count->execute();
        $result_update_thread_get_threads_count = $sql_update_thread_get_threads_count->get_result();
        $row_update_thread_get_threads_count = $result_update_thread_get_threads_count->fetch_assoc();
        //adding 1 to the thread's reply count
        $replies_count = $row_update_thread_get_threads_count['replies'] + 1;
        //preparing query to update replies count in the threads table
        $sql_update_thread_replies_count = $conn->prepare("UPDATE threads SET replies = ? WHERE id=?");
        $sql_update_thread_replies_count->bind_param("ii", $replies_count, $thread_id);
        //update replies count in the thread table
        if ($sql_update_thread_replies_count->execute()) {
            //if the replies count is updated in threads table
            header("Location: thread.php?thread=".$thread_id."&cat=".$cat_id."&subcat=".$subcat_id."");
        } else {
            $error .= "Er is iets misgegaan: count niet geupdated";
        }
    } else {
        //errorcode nog toevoegen
        $error .= "Er is iets misgegaan: reply niet toegevoegd";
    }
}
//function to display all the categories and subcategories
function getCategoryTree($conn)
{
    //emptying the container
    $cat_div = "";
    //sql query to get all the categories
    $sql_get_categories_from_db = "SELECT * FROM categories";
    $result_get_categories_from_db = $conn->query($sql_get_categories_from_db);
    //adding a div to display all teh categories in
    $cat_div .= "<div class='container_categories'>";
    //loop through all the categories and show all current categories
    while ($row_get_categories_from_db = $result_get_categories_from_db->fetch_assoc()) {
        //Fill the categories variable and display this for every category found
        $cat_title = $row_get_categories_from_db['cat_title'];
        //adding a div for every categopry
        $cat_div .= "
        <div class='container_category'>
            <div class='category_header'>
                <div class='cat_title_container'>" . $cat_title . "</div>
                <div class='subcat_thread_count_head_container'>Onderwerpen</div>
            </div>
            <div class = 'container_subcats'>";
        //preparing query to get all the subcategories
        $sql_get_subcategories_from_db = $conn->prepare("SELECT * FROM categories INNER JOIN subcategories ON subcategories.cat_id = categories.id WHERE categories.cat_title =?");
        $sql_get_subcategories_from_db->bind_param("s", $cat_title);
        //get all the subcategories
        $sql_get_subcategories_from_db->execute();
        $result_get_subcategories_from_db = $sql_get_subcategories_from_db->get_result();
        //loop through all the subcategories and show all current subcategories onderneath the categories
        while ($row_get_subcategories_from_db = $result_get_subcategories_from_db->fetch_assoc()) {
            //place a div inside the category for each subcategory
            $cat_div .= "
            <div class = 'container_subcat'>
                <div class = 'subcat_title_container'><div class = 'subcat_read_img_container'><img src='http://localhost/f1-forum/assets/img/forum_read.png'></div><a class='subcat_link' href= 'topic.php?cat=" . $row_get_categories_from_db['id'] . "&subcat=" . $row_get_subcategories_from_db['id'] . "'>" . $row_get_subcategories_from_db['subcat_title'] . "</a></div>
                <div class = 'subcat_thread_count_container'>" . $row_get_subcategories_from_db['subcat_threads_count'] . "</div>
            </div>";
        }
        $cat_div .= '
        </div></div>';
    }
    $cat_div .= "</div><br>";
    //return the div. Echo this function to see the div
    return $cat_div;
}
function getThreads($conn, $cat_id, $subcat_id)
{
    //emptying the threads container div
    $threads_div = "";
    //the outer div for all the threads
    $threads_div .= "<div class='container_threads_inner'>";
    //preparing query to get all threads from current subcat
    $sql_get_all_threads_from_subcat = $conn->prepare("SELECT *, threads.id as threadId, users.id as userId, subcategories.id as subcatId, categories.id as catId FROM threads INNER JOIN subcategories ON subcategories.id = threads.subcat_id INNER JOIN categories ON categories.id = threads.cat_id INNER JOIN users ON users.id = threads.author_id WHERE threads.subcat_id = ?");
    $sql_get_all_threads_from_subcat->bind_param("i", $subcat_id);
    //get all threads from current subcat
    $sql_get_all_threads_from_subcat->execute();
    $result_get_all_threads_from_subcat = $sql_get_all_threads_from_subcat->get_result();
    //thread header shows a line where the details of the divs will be
    $threads_div .= "<div class='threads_header'>
                <div class='threads_info_container'>Onderwerpen</div>
                <div class='threads_replies_container'>Reacties</div>
                <div class='threads_latest_reply_info_container'>Laatste bericht</div>
            </div>
            <div class='container_threads'>";
    while ($row_get_all_threads_from_subcat = $result_get_all_threads_from_subcat->fetch_assoc()) {
        //preparing query to get user info, reply info and thread infor from most recent reply from every post
        $sql_get_replies_from_thread = $conn->prepare("SELECT *, threads.id as threadId, users.id as userId FROM threads INNER JOIN replies ON replies.thread_id = threads.id INNER JOIN users ON users.id = threads.author_id WHERE threads.id = ? ORDER BY replies.date_time DESC LIMIT 1");
        $sql_get_replies_from_thread->bind_param('i', $thread_id);
        $thread_id = $row_get_all_threads_from_subcat['threadId'];
        //get user info, reply info and thread infor from most recent reply from every post
        $sql_get_replies_from_thread->execute();
        $result_get_replies_from_thread = $sql_get_replies_from_thread->get_result();
        $row_get_replies_from_thread = $result_get_replies_from_thread->fetch_assoc();
        $date_time_latest_reply = $row_get_replies_from_thread['date_time'];
        $username_latest_reply = $row_get_replies_from_thread['username'];
        //get all variables from the threads from the database
        $thread_title = $row_get_all_threads_from_subcat['title'];
        $thread_replies = $row_get_all_threads_from_subcat['replies'];
        $time_created = $row_get_all_threads_from_subcat['time_created'];

        $username = $row_get_all_threads_from_subcat['username'];
        ////query to get information from latest post
        //for each thread, place a div in the threads_container_inner
        $threads_div .= "
            <div class='thread_container'>
            
                <div class='thread_info_container'>
                    <div class = 'thread_img_container'>
                        <img src='http://localhost/f1-forum/assets/img/forum_read.png'>
                    </div>
                    <div class='thread_info'>
                        <a class='thread_link' href='thread.php?thread=" . $thread_id . "&cat=" . $cat_id . "&subcat=". $subcat_id . "'>" . $thread_title . "</a><br>
                        <span class='author_date_time'>door: <span class='username'>" . $username . "</span> op:" . $time_created . "</span>
                    </div>
                </div>
                <div class='thread_reply_count_container'>".$thread_replies."
                </div>
                <div class='thread_latest_reply_info_container'>door: <span class='username'>".$username_latest_reply."</span> <br>
                    ".$date_time_latest_reply."
                </div>
            </div>";
        //sql query to get all the subcategories
    }
    $threads_div .= "</div></div>";
    //returning div. Echo this function to see the div
    return $threads_div;
}

//
////
///////
//////////////
// add footer
// get latest reply and inside loop getThreads for each thread get date time and user from most recent reply
//////////////
///////
////
//


function getReplies($conn, $cat_id, $subcat_id, $thread_id)
{
    //emptying the replies div container
    $replies_div = "";
    //the outer div for all replies
    $replies_div.= "<div class='replies_container_inner'>";
    //prepare query to get all replies and user info
    $sql_get_replies_and_user_info_from_db = $conn->prepare("SELECT * FROM replies INNER JOIN users ON users.id = replies.user_id INNER JOIN threads ON threads.id = replies.thread_id WHERE threads.id=?");
    $sql_get_replies_and_user_info_from_db->bind_param("i", $thread_id);
    //get all replies
    $sql_get_replies_and_user_info_from_db->execute();
    $result_get_replies_and_user_info_from_db = $sql_get_replies_and_user_info_from_db->get_result();
    while ($row_get_replies_and_user_info_from_db = $result_get_replies_and_user_info_from_db->fetch_assoc()) {
        //for each reply for the current thread, make a div
        $replies_div .= "<div class='reply_container'>";
        $post_body = $row_get_replies_and_user_info_from_db['post_body'];
        $username = $row_get_replies_and_user_info_from_db['username'];
        $avatar = $row_get_replies_and_user_info_from_db['avatar'];
        $replies_div .= "<h1>Username: ".$username."</h1><br>";
        $replies_div .= "<img src='assets/avatars/".$avatar."'><br>";
        $replies_div .= "<p>Post: ".$post_body."</p><br>";

        $replies_div .= "</div'>";
    }
    $replies_div .= "</div>";

    //return replies div
    return $replies_div;
}
function showThreadHeader($conn, $cat_id, $subcat_id, $thread_id)
{
    //prepare query to get thread header
    $sql_get_thread_title = $conn->prepare("SELECT * FROM threads WHERE id=?");
    $sql_get_thread_title->bind_param("i", $thread_id);
    //get thread title
    $sql_get_thread_title->execute();
    $result_get_thread_title = $sql_get_thread_title->get_result();
    $row_get_thread_title = $result_get_thread_title->fetch_assoc();
    //assigning the title of the current thread to $thread_title
    $thread_title = $row_get_thread_title['title'];
    //this function returns the thread's title. Echo this function on thread.php to see the title of the thread
    return $thread_title;
}

function showTopicFamily($conn, $cat_id, $subcat_id)
{
    //prepare query to get topic family with the arrows
    $sql_get_topic_family = $conn->prepare("SELECT * FROM categories INNER JOIN subcategories ON subcategories.cat_id = categories.id WHERE subcategories.id=?");
    $sql_get_topic_family->bind_param("i", $subcat_id);
    //get topic family
    $sql_get_topic_family->execute();
    $result_get_topic_family = $sql_get_topic_family->get_result();
    $row_get_topic_family = $result_get_topic_family->fetch_assoc();
    //assigning the subcategory name which this thread is placed under to $subcat_title
    $subcat_title = $row_get_topic_family['subcat_title'];
    //this function returns the topic's title. Echo this function on thread.php to see the title of the thread
    return "<div class='family topic_family'><a class='family_part' href='index.php'>Forumoverzicht</a><span class='chvr_needed'></span> <a class='family_part'  href='topic.php?cat=" . $cat_id . "&subcat=" . $subcat_id . "'>".$subcat_title."</a></div>";
}

function showThreadFamily($conn, $cat_id, $subcat_id, $thread_id)
{
    //prepare query to get thread family
    $sql_get_thread_family = $conn->prepare("SELECT *, threads.id as threadId, categories.id as catId, subcategories.id as subcatId, threads.title as thread_title FROM threads INNER JOIN categories ON categories.id = threads.cat_id INNER JOIN subcategories ON subcategories.id = threads.subcat_id WHERE threads.id=?");
    $sql_get_thread_family->bind_param("i", $thread_id);
    //get thread family
    $sql_get_thread_family->execute();
    $result_get_thread_family = $sql_get_thread_family->get_result();
    $row_get_thread_family = $result_get_thread_family->fetch_assoc();
    //assigning the title of the current thread to $thread_title
    $thread_title = $row_get_thread_family['thread_title'];
    //assigning the subcategory name which this thread is placed under to $subcat_title
    $subcat_title = $row_get_thread_family['subcat_title'];
    //this function returns the thread's title. Echo this function on thread.php to see the title of the thread
    return "<p><a href='index.php'>Forumoverzicht</a>  <a href='topic.php?cat=" . $cat_id . "&subcat=" . $subcat_id . "'>".$subcat_title."</a> -> <a href='thread.php?thread=" . $thread_id . "&cat=" . $cat_id . "&subcat=". $subcat_id . "'>".$thread_title. "</a></p>";
}
function showSubcatHeader($conn, $subcat_id)
{
    //prepare query to show current subcategory at thae subcategory's threads page
    $sql_get_categories_from_db = $conn->prepare("SELECT * FROM subcategories INNER JOIN categories ON categories.id = subcategories.cat_id WHERE subcategories.id=?");
    $sql_get_categories_from_db->bind_param("i", $subcat_id);
    //show current subcategory at thae subcategory's threads page
    $sql_get_categories_from_db->execute();
    $result_get_all_categories_from_db = $sql_get_categories_from_db->get_result();
    $row_get_categories_from_db = $result_get_all_categories_from_db->fetch_assoc();
    //assigning the title of the current subcategory to $subcat_title
    $subcat_title = $row_get_categories_from_db['subcat_title'];
    //this function returns the subcategory's title. Echo this function on topic.php to see the title of the subcategory
    return $subcat_title;
}






// if($_SESSION['user']['logged_in_as'] == "admin"){
//     //setting the json categories value to blank
//     $_SESSION['categories'] = [];
//     //query to get all categories from the database
// }
?>

<style>
<?php include 'assets/css/style.min.css';?>
</style>
<!-- The script below makes sure a form is not submitted when reloading a page -->
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
