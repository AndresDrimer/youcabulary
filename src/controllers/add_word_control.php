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
    $uuid = $_POST["uuid"];

    try{
        $wordObj = new Word($word, $uuid);
        $wordObj->save();
        $_SESSION['message'] = "Word added successfully!"; 
        $_SESSION['message_type'] = "success";
    }
    catch(PDOException $e){
        $_SESSION['message'] = $e->getMessage(); 
        $_SESSION['message_type'] = "danger";
           exit();
    }
    header("Location: ../../?view=home");
    exit();
}
