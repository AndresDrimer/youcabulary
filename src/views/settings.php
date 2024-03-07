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

$bg_color = isset($_SESSION["bg_color"]) ? $_SESSION["bg_color"] : "#ffdf66";
?>
<main>

    <button class="nav-main-item"><a href="?view=home">⬅ back</a></button>
    <h1 id="home-title">Settings</h1>

    <p class="center">YouCabulary allows you to customize at your own preference:</p>

    <!--choose background color-->
    <form action="src/controllers/settings.php" method="post" class="form-settings">
        <div>
            <label>Pick a new header and footer´s background color: </label>
            <input type="color" name="bg_color" value="<?php echo $bg_color ;?>">
        </div>
        <input type="submit" value="Change background color">
    </form>
    <!--restore-color-->
    <?php
    
    if( isset($_SESSION["bg_color"])&&($_SESSION["bg_color"] !== "#ffdf66") ):?>

    <form action="src/controllers/settings.php" method="post" class="form-settings">
        <input type="hiddden" name="restore_bg_color">
        <input type="submit" value="back to default color">
    </form>
    <?php endif; ?>

    <!--change voice-->
    <!--*OJO* con esto deberia cambiar el sistema de model, para que los audios no se graben... ver esta idea, son 350 disparos por dia, pero pòdrias pedirle que los pase con uno u otro acento, el mismo fragmento... eso suma bastante... quizas una cantdad por dia por usuario para que no se queme tan rapido y despues un mensjae que diga se agoto la cuota, pronto version paga..
Poner emojis de banderaS?-->
    <?php
$voices = [
    [
        "country_code" => "en-us",
        "country_name" => "US",
        "gender" => "male",
        "name" => "Mike",
        "flag" => "public/flags/us.png"
    ],
    [
        "country_code" => "en-us",
        "country_name" => "US",
        "gender" => "female",
        "name" => "Amy",
        "flag" => "public/flags/us.png"
    ],
    [
        "country_code" => "en-au",
        "country_name"  => "Australia",
        "gender" => "female",
        "name" => "Isla",
        "flag" => "public/flags/au.png"
    ],
    [
        "country_code" => "en-au",
        "country_name"  => "Australia",
        "gender" => "male",
        "name" => "Mason",
        "flag" => "public/flags/au.png"
    ],
    [
        "country_code" => "en-in",
        "country_name"  => "India",
        "gender" => "female",
        "name" => "Jai",
        "flag" => "public/flags/in.png"
    ],
    [
        "country_code" => "en-in",
        "country_name"  => "India",
        "gender" => "male",
        "name" => "Ajit",
        "flag" => "public/flags/in.png"
    ],
    [
        "country_code" => "en-ie",
        "country_name"  => "Irlanda",
        "gender" => "male",
        "name" => "Oran",
        "flag" => "public/flags/ei.png"
    ],
    [
        "country_code" => "en-gb",
        "country_name" => "Great Britain",
        "gender" => "female",
        "name" => "Nancy",
        "flag" => "public/flags/gb.png"
    ],
    [
        "country_code" => "en-gb",
        "country_name"  => "Great Britain",
        "gender" => "female",
        "name" => "Harry",
        "flag" => "public/flags/gb.png"
    ],
    [
        "country_code" => "en-ca",
        "country_name"  => "Canada",
        "gender" => "female",
        "name" => "Clara",
        "flag" => "public/flags/ca.png"
    ],
    [
        "country_code" => "en-ca",
        "country_name"  => "Canada",
        "gender" => "male",
        "name" => "Mason",
        "flag" => "public/flags/ca.png"
    ],
];
?>
    <form action="src/controllers/settings.php" method="post" class="form-settings">
        <div id="form-settings-language">
            <?php foreach ($voices as $voice): ?>
            <div class="name-flag-cont">
                <input type="radio" name="voice"
                    id="voice_<?php echo $voice['country_code'] . '_' . $voice['gender'] . '_' . $voice['name']; ?>"
                    value="<?php echo $voice['country_code'] . '_' . $voice['gender'] . '_' . $voice['name']; ?>" />
                <label
                    for="voice_<?php echo $voice['country_code'] . '_' . $voice['gender'] . '_' . $voice['name']; ?>">

                    <?php echo $voice['name'] . ' (' . $voice['country_name'] . ', ' . $voice['gender'] . ')'; ?>
                  
                    <img src="<?php echo $voice['flag']; ?>" alt="flag" class="flag" />

                </label>

            </div>
            <?php endforeach; ?>
        </div>
        <div>
            <input type="submit" value="Change Voice" id="change-voice-btn">
        </div>
    </form>
</main>


<?php
require "src/includes/footer.inc.php";
?>