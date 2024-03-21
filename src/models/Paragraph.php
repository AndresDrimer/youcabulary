<?php

namespace Andres\YoucabOk\models;

use PDO;
use PDOException;

use Andres\YoucabOk\models\Dbh;
use Andres\YoucabOk\models\Audio;
class Paragraph extends Dbh
{

    private $uuid;
    private $paragraph;
    private $user_uuid;
    private $audio;
    private $created_at;
    private $voiceCountry;
    private $voiceName;

    public function __construct(string $paragraph, string $user_uuid, string $voiceCountry, string $voiceName)
    {
        
        $this->uuid = uniqid();
        $this->paragraph = $paragraph;
        $this->user_uuid = $user_uuid;
        $this->audio = null;
        $this->created_at = date("Y-m-d H:i:s");
        $this->voiceCountry = $voiceCountry;
        $this->voiceName = $voiceName;
    }
    
    public static function getAllParagraphs(){
        try{
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            
            $paragraphs = $pdo->query("SELECT * FROM paragraphs");
            $paragraphs = $paragraphs->fetchAll(PDO::FETCH_ASSOC);  
            return $paragraphs;
        }
                catch (PDOException $e){
                    error_log("Error: ". $e->getMessage());
                }
            }

    public static function getUserParagraphs(string $user_uuid)
    {
    $sql = "SELECT * FROM paragraphs WHERE user_uuid = :user_uuid";
        try{
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_uuid', $user_uuid);
            $stmt->execute();
            $paragraphs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $paragraphs;

        } catch (PDOException $e){
            error_log("Error: ". $e->getMessage());
        }
    }

    public static function getOtherUsersParagraphs(string $user_uuid)
    {
        $sql = "SELECT * FROM paragraphs WHERE user_uuid != :user_uuid";
            try{
                $dbh = new Dbh;
                $pdo = $dbh->connect();
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':user_uuid', $user_uuid);
                $stmt->execute();
                $paragraphs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $paragraphs;
            } catch (PDOException $e){
                error_log("Error: ". $e->getMessage());
            }
        }

    public function getPublishDate(){
        return $this->created_at;
    }
    public function getPublishDateFormatted(){
        return date('d-m-Y', strtotime($this->created_at));
    }



//tambien esta el mismo metodo en model Word
    public function getAudioFromDatabase($worde)
    {
        $sql = "SELECT audio_data FROM paragraphs WHERE paragraph = :paragraph AND user_uuid = :user_uuid";
        try {
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':paragraph', $worde);
            $stmt->bindParam(':user_uuid', $this->user_uuid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['audio_data'];
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
        }
    }

    function removeEveryMarkUpTags($text) { 
        return strip_tags($text);
    }


    public static function canCreateWordToday(string $user_uuid)
    {
        $today = date('Y-m-d');
        $sql = "SELECT created_at FROM paragraphs WHERE user_uuid = :user_uuid ORDER BY created_at DESC LIMIT 1";
        try {
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_uuid', $user_uuid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && date('Y-m-d', strtotime($result['created_at'])) == $today) {
                return false; // El usuario ya ha creado una palabra hoy
            } else {
                return true; // El usuario puede crear una nueva palabra hoy
            }
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false; // Por precaución, asumimos que no se puede crear una nueva palabra
        }
    }

    public function save()
    {
        // Clean paragraph for audio processing
        $cleanParagraphForAudio = $this->removeEveryMarkUpTags($this->paragraph);

        // Instantiate Audio class
        $audio = new Audio();

        // Use Audio class to fetch audio data
        $audioData = $audio->getAudioFromApi($cleanParagraphForAudio, $this->voiceCountry, $this->voiceName);
        
        $this->audio = $audioData;

        $sql = "INSERT INTO paragraphs (uuid, paragraph, user_uuid, audio, created_at) VALUES (:uuid, :paragraph, :user_uuid, :audio, :created_at)";
        try {
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":uuid", $this->uuid);
            $stmt->bindParam(":paragraph", $this->paragraph);
            $stmt->bindParam(":user_uuid", $this->user_uuid);
            $stmt->bindParam(":audio", $this->audio);
            $stmt->bindParam(":created_at", $this->created_at);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
        }
    }
}