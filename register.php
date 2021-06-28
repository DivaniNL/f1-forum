<?php
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
    <title>Register - F1-Forum.nl</title>
</head>

<body>
    <!-- Main container -->
    <div class="container_main">
        <?php include 'header.php'; ?>
        <div class="container_register">
            <div class="container_register_header">
                <h2>Registreer hier</h2>
            </div>
            <div class="register">
                <!-- Register form container -->
                <div class="container_form_register">
                    <!-- Register form -->
                    <form class='registerform' action="" method="POST" enctype="multipart/form-data">
                        <!-- Username Input -->
                        <div class="contact-row column-right">
                            <label for="username">Gebruikersnaam</label>
                            <input class="register_input_box" id="username" type="text" name="username"><br>
                        </div>
                        <!-- First Name Input -->
                        <div class="contact-row column-right">
                            <label for="firstname">Voornaam</label>
                            <input class="register_input_box" id="firstname" type="text" name="firstname"><br>
                        </div>
                        <!-- Last Name Input -->
                        <div class="contact-row column-right">
                            <label for="lastname">Achternaam</label>
                            <input class="register_input_box" id="lastname" type="text" name="lastname"><br>
                        </div>
                        <!-- Email-Adress Input -->
                        <div class="contact-row column-right">
                            <label for="email">Email-Adres</label>
                            <input class="register_input_box" id="email" type="email" name="email"><br>
                        </div>
                        <!-- Email-Adress Confirmation -->
                        <div class="contact-row column-right">
                            <label for="confirm_email">Bevestig Email-Adres</label>
                            <input class="register_input_box" id="confirm_email" type="email" name="confirm_email"><br>
                        </div>
                        <!-- Password Input -->
                        <div class="contact-row column-right">
                            <label for="password">Wachtwoord</label>
                            <input class="register_input_box" id="password" type="password" name="password" pattern="^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{7,}$" title="Minimum of 7 characters. Should have at least one special character and one number and one UpperCase Letter."><br>
                        </div>
                        <!-- Password Confirmation -->
                        <div class="contact-row column-right">
                            <label for="confirm_password">Bevestig Wachtwoord</label>
                            <input class="register_input_box" id="confirm_password" type="password" name="confirm_password" pattern="^(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{7,}$" title="Minimum of 7 characters. Should have at least one special character and one number and one UpperCase Letter."><br>
                        </div>
                        <!-- Favourite Driver Input -->
                        <div class="contact-row column-right">
                            <label for="driver">Favoriete Coureur</label>
                            <select class="register_input_box" name="favourite_driver" id="favourite_driver">
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

                        </div>
                        <!-- Avatar Input -->
                        <div class="contact-row column-right file-container">
                            <label class="file">
                                <input class="" type="file" name="avatar" id="avatar">
                                Kies je avatar
                            </label>
                        </div>

                        <!-- Submit And Register -->
                        <div class='submit'>
                            <button id="register" type="submit" class="button_submit" name="register">Registreer</button>

                        </div>
                        <div class="login_prompt">
                            <button class="button_login_prompt"><a href="login.php">Heb je al een account? Klik hier om in te loggen.</a></button>
                        </div>
                    </form>
                </div>

                <!-- Errors occouring while registering will appear here -->

            </div>

            
            <div class="container_error"><?php echo $error ?> </div>
        </div><br><br>
        <?php include 'footer.php'; ?>
</body>

</html>