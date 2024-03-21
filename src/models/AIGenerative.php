<?php

namespace Andres\YoucabOk\models;

use Dotenv\Dotenv;


//1000 free hits/month
class AIGenerative 
{

    
    private $chatgptapi;

    public function __construct()
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

    
        $this->chatgptapi = $_ENV["CHATGPT_API"];
    }


    public function filterNoMoreThanFiveWords(mixed $customArray)
    {
        //select only 5 random words if custom_dictionary has more
        if (count($customArray) > 5) {
            $random_keys = array_rand($customArray, 5);
            $NoMoreThanFiveWordsArray = array();
            foreach ($random_keys as $key) {
                $NoMoreThanFiveWordsArray[] = $customArray[$key];
            }
        } else {
            $NoMoreThanFiveWordsArray = implode(', ', $customArray);
        }

        return $NoMoreThanFiveWordsArray;
    }

   
    //it offers free 50hits/month
    public function getParagraph(mixed $userCustomWordsArray)
    {

        $curl = curl_init();
        $chatgptapi = $_ENV["CHATGPT_API"];

        //select only 5 random words from custom_dictionary´s terms if it contains more than 5
        $words =  $this->filterNoMoreThanFiveWords($userCustomWordsArray);

        if (is_array($words)) {
            $words = implode(", ", $words);
        }


        curl_setopt_array($curl, [
            CURLOPT_URL => "https://chatgpt-42.p.rapidapi.com/conversationgpt4",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'messages' => [
                        [
                                        'role' => 'user',
                                        'content' => "Can you generate a meaningful paragraph including all this words:{$words} . I would like to see those words I´ve mentioned being bold in your text response aswell. Please do not include  string 'Absolutely! Here's the generated paragraph with the requested words included and bolded:'. I want to see only the rest of the response. Don´t forget to display ** and ** between each of the $words. Thank  you"
                        ]
                ],
                'system_prompt' => '',
                'temperature' => 0.9,
                'top_k' => 5,
                'top_p' => 0.9,
                'max_tokens' => 256,
                'web_access' => null
            ]),
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: chatgpt-42.p.rapidapi.com",
                "X-RapidAPI-Key:{$chatgptapi}",
                "content-type: application/json"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $responseArray = json_decode($response, true);

            
            if (isset($responseArray['result'])) {
              
               $response = $this->convertAstericsIntoBoldFont( $responseArray['result']);
               return ["message" => $response, "success"=> true];        

            } else {
                return [
                    "message" => "The paragraph could not be created; it's possible you've exhausted your daily generation quota.",
                    "success" => false
                ];
            }
        }
}
    public function convertAstericsIntoBoldFont(string $s){
        $s = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $s);
        return $s;
    }


}


