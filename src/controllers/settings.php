<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Modifies session´s bg-color
if ( isset($_POST['bg_color']) ) {
    $_SESSION["bg_color"] = $_POST['bg_color'];
echo $_POST["bg_color"];
header('Location: ../../?view=settings');
exit;

}
if ( isset($_POST['restore_bg_color']) ) {
    $_SESSION["bg_color"] = $BG_COLOR_STD;
    $_SESSION["font_color"] = $FONT_COLOR_STD;
    header('Location: ../../?view=settings');
exit;
}

// Modifies session´s font-color
if ( isset($_POST['font_color']) ) {
    $_SESSION["font_color"] = $_POST['font_color'];
echo $_POST["font_color"];
header('Location: ../../?view=settings');
exit;

}
if ( isset($_POST['restore_bg_color']) ) {
    $_SESSION["bg_color"] = "#ffdf66";
    header('Location: ../../?view=settings');
exit;
}




//Voice selector
if ( isset($_POST['voice-selector']) ){
    $languageSelectedArray = explode("_",$_POST['voice']);
    $selectedCountry =  $languageSelectedArray[0] ;
     $selectedGenre = $languageSelectedArray[1];
    $selectedName = $languageSelectedArray[2];
    $_SESSION["voiceName"] = $selectedName;
    $_SESSION["voiceCountry"] = $selectedCountry;
    header('Location: ../../?view=settings');
    exit;
}

