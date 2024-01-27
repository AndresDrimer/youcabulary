<?php

ini_set('session.use_only_cookies', 1); //1 means true
ini_set('session.use_strict_mode', 1); //forces to use a session id created on my server, and makes it more complex -so it´s more difficult for some third party to guess.

//modifys some params of my cookie to make it more secure
session_set_cookie_params([
    'lifetime' => 86400, //i´ll try to have it for a whole day (it was 1800-half an hour)
    'domain' => 'localhost', //change when deploying for ej:example.com
    'path' => '/',
    'secure' => true,
    'httponly' => true

]); 

ini_set('session.gc_maxlifetime', 86400); //beside cookie´s expiration time, session will remain open for a whole day. 86400 = 60*60*24 = 24 hours.

session_start();

//to make session_id even stronger, and re do it each every 30mins:
if (!isset($_SESSION['last_regeneration'])){
    
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} else {

    $interval = 60 * 30;

    if (time() - $_SESSION['last_regeneration'] >= $interval){

        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}
