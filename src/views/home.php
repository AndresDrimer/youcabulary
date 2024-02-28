<?php

use Andres\YoucabOk\models\User;
use Andres\YoucabOk\models\Word;
use Andres\YoucabOk\models\UserCelebrate;

require "src/includes/header.inc.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_uuid = $_SESSION["uuid"];
$custom_dictionary = Word::getAll($user_uuid);

////////////////////////////////////////
//////////handle Message´s Modals///////
////////////////////////////////////////

//audio
$messageTypeToAudio = include "src/controllers/audio_control.php";

//message´s type
if (isset($_SESSION['message'])) {
    if (array_key_exists($_SESSION['message_type'], $messageTypeToAudio)) {
        $audioFilePath = $messageTypeToAudio[$_SESSION['message_type']];
    } else {
        // Handle the case where the message type is not defined
        $audioFilePath = ''; // Default to no audio or set a default audio path
    }
    echo '<div class="back-overlay"></div><div class="alert alert-' . $_SESSION['message_type'] .  '" id="close-self-modal"><div class="close">✖</div>' . $_SESSION['message'] . '<audio src="' . $audioFilePath . '" autoplay></audio></div>';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

function makeTruncatedDef($definition)
{
    // REGEX for identify number of definitons inside Definition1
    preg_match_all('/(\d+\.)/', $definition, $matches);
    // Get index of first point following number gretaer than 3
    $lastMatchIndex = false;
    foreach ($matches[0] as $key => $match) {
        if ((int)$matches[1][$key] >  3) {
            $lastMatchIndex = $key;
            break;
        }
    }
    // Cut text beyond 3. point
    if ($lastMatchIndex !== false) {
        $truncatedDefinition = substr($definition,  0, strpos($definition, $matches[0][$lastMatchIndex]));
    } else {
        //if its smaller than 4 points, show entire definition
        $truncatedDefinition = $definition;
    }
    
    return $truncatedDefinition;
}

$userCelebrate = new UserCelebrate($uuid);
$wordCount = $userCelebrate->getUserWordCount();
$_SESSION["wordCount"] = $wordCount;

?>

<main>

<div id="confetti-container"></div>

    <button onclick="showMPhilosophyModal()" id="philo-open-btn">Our philosophy</button>
    <div id="philosophy-modal" style="display:none;">
        <button onClick="closePhiloModal()" id="close-philo-modal" class="close">✖</button>
        <h3>YouCabulary Philosophy</h3>
        <p>Existe un método secreto que muchos de los mejores lingüistas del mundo comparten y que los ayudó durante
            generaciones a aprender nuevos idiomas a una velocidad pasmosa. Como siempre la simpleza es la clave, y el
            método consiste en una sola regla: aprender 3 palabras nuevas cada día. Esta página no es más que una
            herramienta para ayudarte en tu camino a dominar el inglés en muy poco tiempo.<br>No lo olvides: <span
                style="font-weight:bold;">3 palabras nuevas, cada día, todos los días.</span></p>
    </div>


    <div id="loading" style="display: none;">
        <img src="public/stripe.gif" alt="Loading...">
        <p>Loading...</p>

    </div>
<?php if($wordCount === 0){
    echo"<div class='starter-text'><h2>Agregá tu primera palabra para comenzar<span id='three-dots'>...</span></h2></div>";
} ?>
    <h1 id="home-title">YouCabulary</h1>
    <p id="word-count-home"><?php if (count($custom_dictionary) > 0) {
                                echo "Tu diccionario ya tiene " . count($custom_dictionary) . " ";
                                echo (count($custom_dictionary) > 1) ? "palabras" : "palabra";
                            } ?></p>

    <form action="src/controllers/add_word_control.php" method="POST" id="add-word">
        <input type="hidden" name="uuid" value="<?php echo $user_uuid; ?>">
        <input type="text" name="new_word" placeholder="New word or expression...">
        <input type="submit" value="add" autofocus>
    </form>

    <div class="show-terms">
        <?php
        foreach ($custom_dictionary as $index => $word) {
            echo '<span class="each-term" id="term-' . $index . '">' . $word["str"] . '</span>';
        }
        ?>
    </div>


    <?php
    foreach ($custom_dictionary as $index => $word) :
        $wordObj = new Word($word['str'], $_SESSION["uuid"]);
        $audioData = $wordObj->getAudioFromDatabase($word['str']);
        $audioUrl = 'data:audio/mpeg;base64,' . base64_encode($audioData);

    ?>

    <div class="dict-container" id="def-<?php echo $index; ?>">
        <h2 class="word-title"><?php echo htmlspecialchars($word['str']); ?></h2>

        <div class="bin-and-audio-container">

            <audio controls>
                <source src="<?php echo $audioUrl; ?>" type="audio/mpeg">
                Tu navegador no puede reproducir este audio.
            </audio>
            <form action="src/controllers/del_word_control.php" method="post" class="bin-complete-form">
                <input type="hidden" name="uuid" value="<?php echo $user_uuid; ?>">
                <input type="hidden" name="delete_word" value="<?php echo $word['str']; ?>">
                <button type="submit" value="" id="del-word-btn"><span class="material-symbols-outlined">
                        delete
                    </span></button>
            </form>
        </div>


        <!-- first definition logic-->
        <?php
            echo "<div style='font-size:small;'>";
            if (!is_null($word['definition'])) {

                echo "<p class='definition-classic-title'>Classic</p><br>";
            ?>

        <?php

                $truncatedDefinition = makeTruncatedDef($word["definition"]);

                echo "<p>" . htmlspecialchars($truncatedDefinition) .
                    "</p>";


                echo "</div>";
            }
   
            ?>

        <!-- second definition logic-->

        <?php if (!is_null($word['second_definition_array'])) : ?>
        <?php
                $secondDefinitions = json_decode($word['second_definition_array'], true);
                echo "<p class='definition-urban-title'>Urban</p><br>";
                foreach ($secondDefinitions as $secondDefinitionIndex => $secondDefinition) : ?>
        <p class='definition-2' style="font-size:small;">

            <?php
                        echo ($secondDefinitionIndex + 1) . ') ';
                        echo htmlspecialchars($secondDefinition['definition']); ?>
            Example: <?php echo htmlspecialchars($secondDefinition['example']); ?>
        </p>
        <?php endforeach; ?>
        <?php endif; echo"</div>"; ?> 
    </div>
   <p class="separator">〰</p>
    <?php endforeach; ?>


</main>

<!--evaluate celebration for confetti-->
<?php
  
    if( isset($_SESSION["firstWordConfetti"]) && $_SESSION["firstWordConfetti"] ){
        $userCelebrate->confetti();
        $_SESSION["firstWordConfetti"]= false;
    }
    if( isset($_SESSION["thirdWordConfetti"]) && $_SESSION["thirdWordConfetti"] ){
        $userCelebrate->confetti();
        $_SESSION["thirdWordConfetti"]= false;
    }
    if( isset($_SESSION["thirtyWordConfetti"]) && $_SESSION["thirtyWordConfetti"] ){
        $userCelebrate->confetti();
        $_SESSION["thirtyWordConfetti"]= false;
    }
    if( isset($_SESSION["sixtyWordConfetti"]) && $_SESSION["sixtyWordConfetti"] ){
        $userCelebrate->confetti();
        $_SESSION["sixtyWordConfetti"]= false;
    }
    if( isset( $_SESSION["threeHundredWordConfetti"]) && $_SESSION["threeHundredWordConfetti"] ){
        $userCelebrate->confetti();
        $_SESSION["threeHundredWordConfetti"]= false;
    }
?>

<?php
require "src/includes/footer.inc.php";
?>