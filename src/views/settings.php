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

$bg_color = isset($_SESSION["bg_color"]) ? $_SESSION["bg_color"] : $BG_COLOR_STD;

$font_color = isset($_SESSION["font_color"]) ? $_SESSION["font_color"] : $FONT_COLOR_STD;

$voiceCountry = isset($_SESSION["voiceCountry"]) ? $_SESSION["voiceCountry"] : "en-us";

$voiceName = isset($_SESSION["voiceName"]) ? $_SESSION["voiceName"] : "en-us";

?>

<main>

    <button class="nav-main-item"><a href="?view=home">⬅ back</a></button>
    <h1 id="home-title">Settings</h1>

    <p class="center" id="word-count-home">Set your YouCabulary preferences:</p>





    <!--change voice-->
    <div id="colors-change-main-container">
        <h3 class="settings-sub-title">Select Voice</h3>
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
            <p class="tiny-note">* Please note that spoken texts are being stored in your database, so it won´t change
                once listened. But you can recall new words any time!</p>
        </form>

        <!--CHANGE COLORS-->
        <h3 class="settings-sub-title">Select Header & Footer Colors</h3>


        <!--choose background color-->
        <div id="color-forms-grid-container">
            <form action="src/controllers/settings.php" method="post" class="form-settings-colors">
                <div>
                    <label id="pick-color-label">Pick your background color: </label>
                    <input type="color" name="bg_color" value="<?php echo $bg_color ;?>">
                </div>
                <input type="submit" value="Change background color">
            </form>


            <!--change font-color-->
            <form action="src/controllers/settings.php" method="post" class="form-settings-colors">
                <div>
                    <label id="pick-color-label">Pick your font color: </label>
                    <input type="color" name="font_color" value="<?php echo $font_color ;?>">
                </div>
                <input type="submit" value="Change font color">
            </form>
        </div>
        <!--restore-color-->

        <?php
if( (isset($_SESSION["bg_color"]) && $_SESSION["bg_color"] !== $BG_COLOR_STD) || (isset($_SESSION["font_color"]) && $_SESSION["font_color"] !== $FONT_COLOR_STD) ): ?>

        <form action="src/controllers/settings.php" method="post" class="form-settings">
            <input type="hidden" name="restore_bg_color">
            <input type="submit" value="back to default colors" id="restore-btn">
        </form>

        <?php endif; ?>
    </div>
</main>


<?php
require "src/includes/footer.inc.php";
?>