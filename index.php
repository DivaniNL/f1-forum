<?php
include 'conn/conn.php';
include "handler.php";
?>
<!DOCTYPE html>
<html lang="en">

<body>
    <div class="container_main">
        <?php include 'header.php';?>
        <?php echo getCategoryTree($conn);?>
        <?php include 'footer.php';?>
    </div>
    
</body>
</html>