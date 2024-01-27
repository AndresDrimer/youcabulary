<?php
require __DIR__ . '/../../vendor/autoload.php';
use Andres\YoucabOk\models\Word;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if( $_SERVER["REQUEST_METHOD"] == "POST"){
    if( !isset($_POST["new_word"]) ){
        $_SESSION['message'] = "You forgot to add the new word"; 
        $_SESSION['message_type'] = "danger";
    }

    $word = $_POST["new_word"];
    $word = htmlspecialchars($word, ENT_QUOTES, 'UTF-8');

    try{
        $wordObj = new Word($word);
        $wordObj->save();
        var_dump($wordObj);
    }
    catch(PDOException $e){
        $_SESSION['message'] = $e->getMessage(); 
        $_SESSION['message_type'] = "danger";
    }
    header("Location: ../../?view=home");
}
