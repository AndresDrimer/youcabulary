<?php

use Andres\YoucabOk\models\User;
use Andres\YoucabOk\models\Word;

require "src/includes/header.inc.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_uuid = $_SESSION["uuid"];
$custom_dictionary = Word::getAll($user_uuid);


if (isset($_SESSION['message'])) {
    echo '<div class="back-overlay"></div><div class="alert alert-' . $_SESSION['message_type'] . '"><div class="close">✖</div>' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<main>
    <button onclick="showMPhilosophyModal()" id="philo-open-btn">Our philosophy</button>
    <div id="philosophy-modal" style="display:none;">
        <button onClick="closePhiloModal()" id="close-philo-modal" class="close">✖</button>
        <h3>YouCabulary Philosophy</h3>
        <p>Existe un método secreto que muchos de los mejores lingüistas del mundo comparten y que los ayudó durante generaciones a aprender nuevos idiomas a una velocidad pasmosa.Como siempre la simpleza es la clave, y el método consiste en una sola regla: aprender 3 palabras nuevas cada día. Esta página no es más ue una herramienta para ayudarte en tu camino a dominar el inglés en muy poco tiempo.<br>No lo olvides: <span style="font-weight:bold;">3 palabras nuevas, cada día, todos los días.</span></p>
    </div>


    <div id="loading" style="display: none;">
    <img src="public/stripe.gif" alt="Loading..." >
    <p >Loading...</p>
    
</div>

    <h1 id="home-title">YouCabulary</h1>
    <p id="word-count-home"><?php if (count($custom_dictionary) > 0) {
                                echo "Tu diccionario ya tiene " . count($custom_dictionary) . " ";
                                echo (count($custom_dictionary) > 1) ? "palabras" : "palabra";
                            } ?></p>

    <form action="src/controllers/add_word_control.php" method="POST" id="add-word">
        <input type="text" name="new_word" placeholder="New word...">
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
        $wordObj = new Word($word['str']);
        $audioData = $wordObj->getAudioFromDatabase($word['str']);
        $audioUrl = 'data:audio/mpeg;base64,' . base64_encode($audioData);
    ?>

        <div class="dict-container" id="def-<?php echo $index; ?>">
            <h2><?php echo htmlspecialchars($word['str']); ?></h2>

            <div class="bin-and-audio-container">

               <audio controls>
                    <source src="<?php echo $audioUrl; ?>" type="audio/mpeg">
                    Tu navegador no puede reproducir este audio.
                </audio>
                <form action="src/controllers/del_word_control.php" method="post" class="bin-complete-form">
                    <input type="hidden" name="delete_word" value="<?php echo $word['str']; ?>">
                    <button type="submit" value="" id="del-word-btn"><span class="material-symbols-outlined">
                            delete
                        </span></button>
                </form>
            </div>
            <p style="font-size:small;"><?php echo (($word['definition'])); ?></p>
        </div>
    <?php endforeach; ?>


</main>

<?php
require "src/includes/footer.inc.php";
?>