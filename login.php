<?php
ob_start();
include 'conn/conn.php';
include 'handler.php';
if (isset($_SESSION['user'])) { //if login in session is not set
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - F1-Forum.nl</title>
</head>

<body>
    <!-- Main container -->
    <div class="container_main">
        <?php include 'header.php'; ?>
        <div class="container_login">
            <div class="container_login_header">
                <h2>Log hier</h2>
            </div>
            <div class="login">
                <!-- login form container -->
                <div class="container_form_login">
                    <!-- login form -->
                    <form action="" class="loginform" method="POST">
                    <!-- Username or Email-Adress Input -->
                    <div class="contact-row column-right">
                        <label for="login_username_email">Username</label>
                        <input class="login_input_box"   id="login_username_email" type="text" name="login_username_email"><br>
                    </div>
                    <!-- Password Input -->
                    <div class="contact-row column-right">
                        <label for="login_password">Password</label>
                        <input class="login_input_box"  id="login_password" type="password" name="login_password"><br>
                    </div>
                    <!-- Submit and attempt to login -->
                    <div class='submit'>
                        <button id="login" type="submit" name="login" class="button_submit">Log in</button>
                    </div>
                    <div class="register_prompt">
                        <button class="button_register_prompt"><a href="register.php">Heb je nog geen account? Klik hier om je te registreren </a></button>
                    </div>
                </form>
                </div>

                <!-- Errors occouring while logining will appear here -->
                <div class="container_error"><?php echo $error ?> </div>
            </div>

            

        </div><br><br>
        <?php include 'footer.php'; ob_end_flush();?>
</body>

</html>

