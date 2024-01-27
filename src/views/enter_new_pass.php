<?php
require "src/includes/header.inc.php";
if(isset($_GET["token"])){
    $token = $_GET["token"];
}
?>

<main>

<form method="post" action="src/controllers/forgottenpass_control.php" id="reset-pass-Form">
          <p style="text-align: center; font-size: larger">Introducí tu nueva contraseña</p>
            <input type="hidden" name= "change-password-form">
            <input type="password" name="new_password" placeholder="New password..." autofocus id="new-pass">
            <input type="password" name="new_password_copy" placeholder="Re-type new password..." id="new-pass-copy">
            <input type="hidden" name="token_value" value="<?php echo $token?>">
            <input type="submit" value="Recuperar contraseña"  id="submit-send-new-pass-btn" disabled>
        </form>

</main>
<script src="src/resources/js/disableSendButton.js"></script>
<?php 
require "src/includes/footer.inc.php";
?>