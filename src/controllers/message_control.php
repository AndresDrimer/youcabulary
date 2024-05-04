<?php

use Andres\YoucabOk\models\UserCelebrate;

function handleMessages($session) {
    //audio
    $messageTypeToAudio = include "src/controllers/audio_control.php";

    //message´s type
    if (isset($session['message'])) {
        if (array_key_exists($session['message_type'], $messageTypeToAudio)) {
            $audioFilePath = $messageTypeToAudio[$session['message_type']];
        } else {
            // Handle the case where the message type is not defined
            $audioFilePath = ''; // Default to no audio or set a default audio path
        }
        echo '<div class="back-overlay"></div><div class="alert alert-'. $session['message_type'].  '" id="close-self-modal"><div class="close">✖</div>'. $session['message']. '<audio src="'. $audioFilePath. '" autoplay></audio></div>';
        unset($session['message']);
        unset($session['message_type']);
    }
}
