<?php

namespace Andres\YoucabOk\models;

use PDO;
use PDOException;

use Andres\YoucabOk\models\Dbh;

class Paragraph extends Dbh
{

    private $uuid;
    private $paragraph;
    private $user_uuid;
    private $audio;
    private $created_at;

    public function __construct(string $paragraph, string $user_uuid)
    {
        
        $this->uuid = uniqid();
        $this->paragraph = $paragraph;
        $this->user_uuid = $user_uuid;
        $this->audio = null;
        $this->created_at = date("Y-m-d H:i:s");
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

//this method is duplicated form model Word, should be refactored later
    public function getAudioFromApi($worde)
    {
/*Mike voice en-us male
//Amy voice en-us female//irlanda
//autralia
//india

elegir las mejores de cada pais, homre y mujer
en-us male: Mike ; en-us
en-us- female: Amy; en-us
autralia female: isla ; en-au
australia male: Mason ; en-au
india female:Jai; en-in
india male: Ajit;  en-in         
irlanda male: Oran ;en-ie
great britan female: nancy; en-gb
great britain male: harry; en-gb
canada female: clara ; en-ca
canada male: Mason ;   en-ca
*/
/*estoy probando la calidad, si es 44 y 16, o si menos igual queda bien*/
/*tengo que dejar de grabar y probarlo mucho, me da 350 hits por dia ,da para probar bien*/
/*la formula deberia cambiarse asi:
    $country="en-us"
    $locutorName="Mike"
    CURLOPT_POSTFIELDS => "src={$worde}&hl={$country},v={$locutorName},&r=0&c=mp3&f=48khz_16bit_mono",
    
    
    */
        $curl = curl_init();
        $api_key = $_ENV["TEXT_TO_SPEECH_API_KEY"];
        $x_rapid_api_key = $_ENV["X_RAPIDAPI_KEY"];
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://voicerss-text-to-speech.p.rapidapi.com/?key={$api_key}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "src={$worde}&hl=en-us,v:'Mike',&r=0&c=mp3&f=48khz_16bit_mono",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: voicerss-text-to-speech.p.rapidapi.com",
                "X-RapidAPI-Key: {$x_rapid_api_key}",
                "content-type: application/x-www-form-urlencoded"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function setAudio(){
        $this->audio = $this->getAudioFromApi($this->paragraph);
    }
    public function getAudio(){
        return $this->audio;
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
        //grabbing audio data with no tags
        $cleanParagraphForaudio = $this->removeEveryMarkUpTags($this->paragraph);
        $this->audio = $this->getAudioFromApi($cleanParagraphForaudio);

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
