<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';

use Andres\YoucabOk\models\PasswordReset;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
   }

define('BASE_URL', 'http://localhost/youcab_ok/');

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


function sendPasswordResetEmail($email, $token) {
    $mail = new PHPMailer(true);

    // Configuración del correo...
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV["MAILER_USERNAME"]; // Tu correo de Gmail
    $mail->Password = $_ENV["APP_PWD_GOOGLE"]; // Tu contraseña de Gmail
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;



    // Dirección del remitente 
    $mail->setFrom($_ENV["MAILER_USERNAME"]); 

    // Destinatario
    $mail->addAddress($email);   
    
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    // Contenido
    $mail->Subject = 'Restablecimiento de contraseña';
    
    $mail->Body = '<p>Haz clic en el enlace para restablecer tu contraseña: <a href="' . BASE_URL . '?view=enter_new_pass&token=' . $token . '">Restablecer contraseña</a></p>';

    try {
        $mail->send();
        echo '<script>alert("Mensaje enviado")</script>';
       
    } catch (Exception $e) {
        echo "No se pudo enviar el mensaje. Error del mailer: {$mail->ErrorInfo}";
        error_log("Error: " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["reset-password-form"])) {
       
        $email = $_POST["email"];
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $_SESSION["email"] = $email;
       
        $passwordReset = new PasswordReset($email);
        $passwordReset->generateToken();
        $passwordReset->saveToken();
        $token = $passwordReset->getToken();

        echo "<script>document.getElementById('loadingContainer').classList.remove('hide');</script>";
       
        sendPasswordResetEmail($email, $token);
       
        echo "<script>document.getElementById('loadingContainer').classList.add('hide');</script>";

        header("Location: ../../?view=check_email");
    }

    if (isset($_POST["change-password-form"])) {
        
        $email = $_SESSION["email"];
        $token = $_POST["token_value"];
        $newPassword = $_POST["new_password"];
        $newPasswordCopy = $_POST["new_password_copy"];
        
       
        $passwordReset = new PasswordReset($email);
        $isValidToken = $passwordReset->verifyToken($token);
        
        if ($isValidToken) {
            $passwordReset->resetPassword($newPassword);
            
            $_SESSION['message'] = '¡Tu contraseña ha sido restablecida con éxito!';
            $_SESSION['message_type'] = 'success';
            
            header('Location: ../../?view=register_login');
            exit;
        }
        else{
            echo"<script>alert('Tu nueva contraseña no se pudo validar')</script>";
        }
    }
    
}
