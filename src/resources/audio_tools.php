<?php
$voices = [
    [
        "country_code" => "en-us",
        "country_name" => "US",
        "gender" => "male",
        "name" => "Mike",
        "flag" => "public/flags/us.png"
    ],
    [
        "country_code" => "en-us",
        "country_name" => "US",
        "gender" => "female",
        "name" => "Amy",
        "flag" => "public/flags/us.png"
    ],
    [
        "country_code" => "en-au",
        "country_name"  => "Australia",
        "gender" => "female",
        "name" => "Isla",
        "flag" => "public/flags/au.png"
    ],
    [
        "country_code" => "en-au",
        "country_name"  => "Australia",
        "gender" => "male",
        "name" => "Mason",
        "flag" => "public/flags/au.png"
    ],
    [
        "country_code" => "en-in",
        "country_name"  => "India",
        "gender" => "female",
        "name" => "Jai",
        "flag" => "public/flags/in.png"
    ],
    [
        "country_code" => "en-in",
        "country_name"  => "India",
        "gender" => "male",
        "name" => "Ajit",
        "flag" => "public/flags/in.png"
    ],
    [
        "country_code" => "en-ie",
        "country_name"  => "Irlanda",
        "gender" => "male",
        "name" => "Oran",
        "flag" => "public/flags/ei.png"
    ],
    [
        "country_code" => "en-gb",
        "country_name" => "Great Britain",
        "gender" => "female",
        "name" => "Nancy",
        "flag" => "public/flags/gb.png"
    ],
    [
        "country_code" => "en-gb",
        "country_name"  => "Great Britain",
        "gender" => "female",
        "name" => "Harry",
        "flag" => "public/flags/gb.png"
    ],
    [
        "country_code" => "en-ca",
        "country_name"  => "Canada",
        "gender" => "female",
        "name" => "Clara",
        "flag" => "public/flags/ca.png"
    ],
    [
        "country_code" => "en-ca",
        "country_name"  => "Canada",
        "gender" => "male",
        "name" => "Mason",
        "flag" => "public/flags/ca.png"
    ],
];

function audioFormatter($audioData){
    return 'data:audio/mpeg;base64,' . base64_encode($audioData);
}


function returnFlagFromCountryCode(string $countryCode){
    $countryCode = strtolower($countryCode);
    global $voices; 
    foreach($voices as $voice){
        if($voice['country_code'] === $countryCode){
            return $voice['flag'];
        }
    }

    return null;
}
