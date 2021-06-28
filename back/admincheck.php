<?php
if (!headers_sent()) {
if(isset($_SESSION['user'])){ 
    if($_SESSION['user']['logged_in_as'] != "admin"){
        header("Location: ../index.php");
    }else{
        $_SESSION['admin_page'] = 'home';
    }
}
}
?>