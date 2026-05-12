<?php

include __DIR__ . "/../includes/config.php";

function generateTripPlan(
    $budget,
    $days,
    $destinations,
    $style
){

    $prompt = "

Create a professional Sri Lanka travel plan.

Budget: $budget LKR
Days: $days
Destinations: $destinations
Travel Style: $style

Include:
- day by day itinerary
- hotels
- food recommendations
- transport guide
- weather tips
- estimated costs

";

    $data = [

        "contents" => [

            [
                "parts" => [

                    [
                        "text" => $prompt
                    ]
                ]
            ]
        ],

        "generationConfig" => [

            "temperature" => 0.8,
            "maxOutputTokens" => 2048
        ]
    ];

    $api_url =
    "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent?key="
    . GEMINI_API_KEY;

    $ch = curl_init();

    curl_setopt_array($ch,[

        CURLOPT_URL => $api_url,

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_POST => true,

        CURLOPT_POSTFIELDS =>
        json_encode($data),

        CURLOPT_HTTPHEADER => [

            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec($ch);

    curl_close($ch);

    $result = json_decode($response,true);

    if(isset($result['candidates'][0]['content']['parts'][0]['text'])){

        return
        $result['candidates'][0]['content']['parts'][0]['text'];

    }else{

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}