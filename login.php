<?php
include 'handler.php';
if(isset($_SESSION['user'])){ //if login in session is not set
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log In - F1-Forum.nl</title>
    </head>
    <body>
        <!-- Main container -->
        <div class="container">
            <!-- Login Form container -->
            <div class="form-container">
                <!-- Login Form -->
                <form action="" method="POST">
                    <!-- Username or Email-Adress Input -->
                    <label for="login_username_email">Username</label>
                    <input id="login_username_email" type="text" name="login_username_email">
                    <!-- Password Input -->
                    <label for="login_password">Password</label>
                    <input id="login_password" type="password" name="login_password">
                    <!-- Add avatar later -->
                    <!-- Submit and attempt to login -->
                    <input id="login" type="submit" name="login">
                </form>
            </div>
            <!-- Div with redirect to the register page -->
            <div class="register-container">
                Don't have an account? <a href="register.php">Register</a> Here!
            </div>
            <!-- Errors occouring while trying to log in will appear here -->
            <div class="error-container"><?php echo $error ?> </div>
        </div>
    </body>
</html>