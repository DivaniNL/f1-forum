<!-- footer container -->
<?php
if (!empty($_POST["send"])) {
    $name = $_POST["userName"];
    $email = $_POST["userEmail"];
    $phone = $_POST["phone"];
    $subject = $_POST['subject'];
    $content = $name . " Left you a message:" . "\r\n" . $_POST["content"] . "" . "\r\n" . "His / Her Phone Number is: " . $phone . "";

    $toEmail = "divani.development@gmail.com";
    $mailHeaders = "From: " . $name . "<" . $email . ">\r\n";
    if (mail($toEmail, $subject, $content, $mailHeaders)) { }
}
?>
<br>
<div class="container_ftr">
    <div class="container_contact_header">
        <h2> Neem Contact op</h2>
    </div>
    <div class="contact_me">
        <div class="container_form_contact_me">

            <form id="ContactMe" action="" method="post">
                <div class="contact-row column-right">
                    <label style="padding-top: 20px;">Naam</label>
                    <span id="userName-info" class="info"></span><br />
                    <input type="text" name="userName" id="userName" class="contact_me_input_box">
                </div>
                <div class="contact-row column-right">
                    <label>Email-Adres</label> <span id="userEmail-info" class="info"></span><br />
                    <input type="text" name="userEmail" id="userEmail" class="contact_me_input_box">
                </div>
                <div class="contact-row">
                    <label>Telefoonnummer</label> <span id="phone-info" class="info"></span><br />
                    <input type="text" name="phone" id="phone" class="contact_me_input_box">
                </div>

                <div>
                    <label>Onderwerp</label> <span id="subject-info" class="info"></span><br />
                    <input type="text" name="subject" id="subject" class="contact_me_input_box"> </div>
                <div>
                    <label>Bericht</label> <span id="content-info" class="info"></span><br />
                    <textarea name="content" id="content" class="contact_me_input_box" rows="3"></textarea>
                </div>
                <div class='submit'>
                    <button type="submit" name="send" value="Send" class="button_submit">Send</button>
                </div>
            </form>
            <div id="loader-icon" style="display: none;">
            </div>
        </div>
    </div>
</div>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>