<?php

include "includes/auth.php";
include "includes/db.php";
include "api/openai.php";
include "includes/Parsedown.php";

$parsedown = new Parsedown();

$user_id = $_SESSION['user_id'];

$message = "";
$ai_response = "";
$trip_id = 0;

/* SAVE TRIP */

if(isset($_POST['save_trip_plan'])){

    $trip_id = intval($_POST['trip_id']);

    $checkQuery = mysqli_prepare(

        $conn,

        "SELECT id
        FROM saved_trips
        WHERE user_id=?
        AND trip_id=?"
    );

    mysqli_stmt_bind_param(

        $checkQuery,

        "ii",

        $user_id,
        $trip_id
    );

    mysqli_stmt_execute($checkQuery);

    $checkResult =
    mysqli_stmt_get_result($checkQuery);

    if(mysqli_num_rows($checkResult) > 0){

        $message = "
        <div class='alert alert-error'>
            Trip already saved ❤️
        </div>";

    }else{

        $saveQuery = mysqli_prepare(

            $conn,

            "INSERT INTO saved_trips
            (user_id,trip_id)

            VALUES(?,?)"
        );

        mysqli_stmt_bind_param(

            $saveQuery,

            "ii",

            $user_id,
            $trip_id
        );

        if(mysqli_stmt_execute($saveQuery)){

            $message = "
            <div class='alert alert-success'>
                Trip saved successfully ❤️
            </div>";

        }else{

            $message = "
            <div class='alert alert-error'>
                Failed to save trip
            </div>";
        }
    }
}

/* GENERATE TRIP */

if(isset($_POST['generate_trip'])){

    $title =
    trim($_POST['title']);

    $budget =
    trim($_POST['budget']);

    $days =
    trim($_POST['days']);

    $destinations =
    trim($_POST['destinations']);

    $travel_style =
    trim($_POST['travel_style']);

    if(
        empty($title) ||
        empty($budget) ||
        empty($days) ||
        empty($destinations) ||
        empty($travel_style)
    ){

        $message = "
        <div class='alert alert-error'>
            Please fill all fields
        </div>";

    }else{

        /* AI RESPONSE */

        $ai_response =
        generateTripPlan(

            $budget,
            $days,
            $destinations,
            $travel_style
        );

        /* SAVE TO DATABASE */

        $query = mysqli_prepare(

            $conn,

            "INSERT INTO trips

            (
                user_id,
                title,
                budget,
                days,
                destinations,
                travel_style,
                ai_response
            )

            VALUES(?,?,?,?,?,?,?)"
        );

        mysqli_stmt_bind_param(

            $query,

            "isdisss",

            $user_id,
            $title,
            $budget,
            $days,
            $destinations,
            $travel_style,
            $ai_response
        );

        if(mysqli_stmt_execute($query)){

            $trip_id =
            mysqli_insert_id($conn);

            $message = "
            <div class='alert alert-success'>
                AI Trip Generated Successfully 🚀
            </div>";

        }else{

            $message = "
            <div class='alert alert-error'>
                Failed to generate trip
            </div>";
        }

        /* BUDGET ANALYTICS */

        $hotel_cost =
        $budget * 0.4;

        $food_cost =
        $budget * 0.2;

        $transport_cost =
        $budget * 0.15;

        $activities_cost =
        $budget * 0.15;

        $remaining =
        $budget -
        (
            $hotel_cost +
            $food_cost +
            $transport_cost +
            $activities_cost
        );
    }
}

include "includes/header.php";
?>


<h1 style="margin-bottom:15px;">
    🧳 AI Trip Planner
</h1>

<p style="
color:#94a3b8;
margin-bottom:40px;
">

    Create smart AI-powered
    Sri Lanka travel plans.

</p>

<?php echo $message; ?>

<!-- FORM -->

<div class="form-container">

    <form method="POST">

        <div class="input-group">

            <label>Trip Title</label>

            <input
            type="text"
            name="title"

            placeholder="Summer Vacation"

            required>

        </div>

        <div class="input-group">

            <label>Budget (LKR)</label>

            <input
            type="number"
            name="budget"

            placeholder="50000"

            required>

        </div>

        <div class="input-group">

            <label>Number of Days</label>

            <input
            type="number"
            name="days"

            placeholder="5"

            required>

        </div>

        <div class="input-group">

            <label>Destinations</label>

            <input
            type="text"
            name="destinations"

            placeholder="Ella, Kandy, Mirissa"

            required>

        </div>

        <div class="input-group">

            <label>Travel Style</label>

            <select
            name="travel_style"
            required>

                <option value="">
                    Select Style
                </option>

                <option value="Adventure">
                    Adventure
                </option>

                <option value="Luxury">
                    Luxury
                </option>

                <option value="Budget">
                    Budget
                </option>

                <option value="Family">
                    Family
                </option>

            </select>

        </div>

        <button
        type="submit"
        name="generate_trip"
        class="btn btn-primary"
        style="width:100%;">

            Generate AI Trip

        </button>

    </form>

</div>

<!-- AI RESPONSE -->

<?php if(!empty($ai_response)): ?>

<div class="card"
style="margin-top:40px;">

    <h2 style="margin-bottom:25px;">
        🤖 AI Travel Plan
    </h2>

    <div class="ai-response">

<?php
echo $parsedown->text(
    $ai_response
);
?>

</div>

</div>

<div class="card"
style="margin-top:30px;">

    <h2 style="margin-bottom:20px;">
        📍 Trip Destinations Map
    </h2>

    <div
    id="map"

    style="
    width:100%;
    height:450px;
    border-radius:20px;
    ">

    </div>

</div>

<!-- SAVE BUTTON -->

<form method="POST"
style="margin-top:20px;">

    <input
    type="hidden"
    name="trip_id"
    value="<?php echo $trip_id; ?>">

    <button
    type="submit"
    name="save_trip_plan"
    class="btn btn-primary">

        ❤️ Save This Trip

    </button>

</form>

<!-- BUDGET ANALYTICS -->

<div class="card"
style="margin-top:30px;">

    <h2 style="margin-bottom:30px;">
        📊 Budget Analytics
    </h2>

    <!-- HOTEL -->

    <div class="budget-item">

        <div class="budget-top">

            <span>🏨 Hotels</span>

            <span>
                LKR
                <?php
                echo number_format(
                    $hotel_cost
                );
                ?>
            </span>

        </div>

        <div class="progress-bar">

            <div
            class="progress-fill"
            style="width:40%;">

            </div>

        </div>

    </div>

    <!-- FOOD -->

    <div class="budget-item">

        <div class="budget-top">

            <span>🍛 Food</span>

            <span>
                LKR
                <?php
                echo number_format(
                    $food_cost
                );
                ?>
            </span>

        </div>

        <div class="progress-bar">

            <div
            class="progress-fill"
            style="width:20%;">

            </div>

        </div>

    </div>

    <!-- TRANSPORT -->

    <div class="budget-item">

        <div class="budget-top">

            <span>🚆 Transport</span>

            <span>
                LKR
                <?php
                echo number_format(
                    $transport_cost
                );
                ?>
            </span>

        </div>

        <div class="progress-bar">

            <div
            class="progress-fill"
            style="width:15%;">

            </div>

        </div>

    </div>

    <!-- ACTIVITIES -->

    <div class="budget-item">

        <div class="budget-top">

            <span>🎯 Activities</span>

            <span>
                LKR
                <?php
                echo number_format(
                    $activities_cost
                );
                ?>
            </span>

        </div>

        <div class="progress-bar">

            <div
            class="progress-fill"
            style="width:15%;">

            </div>

        </div>

    </div>

    <!-- REMAINING -->

    <div class="card"
    style="
    margin-top:30px;
    text-align:center;
    ">

        <h3>
            💰 Remaining Budget
        </h3>

        <h1 style="
        margin-top:15px;
        color:#22c55e;
        font-size:42px;
        ">

            LKR
            <?php
            echo number_format(
                $remaining
            );
            ?>

        </h1>

    </div>

</div>

<?php endif; ?>

<script>

function initMap(){

    const map = new google.maps.Map(

        document.getElementById("map"),

        {

            zoom:7,

            center:{
                lat:7.8731,
                lng:80.7718
            }
        }
    );

    /* DESTINATIONS */

    const locations = [

        {
            name:"Colombo",
            lat:6.9271,
            lng:79.8612
        },

        {
            name:"Kandy",
            lat:7.2906,
            lng:80.6337
        },

        {
            name:"Ella",
            lat:6.8667,
            lng:81.0466
        },

        {
            name:"Mirissa",
            lat:5.9483,
            lng:80.4716
        }
    ];

    locations.forEach(location=>{

        new google.maps.Marker({

            position:{
                lat:location.lat,
                lng:location.lng
            },

            map:map,

            title:location.name
        });
    });
}

</script>

<script

src="
https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap
"

async
defer>

</script>

<?php include "includes/footer.php"; ?>