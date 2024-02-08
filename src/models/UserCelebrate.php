<?php

namespace Andres\YoucabOk\models;

use Andres\YoucabOk\models\User;
use Andres\YoucabOk\models\Dbh;
use PDOException;
use PDO;

class UserCelebrate extends User
{

    private $user_uuid;

    public function __construct($user_uuid)
    {
        $this->user_uuid = $user_uuid;
    }

    public function getUserWordCount()
    {
        $sql = "SELECT COUNT(*) AS wordCount FROM words WHERE user_uuid = :user_uuid";
        try {
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_uuid', $this->user_uuid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $wordCount = $result["wordCount"];
            return $wordCount;
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    public function isFirstWord()
    {
        $wordCount = $this->getUserWordCount();
        return $wordCount ===  1;
    }
    public function isThirdWord()
    {
        $wordCount = $this->getUserWordCount();
        return $wordCount ===  3;
    }
    public function isThirtyWord()
    {
        $wordCount = $this->getUserWordCount();
        return $wordCount ===  30;
    }
    public function isSixtyWord()
    {
        $wordCount = $this->getUserWordCount();
        return $wordCount ===  60;
    }
    public function isThreeHundredWord()
    {
        $wordCount = $this->getUserWordCount();
        return $wordCount ===  300;
    }

    public function confetti()
    {
                echo "<script type='text/javascript'>
            confetti({
                particleCount:   200,
                spread:   100,
                origin: { y:   0.6 }
            });
        </script>";
    
    }
}
