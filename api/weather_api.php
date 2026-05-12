<?php

include __DIR__ . "/../includes/config.php";

function getWeather($city){

    $url =
    "https://api.openweathermap.org/data/2.5/weather?q="
    . urlencode($city)
    . "&appid="
    . WEATHER_API_KEY
    . "&units=metric";

    $response =
    file_get_contents($url);

    return json_decode($response,true);
}