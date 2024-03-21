<?php

use Andres\YoucabOk\models\UserCelebrate;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$uuid = $_POST["uuid"];

function makeSessionMessage($str)
{
    echo $_SESSION['message'] = $str;
}
function makeSessionVariableForConfettiTrue($string)
{
    echo $_SESSION[$string] = true;
}


$userCelebrate = new UserCelebrate($uuid);
if (
    $userCelebrate->isFirstWord() ||
    $userCelebrate->isThirdWord() ||
    $userCelebrate->isThirtyWord() ||
    $userCelebrate->isSixtyWord() ||
    $userCelebrate->isThreeHundredWord()
) {
    $_SESSION['message_type'] = "celebration";
    if ( $userCelebrate->isFirstWord()) {
        makeSessionMessage("Your first word!! Welcome to your new home for English");
        makeSessionVariableForConfettiTrue("firstWordConfetti");
    } elseif ( $userCelebrate->isThirdWord()) {
        makeSessionMessage("First 3 words added! Keep up the good work");
        makeSessionVariableForConfettiTrue("thirdWordConfetti");
    } elseif (! $userCelebrate->isThirtyWord()) {
        makeSessionMessage("Thirty words, awesome job!! Perseverance reaps its rewards.");
        makeSessionVariableForConfettiTrue("thirtyWordConfetti");
    } elseif ( $userCelebrate->isSixtyWord()) {
        makeSessionMessage("Sixty words, you did some serious improvement here! Keep imporving like that");
        makeSessionVariableForConfettiTrue("sixtyWordConfetti");
    } elseif ( $userCelebrate->isThreeHundredWord()) {
        makeSessionMessage("300 words!!! Such a devotional work you have done here!! Keep practicing every day :)");
        makeSessionVariableForConfettiTrue("threeHundredWordConfetti");
    }
} 

/*
This success message is not available anymore because it interferes with scrolling, a behaviour I find more usefull after adding words.

else {
    // Standard added word message
    makeSessionMessage("Word added successfully!");

    $_SESSION['message_type'] = "success";
}*/
