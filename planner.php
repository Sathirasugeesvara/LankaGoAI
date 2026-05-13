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
        type="submit" id="generateBtn"
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
const locations = [

    {
        name:"Colombo",
        coords:[6.9271,79.8612]
    },

    {
        name:"Kandy",
        coords:[7.2906,80.6337]
    },

    {
        name:"Ella",
        coords:[6.8667,81.0466]
    },

    {
        name:"Mirissa",
        coords:[5.9483,80.4716]
    },

    {
        name:"Sigiriya",
        coords:[7.9570,80.7603]
    },

    {
        name:"Nuwara Eliya",
        coords:[6.9497,80.7891]
    },

    {
        name:"Galle",
        coords:[6.0329,80.2168]
    },

    {
        name:"Bentota",
        coords:[6.4254,79.9959]
    },

    {
        name:"Arugam Bay",
        coords:[6.8404,81.8368]
    },

    {
        name:"Yala National Park",
        coords:[6.3725,81.5185]
    },

    {
        name:"Trincomalee",
        coords:[8.5874,81.2152]
    },

    {
        name:"Jaffna",
        coords:[9.6615,80.0255]
    },

    {
        name:"Anuradhapura",
        coords:[8.3114,80.4037]
    },

    {
        name:"Polonnaruwa",
        coords:[7.9403,81.0188]
    },

    {
        name:"Haputale",
        coords:[6.7650,80.9510]
    },

    {
        name:"Badulla",
        coords:[6.9934,81.0550]
    },

    {
        name:"Dambulla",
        coords:[7.8731,80.6511]
    },

    {
        name:"Pasikuda",
        coords:[7.9290,81.5610]
    },

    {
        name:"Hikkaduwa",
        coords:[6.1408,80.1010]
    },

    {
        name:"Unawatuna",
        coords:[6.0108,80.2499]
    }

];
</script>

<script>

const plannerForm =
document.querySelector("form");

const generateBtn =
document.getElementById("generateBtn");

plannerForm.addEventListener(
"submit",
function(){

    generateBtn.classList.add(
        "loading"
    );
});

</script>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>

const map = L.map('map').setView(
    [7.8731,80.7718],
    7
);

L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
        attribution:
        '&copy; OpenStreetMap contributors'
    }
).addTo(map);

locations.forEach(location => {

    L.marker(location.coords)
    .addTo(map)
    .bindPopup(
        `<b>${location.name}</b>`
    );

});

</script>

<?php include "includes/footer.php"; ?>