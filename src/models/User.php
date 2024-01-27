<?php

namespace Andres\YoucabOk\models;
use Andres\YoucabOk\models\Dbh;
use PDO;
use PDOException;
use Exception;

class User extends Dbh{

    private $uuid;
    private $username;
    private $password;
    private $email;
    private $hash_password;

    public function __construct($username, $email, $password ){
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->uuid = uniqid();
    }

    public function getUuid(){
        return $this->uuid;
    }
    public function setUuid( $uuid ){
        $this->uuid = $uuid;
    }
    public function getUsername(){
        return $this->username;
    }
    public function setUsername($username){
        $this->username = $username;
    }
    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    public function getPassword(){
        return $this->password;
    }
    public function setPassword($password){
        $this->password = $password;
    }
 
    public function save(){
        try {
        $dbh = new Dbh();
        $pdo = $dbh->connect();   
        $sql = "INSERT INTO users (uuid, username, email, hash_password) VALUES (:uuid, :username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":uuid", $this->uuid);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $hash_password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->password = $hash_password;
        $stmt->bindParam(":password", $this->password);
        $stmt->execute();
        } catch (PDOException $e){
            error_log(  "PDOException: " . $e->getMessage() );
        }
    }
    
   public static function newUserFromArray($array){
   $password = isset($array['password']) ? $array['password'] : null;
   $hash_password = isset($array['hash_password']) ? $array['hash_password'] : null;
   $user = new User($array['username'], $array['email'], $password);
   $user->setUuid($array['uuid']);
 
   return $user;
}
public static function getUser($uuid){
    $dbh = new Dbh();
    $pdo = $dbh->connect();
    $sql = "SELECT * FROM users WHERE uuid = :uuid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":uuid", $uuid);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ? User::newUserFromArray($user) : null;
  }
  public function updateUser($uuid, $username, $email, $password)
  {
      try {

          if (self::checkCredentials($username, $password)) {

              $sql = "UPDATE users SET username = :username, email = :email, hash_password = :hash_password WHERE uuid = :uuid";
              $dbh = new Dbh;
              $pdo = $dbh->connect();
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(':username', $username);
              $stmt->bindParam(':email', $email);
              $hash_password = $this->setPassword($password);
              $stmt->bindParam(':hash_password', $hash_password);
              $stmt->bindParam(':uuid', $uuid);
              $stmt->execute();
          } else {

              throw new Exception("Las credenciales son incorrectas.");
          }
      } catch (PDOException $e) {
          // PDO error
          error_log("Error: " . $e->getMessage());
      } catch (Exception $e) {
          // Credentials Error
          error_log("Error: " . $e->getMessage());
      }
  }
    
    public static function checkCredentials($username, $password) {
        try {
            $dbh = new Dbh();
            $pdo = $dbh->connect();
            
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
           
            if ($user !== false && password_verify($password, $user['hash_password'])) {
                return $user;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
      }
      
    public function generateToken($length = 64)
    {
        return bin2hex(random_bytes($length));
    }

    public function createRememberMeCookie($uuid, $expiryTime)
{
    $token = $this->generateToken();
    $expiryDate = date('Y-m-d H:i:s', $expiryTime);

    $sql = "UPDATE users SET remember_token = :token, remember_token_expiry = :expiry WHERE uuid = :uuid";

    try {
        $dbh = new Dbh;
        $pdo = $dbh->connect();
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', $expiryDate);
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();

        if (setcookie('remember_me', $token, $expiryTime, '/', '', false, true)) {
            return true;
        } else {
            throw new Exception('Failed to create remember me cookie');
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
    }
}

    public function validateRememberMeCookie()
    {
        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];

            $sql = "SELECT * FROM users WHERE remember_token = :token";
            try {
                $dbh = new Dbh;
                $pdo = $dbh->connect();
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':token', $token);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && $user['remember_token_expiry'] > date('Y-m-d H:i:s')) {
                    return true;
                }
            } catch (Exception $e) {
                error_log("Error: " . $e->getMessage());
            }
        }
        return false;
    }
}