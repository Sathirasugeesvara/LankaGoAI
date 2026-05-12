<?php

include "includes/auth.php";
include "includes/header.php";

include "api/weather_api.php";

$weather = null;
$city = "";

if(isset($_POST['check_weather'])){

    $city = trim($_POST['city']);

    if(!empty($city)){

        $weather = getWeather($city);
    }
}
?>

<h1 style="margin-bottom:15px;">
    🌦️ Weather Forecast
</h1>

<p style="
color:#94a3b8;
margin-bottom:40px;
">

    Check real-time weather conditions
    for Sri Lankan destinations.

</p>

<!-- WEATHER SEARCH -->

<div class="form-container">

    <form method="POST">

        <div class="input-group">

            <label>Enter City</label>

            <input
            type="text"
            name="city"

            placeholder="Colombo, Kandy, Ella..."

            value="<?php echo htmlspecialchars($city); ?>"

            required>

        </div>

        <button
        type="submit"
        name="check_weather"
        class="btn btn-primary"
        style="width:100%;">

            Check Weather

        </button>

    </form>

</div>

<?php if($weather && isset($weather['main'])): ?>

<div class="dashboard-grid"
style="margin-top:40px;">

    <!-- MAIN WEATHER CARD -->

    <div class="card"
    style="text-align:center;">

        <img

        src="https://openweathermap.org/img/wn/<?php
        echo $weather['weather'][0]['icon'];
        ?>@2x.png"

        alt="Weather Icon"

        style="
        width:100px;
        margin-bottom:15px;
        ">

        <h2 style="font-size:42px;">

            <?php
            echo round(
                $weather['main']['temp']
            );
            ?>°C

        </h2>

        <p style="
        font-size:20px;
        color:#38bdf8;
        margin-top:10px;
        ">

            <?php
            echo htmlspecialchars(
                $weather['weather'][0]['main']
            );
            ?>

        </p>

        <br>

        <h3>

            📍
            <?php
            echo htmlspecialchars(
                $weather['name']
            );
            ?>

        </h3>

    </div>

    <!-- DETAILS CARD -->

    <div class="card">

        <h2 style="margin-bottom:25px;">
            Weather Details
        </h2>

        <div style="
        display:flex;
        flex-direction:column;
        gap:20px;
        ">

            <div>

                <strong>🌡️ Feels Like:</strong>

                <?php
                echo round(
                    $weather['main']['feels_like']
                );
                ?>°C

            </div>

            <div>

                <strong>💧 Humidity:</strong>

                <?php
                echo $weather['main']['humidity'];
                ?>%

            </div>

            <div>

                <strong>🌬️ Wind Speed:</strong>

                <?php
                echo $weather['wind']['speed'];
                ?> m/s

            </div>

            <div>

                <strong>☁️ Condition:</strong>

                <?php
                echo htmlspecialchars(
                    $weather['weather'][0]['description']
                );
                ?>

            </div>

            <div>

                <strong>🌅 Sunrise:</strong>

                <?php
                echo date(
                    "h:i A",
                    $weather['sys']['sunrise']
                );
                ?>

            </div>

            <div>

                <strong>🌇 Sunset:</strong>

                <?php
                echo date(
                    "h:i A",
                    $weather['sys']['sunset']
                );
                ?>

            </div>

        </div>

    </div>

</div>

<?php elseif($weather): ?>

<div class="alert alert-error"
style="margin-top:30px;">

    City not found or API error.

</div>

<?php endif; ?>

<?php include "includes/footer.php"; ?>