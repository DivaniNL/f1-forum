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
        <title>Register - F1-Forum.nl</title>
    </head>
    <body>
        <!-- Main container -->
        <div class="container">
    	    <!-- Register form container -->
            <div class="form-container">
    	        <!-- Register form -->
                <form action="" method="POST">
                    <!-- Username Input -->
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username"><br>
                    <!-- First Name Input -->
                    <label for="firstname">First Name</label>
                    <input id="firstname" type="text" name="firstname"><br>
                    <!-- Last Name Input -->
                    <label for="lastname">Last Name</label>
                    <input id="lastname" type="text" name="lastname"><br>
                    <!-- Email-Adress Input -->
                    <label for="email">Email-Adress</label>
                    <input id="email" type="email" name="email"><br>
                    <!-- Email-Adress Confirmation -->
                    <label for="confirm_email">Confirm Email-Adress</label>
                    <input id="confirm_email" type="email" name="confirm_email"><br>
                    <!-- Password Input -->
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" pattern="^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{7,}$" title="Minimum of 7 characters. Should have at least one special character and one number and one UpperCase Letter."><br>
                    <!-- Password Confirmation -->
                    <label for="confirm_password">Confirm Password</label>
                    <input id="confirm_password" type="password" name="confirm_password" pattern="^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{7,}$" title="Minimum of 7 characters. Should have at least one special character and one number and one UpperCase Letter."><br>
                    <!-- Favourite Driver Input -->
                    <label for="driver">Favourite Driver</label>
                    <select name="favourite_driver" id="favourite_driver">
                        <option value="Lewis Hamilton">Lewis Hamilton</option>
                        <option value="Valtteri Bottas">Valtteri Bottas</option>
                        <option value="Max Verstappen">Max Verstappen</option>
                        <option value="Sergio Perez">Sergio Perez</option>
                        <option value="Lando Norris">Lando Norris</option>
                        <option value="Daniel Ricciardo">Daniel Ricciardo</option>
                        <option value="Sebastian Vettel">Sebastian Vettel</option>
                        <option value="Lance Stroll">Lance Stroll</option>
                        <option value="Fernando Alonso">Fernando Alonso</option>
                        <option value="Esteban Ocon">Esteban Ocon</option>
                        <option value="Charles Leclerc">Charles Leclerc</option>
                        <option value="Carlos Sainz">Carlos Sainz</option>
                        <option value="Pierre Gasly">Pierre Gasly</option>
                        <option value="Yuki Tsunoda">Yuki Tsunoda</option>
                        <option value="Kimi Raikkonen">Kimi Raikkonen</option>
                        <option value="Antonio Giovinazzi">Antonio Giovinazzi</option>
                        <option value="Mick Shumacher">Mick Shumacher</option>
                        <option value="Nikita Mazepin">Nikita Mazepin</option>
                        <option value="George Russel">George Russel</option>
                        <option value="Nicholas Latifi">Nicholas Latifi</option>
                    </select>
                    <!-- Submit And Register -->
                    <input id="register" type="submit" name="register">
                </form>
            </div>
            <div class="login-container">
                Already have an account? <a href="login.php">Log In</a> Here!
            </div>
            <!-- Errors occouring while registering will appear here -->
            <div class="error-container"><?php echo $error ?> </div>
        </div>
    </body>
</html>


