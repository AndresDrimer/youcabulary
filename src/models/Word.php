<?php

namespace Andres\YoucabOk\models;

use Andres\YoucabOk\models\Dbh;
use PDO;
use PDOException;
use Exception;
use Dotenv\Dotenv;


require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class Word extends Dbh
{

    private $str;
    private $uuid;
    private $definition;
    private $user_uuid;
    private $audio_data;

    public function __construct(string $str)
    {


        $this->str = $str;
        $this->uuid = uniqid();
        $this->definition = null;
        $this->user_uuid = $_SESSION["uuid"];
        $this->audio_data = null;
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    public function getAudioFromApi($worde)
    {

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
            CURLOPT_POSTFIELDS => "src={$worde}&hl=en-us&r=-3&c=mp3&f=8khz_8bit_mono",
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

    public static function getAll($user_uuid)
    {

        $sql = "SELECT * FROM words WHERE user_uuid = :user_uuid ORDER BY str ASC";
        try {
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_uuid', $user_uuid);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            echo "no pude trare las palabras";
        }
    }
    public function getDefinitionFromApi($str)
    {
        $x_rapid_api_key = $_ENV["X_RAPIDAPI_KEY"];
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://dictionary-by-api-ninjas.p.rapidapi.com/v1/dictionary?word={$str}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: dictionary-by-api-ninjas.p.rapidapi.com",
                "X-RapidAPI-Key: {$x_rapid_api_key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response, true);
            if ($data["valid"]) {
                return $data["definition"];
            }
        }
    }

    public function setAudio($audioData)
    {
        $this->audio_data = $audioData;
    }

    public function getAudio()
    {
        return $this->audio_data;
    }

    public function save()
    {
        $definition = $this->getDefinitionFromApi($this->str);
        $this->definition = $definition;

        if (!$definition) {

            $_SESSION['message'] = 'No encontramos esa palabra en el diccionario, por favor asegurate de no haber escrito alguna letra equivocada';
            $_SESSION['message_type'] = 'danger';
        } else {
            $this->audio_data = $this->getAudioFromApi($this->str);

            $audioData = $this->getAudio();

            $sql = "INSERT INTO words(str, uuid, definition, user_uuid, audio_data ) VALUES(:str, :uuid, :definition, :user_uuid, :audio_data)";
            try {
                $dbh = new Dbh;
                $pdo = $dbh->connect();
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':str', $this->str);
                $stmt->bindParam(':uuid', $this->uuid);
                $stmt->bindParam(':definition', $this->definition);
                $stmt->bindParam(':user_uuid', $this->user_uuid);
                $stmt->bindParam(':audio_data', $audioData);
                $stmt->execute();
            } catch (Exception $e) {
                error_log("Error log: " . $e->getMessage());
                $_SESSION['message'] = 'Hubo un error al guardar la palabra en la base de datos';
                $_SESSION['message_type'] = 'danger';
            }
        }
    }
    public function getAudioFromDatabase($worde)
    {
        $sql = "SELECT audio_data FROM words WHERE str = :str AND user_uuid = :user_uuid";
        try {
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':str', $worde);
            $stmt->bindParam(':user_uuid', $this->user_uuid);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['audio_data'];
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
        }
    }
    public function deleteWord($worde){
        $sql = "DELETE FROM words WHERE str = :str AND user_uuid = :user_uuid";
        try {
            $dbh = new Dbh;
            $pdo = $dbh->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':str', $worde);
            $stmt->bindParam(':user_uuid', $this->user_uuid);
            $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Error: ". $e->getMessage());
          
        }
    }
}
