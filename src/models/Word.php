<?php

namespace Andres\YoucabOk\models;

use Andres\YoucabOk\models\Dbh;
use PDO;
use PDOException;
use Exception;

class Word extends Dbh
{

    private $str;
    private $uuid;
    private $definition;
    private $user_uuid;

    public function __construct(string $str)
    {
        $this->str = $str;
        $this->uuid = uniqid();
        $this->definition = null;
        $this->user_uuid = $_SESSION["uuid"];
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    public function getAudio($worde)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://voicerss-text-to-speech.p.rapidapi.com/?key=537576a1d0714cc88455e2c9dee70024",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "src={$worde}&hl=en-us&r=-3&c=mp3&f=8khz_8bit_mono",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: voicerss-text-to-speech.p.rapidapi.com",
                "X-RapidAPI-Key: b9944b3745msh92869d723beee38p181749jsn16d1f1982f31",
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
    public function catchDefinition($str)
    {
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
                "X-RapidAPI-Key: b9944b3745msh92869d723beee38p181749jsn16d1f1982f31"
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
    public function save()
    {
        $definition = $this->catchDefinition($this->str);
        $this->definition = $definition;
       
        if (!$definition) {
            echo "couldn´t add that word to database";
        } else {
            $sql = "INSERT INTO words(str, uuid, definition, user_uuid ) VALUES(:str, :uuid, :definition, :user_uuid)";
            try {
                $dbh = new Dbh;
                $pdo = $dbh->connect();
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':str', $this->str);
                $stmt->bindParam(':uuid', $this->uuid);
                $stmt->bindParam(':definition', $this->definition);
                $stmt->bindParam(':user_uuid', $this->user_uuid);
                $stmt->execute();
                
            } catch (Exception $e) {
                error_log("Error log: " . $e->getMessage());
                echo "mala" . $e->getMessage();
            }
        }
    }
}
