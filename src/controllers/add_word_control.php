<?php
require __DIR__ . '/../../vendor/autoload.php';

use Andres\YoucabOk\models\Word;
use Andres\YoucabOk\models\UserCelebrate;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["new_word"])) {
        $_SESSION['message'] = "You forgot to add the new word";
        $_SESSION['message_type'] = "danger";
    } else {
        $word = $_POST["new_word"];
        $word = htmlspecialchars($word, ENT_QUOTES, 'UTF-8');
        $uuid = $_POST["uuid"];
        $voiceCountry = $_POST["voiceCountry"];
        $voiceName = $_POST["voiceName"];

        try {
            $wordObj = new Word($word, $uuid, $voiceCountry, $voiceName);
            $wordObj->save();
            $lastWordId = $wordObj->getUuid();
           
            //Celebration controller
            include "celebrations_control.php";
          header("Location: ../../?view=home&lastWordId=" . $lastWordId);

        } catch (PDOException $e) {
            $_SESSION['message'] = $e->getMessage();
            $_SESSION['message_type'] = "danger";
            exit();
        }
    }
   
    exit();
}
