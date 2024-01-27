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
