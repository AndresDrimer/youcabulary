<?php

namespace Andres\YoucabOk\models;

use Andres\YoucabOk\models\Dbh;
use PDO;
use PDOException;
use Exception;

class PasswordReset extends Dbh
{

    private $email;
    private $token;
    private $tokenExpiry;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function generateToken()
    {
        $this->token = bin2hex(random_bytes(64));
        $this->tokenExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    }

    public function saveToken()
    {
        try {
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $sql = "UPDATE users SET password_reset_token = :token, password_reset_token_expiry = :expiry WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':token', $this->token);
            $stmt->bindParam(':expiry', $this->tokenExpiry);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function verifyToken($token)
    {
        try {
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $sql = "SELECT * FROM users WHERE password_reset_token = :token AND password_reset_token_expiry > NOW()";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ? $user['email'] : null;
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function resetPassword($newPassword)
    {
        try {
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $sql = "UPDATE users SET hash_password = :password, password_reset_token = NULL, password_reset_token_expiry = NULL WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', $this->email);

            // Imprime el estado de la preparación de la consulta
            echo "Estado de preparación de la consulta: " . $stmt->errorCode() . "\n";

            // Ejecuta la consulta
            $result = $stmt->execute();

            // Imprime el resultado de la ejecución de la consulta
            echo "Resultado de la ejecución de la consulta: " . $result . "\n";

            // Obtiene información sobre la última consulta ejecutada
            $info = $pdo->errorInfo();

            // Imprime la información de la última consulta
            echo "Información de la última consulta: \n";
            print_r($info);
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function getToken()
    {
        return $this->token;
    }
}
