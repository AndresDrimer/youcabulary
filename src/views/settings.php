<?php

use Andres\YoucabOk\models\User;
use Andres\YoucabOk\models\Word;
use Andres\YoucabOk\models\AIGenerative;
use Andres\YoucabOk\models\Paragraph;


use function PHPUnit\Framework\isEmpty;

require "src/includes/header.inc.php";
require "src/resources/audio_tools.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$bg_color = isset($_SESSION["bg_color"]) ? $_SESSION["bg_color"] : "#ffdf66";

$voiceCountry = isset($_SESSION["voiceCountry"]) ? $_SESSION["voiceCountry"] : "en-us";

$voiceName = isset($_SESSION["voiceName"]) ? $_SESSION["voiceName"] : "en-us";

?>

<main>

    <button class="nav-main-item"><a href="?view=home">⬅ back</a></button>
    <h1 id="home-title">Settings</h1>

    <p class="center" id="word-count-home">Set your YouCabulary preferences:</p>

    <!--choose background color-->
    <h3 class="settings-sub-title">Select color</h3>
    <form action="src/controllers/settings.php" method="post" class="form-settings">
        <div>
            <label id="pick-color-label">Pick a color for header and footer´s background: </label>
            <input type="color" name="bg_color" value="<?php echo $bg_color ;?>">
        </div>
        <input type="submit" value="Change background color">
    </form>
    <!--restore-color-->
    <?php
    
    if( isset($_SESSION["bg_color"])&&($_SESSION["bg_color"] !== "#ffdf66") ):?>

    <form action="src/controllers/settings.php" method="post" class="form-settings">
        <input type="hidden" name="restore_bg_color">
        <input type="submit" value="back to default color">
    </form>
    <?php endif; ?>

    <!--change voice-->
    <h3 class="settings-sub-title">Select voice</h3>
    <form action="src/controllers/settings.php" method="post" class="form-settings">
        <input type="hidden" name="voice-selector">
        <div id="form-settings-language">
            <?php foreach ($voices as $voice): ?>
            <div class="name-flag-cont">
                <input type="radio" name="voice"
                    id="voice_<?php echo $voice['country_code'] . '_' . $voice['gender'] . '_' . $voice['name']; ?>"
                    value="<?php echo $voice['country_code'] . '_' . $voice['gender'] . '_' . $voice['name']; ?>" 
                    <?php echo ($voice['name'] == $voiceName) ? 'checked' : ''; ?> />
                <label
                    for="voice_<?php echo $voice['country_code'] . '_' . $voice['gender'] . '_' . $voice['name']; ?>">

                    <?php echo $voice['name'] . ' (' . $voice['country_name'] . ', ' . $voice['gender'] . ')'; ?>
                  
                    <img src="<?php echo $voice['flag']; ?>" alt="flag" class="flag" />

                </label>

            </div>
            <?php endforeach; ?>
        </div>
        <div>
            <input type="submit" value="Change voice" id="change-voice-btn">
        </div>
        <p class="tiny-note">* Please note that spoken texts are being stored in your database, so it won´t change once listened. But you can recall new words any time!</p>
    </form>
</main>


<?php
require "src/includes/footer.inc.php";
?>