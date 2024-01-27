<?php

namespace Andres\YoucabOk\models;
use PDO;
use PDOException;
use Dotenv\Dotenv;

class Dbh {

    private $dbhost;
    private $dbname;
    private $dbuser;
    private $dbpass;
    private $dbcharset;

    protected function __construct() 
    {

        require_once __DIR__ . '/../../vendor/autoload.php';
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->dbhost = $_ENV["DBHOST"];
        $this->dbname = $_ENV["DBNAME"];
        $this->dbuser = $_ENV["DBUSER"];
        $this->dbpass = $_ENV["DBPASS"];
        $this->dbcharset = $_ENV["DBCHARSET"];
    }

    protected function connect(){
        try {
        $dbh = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname;charset=$this->dbcharset", $this->dbuser, $this->dbpass); 
        
        return $dbh;
       
        } catch (PDOException $e) {
        echo $e->getMessage();
        }
    }
}
