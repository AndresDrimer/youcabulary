<?php

use Andres\YoucabOk\models\User;
use Andres\YoucabOk\models\Word;
use Andres\YoucabOk\models\AIGenerative;
use Andres\YoucabOk\models\Paragraph;
use Andres\YoucabOk\models\Audio;

use function PHPUnit\Framework\isEmpty;

require "src/includes/header.inc.php";
require "src/resources/audio_tools.php";

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

//establish default voice if not yet selected
if (!isset($_SESSION["voiceName"])){
    $_SESSION["voiceName"] = "Mike";
    $_SESSION["voiceCountry"] =  "en-us";
}
$voiceName = $_SESSION["voiceName"];
$voiceCountry = $_SESSION["voiceCountry"]; 

?>

<main>
    <nav class="nav-main">
        <button class="nav-main-item"><a href="?view=home">⬅ back</a></button>
        <a href="?view=archive" class="nav-main-item">Archive</a>
        <a href="?view=settings" class="nav-main-item">Settings</a>
    </nav>
    <h1 id="ai-title">YouCabulary´s<br>AI creation</h1>

    <p class="ai-p">We handle AI generation to create a random and meaningfull paragraph with 5 of your custom words.<br><span>*  You are allowed to create 1 new paragraph each day</span></p>

    <form method="post">
        <input type="submit" value="create your daily paragraph!">
        <input type="hidden" name="user_custom_words"
            value="<?php echo htmlspecialchars($userCustomWordsString, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="create">
    </form>


    <div class="show-ai-paragraph-container">


        <?php
            
            //when page refresh first shows this text 
                  if ( ($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["create"]) ){
              
                    if (Paragraph::canCreateWordToday($user_uuid)) {
                                 
                        //get new Paragraph array
                         $newParagraphArray = $newAi->getParagraph($userCustomWordsArray); 
              
                        if (array_key_exists("success", $newParagraphArray) && $newParagraphArray["success"]){
                   
                            if (array_key_exists("message", $newParagraphArray)){
                                $newParagraph = $newParagraphArray["message"];
                                    //esta linea la descomento solo para probar y no gastar quota de mi API: $newParagraph = "este es un parrafo de prueba";

                                     //save to database 
                                     $paragraph = new Paragraph($newParagraph, $user_uuid, $voiceCountry, $voiceName);
                                     $paragraph->save();

                                
                                    // Instantiate the Audio class and fetch audio data
                                    $audio = new Audio();
                                    $audioData = $audio->getAudioFromApi($newParagraph); 
                                    //correct format
                                    $audioUrl =audioFormatter($audioData);
                                ?>

                                <audio controls>
                                    <source src="<?php echo $audioUrl; ?>" type="audio/mpeg">
                                    Tu navegador no puede reproducir este audio.
                                </audio>

                                <?php
                        
                                    echo "<p class='new-paragraph'>" . $newParagraph . "</p>";
                                    echo "<p class='small-text'> creado por <b>" . strtoupper($username) . "</b>, " . $paragraph->getPublishDateFormatted() . "</p>";

                                    //pass today´s date as lastDate to Session to limit creation quota to one daily
                                    $_SESSION["lastDate"] = $today;?><?php
                                }
                            }
           
                        else {
                            if (array_key_exists("message", $newParagraphArray)){
                          
                                
                                echo "<p>". $newParagraphArray["message"]. " Please feel free to <b><a href='?view=archive' style='text-decoration:underline;'>check out previous paragraphs</a></b> (the ones that you created or even those from other users).</p>";    
                                }
                            }
            } else {
                
                echo "<p>" . "You have reached your daily quota and can´t create a new AI paragraph now, but you still can <b><a href='?view=archive' style='text-decoration:underline;'>check out previous paragraphs</a></b> (the ones that you created or even those from other users).<br>Keep practicing and improving your English!" . "</p>";
               
            }
        }

            ?>


    </div>

</main>


<?php
require "src/includes/footer.inc.php";
?>