<?php 

require __DIR__ . '/../../vendor/autoload.php';
use Andres\YoucabOk\models\Word;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['delete_word'])) {
    $worde = $_POST['delete_word'];
    $uuid = $_POST["uuid"];
    $wordObj = new Word($worde, $uuid);
    $wordObj->deleteWord($worde);
    $_SESSION['message'] = "palabra borrada de tu colección";
    $_SESSION['message_type'] = "deleted";
    ob_end_clean(); // Limpiar el buffer de salida
    header("Location: ../../?view=home"); 
    exit();
}
