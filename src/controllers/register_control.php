<?php
require __DIR__ . '/../../vendor/autoload.php';
use Andres\YoucabOk\models\User;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
   }

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["register-form"]) ){
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    //sanitize
    $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    //check empty fields
    if(empty($username) || empty($email) || empty($password)){
        
    
        $_SESSION['message'] = "Please fill in all fields to Register"; 
        $_SESSION['message_type'] = "danger";
    }

    $user = new User($username, $email, $password);
    $user->save();
   
   
       header("Location: ../../?view=register_login");    
}