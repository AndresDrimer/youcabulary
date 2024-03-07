<?php

use Andres\YoucabOk\models\User;
use Andres\YoucabOk\models\Word;
use Andres\YoucabOk\models\AIGenerative;
use Andres\YoucabOk\models\Paragraph;


use function PHPUnit\Framework\isEmpty;

require "src/includes/header.inc.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_uuid = $_SESSION["uuid"];
$user = User::getUser($uuid);
$username = $user->getUsername();

$user_paragraphs = Paragraph::getUserParagraphs($user_uuid);
$other_users_paragraphs = Paragraph::getOtherUsersParagraphs($user_uuid);
?>

<main>
<button class="nav-main-item"><a href="?view=home">⬅ back</a></button>
    <h1 id="home-title">YouCabulary´s<br>ARCHIVE</h1>
    
<div class="archive-btns-container">
    <form action="" method="post">
        <input type="hidden" name="my_archive">
        <input type="submit" value="😎 my archive">
    </form>

    <form action="" method="post">
        <input type="hidden" name="restofthepeople_archive">
        <input type="submit" value="🪐 others´ archive">
    </form>
</div>
<div class="show-ai-paragraph-container">
    <?php
         if ( ($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["my_archive"]) ){
          
           
            foreach ($user_paragraphs as $paragraph){
                
                $audioData = $paragraph["audio"];
                if (isset($audioData)){
                $audioUrl = 'data:audio/mpeg;base64,' . base64_encode($audioData);
                echo"<div style='text-align:right'><audio controls><source src='{$audioUrl}' type='audio/mpeg'>Tu navegador no puede reproducir este audio.</audio></div>";
                }
                echo "<p class='new-paragraph smaller'>" . $paragraph["paragraph"] . "</p>";
                echo "<p class='small-text'> creado por <b>" . strtoupper($username) . "</b>, " . date('d-m-Y', strtotime($paragraph["created_at"]))  . "</p>";
                echo"<p class='separator'>〰</p>";
            }
         }
         if ( ($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["restofthepeople_archive"]) ){
            foreach ($other_users_paragraphs as $paragraph){
                
                $eachUser = User::getUser($paragraph['user_uuid']); 
                
                $audioData = $paragraph["audio"];
                if (isset($audioData)){
                $audioUrl = 'data:audio/mpeg;base64,' . base64_encode($audioData);

                echo"<div style='text-align:right'><audio controls><source src='{$audioUrl}' type='audio/mpeg'>Tu navegador no puede reproducir este audio.</audio></div>";
                }
                echo "<p class='new-paragraph smaller'>" . $paragraph["paragraph"] . "</p>";
                echo "<p class='small-text'> creado por <b>" . $eachUser->getUsername() . "</b>, " . date('d-m-Y', strtotime($paragraph["created_at"]))  . "</p>";
                echo"<p class='separator'>〰</p>";
            }
         }
    ?>
    </div>
</main>


<?php
require "src/includes/footer.inc.php";
?>