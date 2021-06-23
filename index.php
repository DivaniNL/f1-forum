<?php
include "handler.php";
?>
<!DOCTYPE html>
<html lang="en">

<body>
    <div class="container_main">
        <?php include 'header.php';?>
        <div class='categories_container'>
            <?php echo getCategoryTree($conn);?>
        </div>
    </div>
</body>
</html>