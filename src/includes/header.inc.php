<?php
use Andres\YoucabOk\models\User;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    <link rel="icon" type="image/jpg" href="public/imagen.ico"/>
    <link rel="stylesheet" href="src/resources/css/normalize.css">
    <link rel="stylesheet" href="src/resources/css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

    
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


    
    <?php 
    $BG_COLOR_STD = "#ffdf66";
    $bg_color_standard = isset($_SESSION["bg_color"]) ? $_SESSION["bg_color"] : $BG_COLOR_STD;

    $FONT_COLOR_STD = "#000000";
    $font_color_standard = isset($_SESSION["font_color"]) ? $_SESSION["font_color"] : $FONT_COLOR_STD;
    ?>
    <style>
  header {
            background-color: <?php echo $bg_color_standard; ?>;
        }
    #header-text-title{
        color: <?php echo $font_color_standard; ?>
    }

    </style>