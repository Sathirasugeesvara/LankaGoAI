<?php

include __DIR__ . "/../includes/config.php";

function generateTripPlan(
    $budget,
    $days,
    $destinations,
    $style
){

    $prompt = "

You are a professional Sri Lanka travel planner.

Create a detailed and attractive Sri Lanka travel itinerary.

Trip Details:
- Budget: $budget LKR
- Duration: $days days
- Destinations: $destinations
- Travel Style: $style

Requirements:

1. Create a detailed day-by-day itinerary.
2. Explain activities for each day.
3. Recommend hotels for each destination.
4. Recommend famous Sri Lankan foods to try.
5. Include transport methods between locations.
6. Add weather and travel tips.
7. Mention estimated daily expenses.
8. Suggest best tourist attractions and hidden gems.
9. Use friendly and professional language.
10. Generate at least 20-30 lines of content.

Format the response nicely with headings and bullet points.
Do NOT give short summaries.

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

            "temperature" => 0.9,
            "maxOutputTokens" => 3000
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