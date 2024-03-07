<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si se ha enviado una nueva elección de color
if ( isset($_POST['bg_color']) ) {
    $_SESSION["bg_color"] = $_POST['bg_color'];
echo $_POST["bg_color"];
}
if ( isset($_POST['restore_bg_color']) ) {
    $_SESSION["bg_color"] = "#ffdf66";
}

header('Location: ../../?view=index');
exit;

?>