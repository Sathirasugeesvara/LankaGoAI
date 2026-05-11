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

    - Day-by-day itinerary
    - Hotel suggestions
    - Food recommendations
    - Transportation tips
    - Estimated expenses
    - Weather/travel advice

    ";

    $data = [

        "model" => "gpt-4.1-mini",

        "messages" => [

            [
                "role" => "user",
                "content" => $prompt
            ]
        ],

        "temperature" => 0.7
    ];

    $ch = curl_init();

    curl_setopt_array($ch,[

        CURLOPT_URL =>
        "https://api.openai.com/v1/chat/completions",

        CURLOPT_RETURNTRANSFER => true,

        CURLOPT_POST => true,

        CURLOPT_POSTFIELDS =>
        json_encode($data),

        CURLOPT_HTTPHEADER => [

            "Content-Type: application/json",

            "Authorization: Bearer "
            . OPENAI_API_KEY
        ]
    ]);

    $response = curl_exec($ch);

    curl_close($ch);

    $result = json_decode($response,true);

    if(isset($result['choices'][0]['message']['content'])){

    return $result['choices'][0]['message']['content'];

}else{

    return "ERROR: " .
    json_encode($result);
}
}