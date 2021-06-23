<?php
include 'conn/conn.php';
include "handler.php";
?>
<!DOCTYPE html>
<html lang="en">

<body>
    <div class="container_main">
        <?php include 'header.php';?>
        <div class='container_categoryTree'>
            <?php echo getCategoryTree($conn);?>
        </div>
    </div>
</body>
</html>