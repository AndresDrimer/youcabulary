<?php
require __DIR__ . '/../../vendor/autoload.php';
use Andres\YoucabOk\models\User;


if (session_status() == PHP_SESSION_NONE) {
    session_start();
   }

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["signup-form"]) ){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $rememberMe = isset($_POST["remember_me"]);

  $user = User::checkCredentials($username, $password);

  if( $user ){
    $_SESSION["username"] = $user['username'];
       $_SESSION["uuid"] = $user['uuid'];
       $userObj = User::getUser($user['uuid']);
       if( $rememberMe ){
       $userObj->createRememberMeCookie($userObj->getUuid(), strtotime('+1 month'));
       }

 header("Location: ../../?view=home");
  } else {
   
    $_SESSION['message'] = "Credentials failed, please try to Sign in again";
    $_SESSION['message_type'] = "danger";

    ob_end_clean();
    header("Location: ../../?view=register_login");
  }
}