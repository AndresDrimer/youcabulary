<?php
require "src/includes/header.inc.php";
?>

<main>

  <div id="loadingContainer" class="hide">
    <span class="material-symbols-outlined" id="loadingIcon">
      mail
    </span>
    <div id="loadingText">Sending email...</div>
  </div>

  <form method="post" action="src/controllers/forgottenpass_control.php" id="reset-pass-Form">
    <p style="text-align: center; font-size: larger">¡No te preocupes! Escribí tu email y recibirás instrucciones para recuperarla.</p>
    <input type="hidden" name="reset-password-form">
    <input type="email" name="email" placeholder="Email..." autofocus>

    <input type="submit" value="Recuperar contraseña" class="submit-btn">
  </form>

</main>

<?php
require "src/includes/footer.inc.php";
?>