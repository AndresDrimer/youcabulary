<?php
// Init session
session_start();

// Destroy any session´s variables
$_SESSION = array();


//To completelly destroy session, also destroy session´s cookie 
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
  );
}

// Destroy session.
session_destroy();

header("Location: ../../?view=register_login");
exit;

