<?php

include __DIR__ . "/../includes/config.php";

header("Content-Type: application/json");

$message = trim($_POST['message'] ?? '');

if(empty($message)){

    echo json_encode([
        "reply" => "Please type a message."
    ]);

    exit;
}

$data = [

    "contents" => [

        [
            "parts" => [

                [
                    "text" =>

"You are LankaGo AI, a Sri Lanka travel assistant.

Help users with:
- tourist places
- hotels
- transport
- food
- travel plans
- weather
- budgets

User message:
" . $message
                ]
            ]
        ]
    ],

    "generationConfig" => [

        "temperature" => 0.7,
        "maxOutputTokens" => 2048
    ]
];

$api_url =
"https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key="
. GEMINI_API_KEY;

$ch = curl_init();

curl_setopt_array($ch,[

    CURLOPT_URL => $api_url,

    CURLOPT_RETURNTRANSFER => true,

    CURLOPT_POST => true,

    CURLOPT_POSTFIELDS => json_encode($data),

    CURLOPT_HTTPHEADER => [

        "Content-Type: application/json"
    ]
]);

$response = curl_exec($ch);

$error = curl_error($ch);

curl_close($ch);

if($error){

    echo json_encode([
        "reply" => $error
    ]);

    exit;
}

$result = json_decode($response,true);

if(isset($result['candidates'][0]['content']['parts'][0]['text'])){

    $reply =
    $result['candidates'][0]['content']['parts'][0]['text'];

    echo json_encode([
        "reply" => $reply
    ]);

}else{

    echo json_encode([

        "reply" =>
        json_encode($result, JSON_PRETTY_PRINT)
    ]);
}