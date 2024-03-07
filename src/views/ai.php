<?php

use Andres\YoucabOk\models\User;
use Andres\YoucabOk\models\Word;
use Andres\YoucabOk\models\AIGenerative;
use Andres\YoucabOk\models\Paragraph;
use Andres\YoucabOk\models\Audio;

use function PHPUnit\Framework\isEmpty;

require "src/includes/header.inc.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_uuid = $_SESSION["uuid"];
$custom_dictionary = Word::getAll($user_uuid);

//extract only terms from custom dictionary (field "str")
$userCustomWordsArray = array();
foreach ($custom_dictionary as $item) {
    if (isset($item['str'])) {
        $userCustomWordsArray[] = $item['str'];
    }
}

$userCustomWordsString = implode(",", $userCustomWordsArray);

$newAi = new AIGenerative;
$user = User::getUser($uuid);
$username = $user->getUsername();

$today = date('Y-m-d');

?>

<main>
<button class="nav-main-item"><a href="?view=home">⬅ back</a></button>
    <h1 id="ai-title">YouCabulary´s<br>AI generation</h1>

    <p class="ai-p">We handle AI generation to create a random paragraph with 5 of your custom words to help you practice.<br><span>Free users are allowed to create only one paragraph a day. We are working on deliverying paid suscriptions to make this feature limitless</span></p>

    <form method="post">
        <input type="submit" value="create your daily paragraph!">
        <input type="hidden" name="user_custom_words" value="<?php echo htmlspecialchars($userCustomWordsString, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="create">
    </form>


    <div class="show-ai-paragraph-container">
        
       
            <?php
            
            //when page refresh first shows this text 
                  if ( ($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["create"]) ){
              
                    if (Paragraph::canCreateWordToday($user_uuid)) {
                   
                //get new Paragraph
                $newParagraph = $newAi->getParagraph($userCustomWordsArray);
                //esta linea la uso para probar y no gastar quota de mi API: $newParagraph = "este es un parrafo de prueba bien culero wey";

                //save to database 
                $paragraph = new Paragraph($newParagraph, $user_uuid);
                $paragraph->save();

                
                    // Instantiate the Audio class and fetch audio data
                    $audio = new Audio();
                    $audioData = $audio->getAudioFromApi($newParagraph); 
                    //correct format
                    $audioUrl = 'data:audio/mpeg;base64,' . base64_encode($audioData);
                
             ?>

                <audio controls>
                <source src="<?php echo $audioUrl; ?>" type="audio/mpeg">
                Tu navegador no puede reproducir este audio.
            </audio>

           <?php
           
                echo "<p class='new-paragraph'>" . $newParagraph . "</p>";
                echo "<p class='small-text'> creado por <b>" . strtoupper($username) . "</b>, " . $paragraph->getPublishDateFormatted() . "</p>";

                //pass today´s date as lastDate to Session to limit creation quota to one daily
                $_SESSION["lastDate"] = $today;
                  
            } else {
                
                echo "<p>" . "You have reached your daily quota and can´t create a new AI paragraph now, but you still can <b><a href='?view=archive' style='text-decoration:underline;'>check out previous paragraphs</a></b> (the ones that you created or even those from other users).<br>Keep practicing and improving your English!" . "</p>";
               
            }}

            ?>

     
    </div>

</main>


<?php
require "src/includes/footer.inc.php";
?>