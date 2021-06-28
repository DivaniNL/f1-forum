<?php
if (!headers_sent()) {
if(!isset($_SESSION['user'])){ 
    header("Location: ../index.php");
}
}
?>