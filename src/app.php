<?php

if (isset($_SESSION["username"])) {
    $view = $_GET["view"] ?? "";
    switch ($view) {
        case "enter_new_pass":
            require "views/enter_new_pass.php";
            break;
        case "check_email":
            require "views/check_email.php";
            break;
        case "ai":
            require "views/ai.php";
            break;
        case "archive":
            require "views/archive.php";
            break;
        case "settings":
                require "views/settings.php";
                break;
        default:
            require "views/home.php";
    }
} else {
    $view = $_GET["view"] ?? "";
    switch ($view) {
        case "reset_pass":
            require "views/reset_pass.php";
            break;
        case "enter_new_pass":
            require "views/enter_new_pass.php";
            break;
        case "check_email":
            require "views/check_email.php";
            break;
        default:
            require "views/register_login.php";
    }
}
