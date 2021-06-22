<div class="container_hdr">
    <ul class="hdr_left">
        <li class='hdr_container_logo'>
            <img class='hdr_logo' src="assets/img/logo.png" alt="F1-Forum-logo">
        </li>
        <li class='hdr_container_text'>
            <h1>F1-Forum</h1>
            <p>Het nieuwste Nederlandse Formule 1 forum!</p>
        </li>
    </ul>
    <ul class = "hdr_right">
        <?php 
            if(isset($_SESSION['user'])){ //if login in session is not set
                echo "<li class='auth_box a_1'><a onclick='logOut();'>Log out&nbsp;<i class='fa-power-off fa'></i></a></li>";
            }
            else{
                echo "<li class='auth_box a_3'><a href='login.php'>Login&nbsp;<i class=' fa-power-off fa'></i></a></li><li class='auth_box a_2'><a href='register.php'>Register&nbsp;<i class='fa-pencil-square-o fa'></i></a></li>";
            }
            if(isset($_SESSION['user'])){
                if($_SESSION['user']['logged_in_as'] == "admin"){
                    echo "<li class='auth_box a_4'><a href='back/index.php'>Admin&nbsp;<i class='fa-user fa'></i></a></li>";
                }
            }
        ?>
    </ul>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
function logOut() {
    window.location = "logOut.php";
    return false;
}
</script>