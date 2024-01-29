<?php
use Andres\YoucabOk\models\User;

if( isset($_SESSION["uuid"]) ){
    $uuid = $_SESSION["uuid"];
  
    $user = User::getUser($uuid);
    $username = $user->getUsername();
   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youcabulary</title>
    <link rel="stylesheet" href="src/resources/css/normalize.css">
    <link rel="stylesheet" href="src/resources/css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
</head>
<body>
    <header>
        <div>
    <a href="<?php echo $_SERVER["PHP_SELF"] ?>">
    <img src="public/imagen.png" id="logo-header"></img></a></div>
    <div>
        <h1 id="header-text-title">YouCabulary</h1>
  </div>
    <div id="logout-container">
    <a href="src/controllers/logout_control.php"><span class="material-symbols-outlined" id="logout-sign">
logout
</span></a>    
    <?php if(isset($username)){echo"<p id='hello'>Welcome " .ucfirst(lcfirst($username)) . "!</p>";} ?></div>
    </header>