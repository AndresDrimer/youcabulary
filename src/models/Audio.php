<?php

namespace Andres\YoucabOk\models;

use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class Audio 
{
    private $audio_data;
    
    public function __construct(){
        $this->audio_data = null;
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
            CURLOPT_POSTFIELDS => "src={$worde}&hl=en-us&r=0&c=mp3&f=44.1khz_16bit_mono",
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


    public function setAudio($audioData)
    {
        $this->audio_data = $audioData;
    }

    public function getAudio()
    {
        return $this->audio_data;
    }

    
}

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