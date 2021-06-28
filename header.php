<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>F1-Forum</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<div class="container_hdr">
    <ul class="hdr_left">
        <li class='hdr_container_logo'>
            <a href="index.php"><img class='hdr_logo' src="assets/img/logo.png" alt="F1-Forum-logo"></a>
        </li>
        <li class='hdr_container_text'>
            <h1 class="hdr_txt_h1">F1-Forum</h1>
            <p class="hdr_txt_p">Het nieuwste Nederlandse Formule 1 forum!</p>
        </li>
    </ul>
    <ul class = "hdr_right">
        <?php 
            if(isset($_SESSION['user'])){ //if login in session is not set
                echo "<li class='auth_box a_1'><a onclick='logOut();'>Afmelden&nbsp;<i class='fa-power-off fa'></i></a></li>";
            }
            else{
                echo "<li class='auth_box a_3'><a href='login.php'>Inloggen&nbsp;<i class=' fa-power-off fa'></i></a></li><li class='auth_box a_2'><a href='register.php'>Registeren&nbsp;<i class='fa-pencil-square-o fa'></i></a></li>";
            }
            if(isset($_SESSION['user'])){
                if($_SESSION['user']['logged_in_as'] == "admin"){
                    echo "<li class='auth_box a_4'><a href='back/index.php'>Beheerder&nbsp;<i class='fa-user fa'></i></a></li>";
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
var hdr_right = $('.hdr_right');


if(hdr_right.children().length == 1) {
    hdr_right.addClass('onechild');
} 
</script>