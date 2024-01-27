<?php
require "src/includes/header.inc.php";



if (isset($_SESSION['message'])) {
    echo '<div class="back-overlay"></div><div class="alert alert-' . $_SESSION['message_type'] . '"><div class="close">✖</div>' . $_SESSION['message'] . '</div>';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<main>
    <div class="sign-container">
        <div id="logo-login-complete">
            
            <p id="three-words">THREE WORDS A DAY WILL TAKE YOU THERE</p>
          

            <img src="public/imagen.png" alt="logo" id="login-image-logo">
            <p id="login-text-today">Today</p>
            <h1 id="login-text-title">YouCabulary</h1>
        </div>

        <div>

            <label class="switch">
                <input type="checkbox" id="checkBoxSign">
                <span class="slider round">
                    <span class="label" id="label-signup">Signup</span>
                    <span class="label" id="label-register">Register</span>
            </label>


            <form method="post" action="src/controllers/register_control.php" id="registerForm">
                <input type="hidden" name="register-form">
                <input type="text" name="username" placeholder="Username..." autofocus>
                <input type="email" name="email" placeholder="Email...">
                <input type="password" name="password" placeholder="Password...">
                <input type="submit" value="Register New User" class="submit-btn">
            </form>

            <form method="post" action="src/controllers/login_control.php" id="signupForm">
                <input type="hidden" name="signup-form">
                <input type="text" name="username" placeholder="Username..." autofocus>
                <input type="password" name="password" placeholder="Password...">
                <div id="login-remember-container"><label>
                        Recordarme
                    </label>
                    <input type="checkbox" name="remember_me">
                </div>
                <input type="submit" value="Sign up" class="submit-btn">

                <p><a href="?view=reset_pass" style="font-size: small;">Olvidaste tu contraseña?</a></p>
            </form>

        </div>
    </div>
</main>


<?php
require "src/includes/footer.inc.php";
?>