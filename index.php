<?php
include 'conn/conn.php';
include "handler.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" 
      type="image/png" 
      href="https://f1-forum.nl/assets/img/logo.png" />
</head>
<body>
    <div class="container_main">
        <?php include 'header.php';?>
        <?php echo getCategoryTree($conn);?>
        <?php include 'footer.php';?>
    </div>
    
</body>
</html>