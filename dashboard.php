<?php

include "includes/auth.php";
include "includes/db.php";

$user_id = $_SESSION['user_id'];

/* TOTAL TRIPS */

$totalTripsQuery = mysqli_prepare(

    $conn,

    "SELECT COUNT(*) AS total
    FROM trips
    WHERE user_id=?"
);

mysqli_stmt_bind_param(
    $totalTripsQuery,
    "i",
    $user_id
);

mysqli_stmt_execute($totalTripsQuery);

$totalTripsResult =
mysqli_stmt_get_result($totalTripsQuery);

$totalTrips =
mysqli_fetch_assoc($totalTripsResult)['total'];

/* SAVED TRIPS */

$savedTripsQuery = mysqli_prepare(

    $conn,

    "SELECT COUNT(*) AS total
    FROM saved_trips
    WHERE user_id=?"
);

mysqli_stmt_bind_param(
    $savedTripsQuery,
    "i",
    $user_id
);

mysqli_stmt_execute($savedTripsQuery);

$savedTripsResult =
mysqli_stmt_get_result($savedTripsQuery);

$savedTrips =
mysqli_fetch_assoc($savedTripsResult)['total'];

/* RECENT TRIPS */

$recentTripsQuery = mysqli_prepare(

    $conn,

    "SELECT *
    FROM trips
    WHERE user_id=?
    ORDER BY created_at DESC
    LIMIT 3"
);

mysqli_stmt_bind_param(
    $recentTripsQuery,
    "i",
    $user_id
);

mysqli_stmt_execute($recentTripsQuery);

$recentTrips =
mysqli_stmt_get_result($recentTripsQuery);

include "includes/header.php";
?>

<h1 style="margin-bottom:15px;">
    👋 Welcome,
    <?php echo $_SESSION['username']; ?>
</h1>

<p style="
color:#94a3b8;
margin-bottom:40px;
">

    Manage your trips, AI plans,
    and travel activities.

</p>

<!-- STATS -->

<div class="dashboard-grid">

    <div class="card">

        <h3>🌍 Total Trips</h3>

        <h1 style="
        margin-top:15px;
        font-size:42px;
        color:#38bdf8;
        ">

            <?php echo $totalTrips; ?>

        </h1>

    </div>

    <div class="card">

        <h3>❤️ Saved Trips</h3>

        <h1 style="
        margin-top:15px;
        font-size:42px;
        color:#38bdf8;
        ">

            <?php echo $savedTrips; ?>

        </h1>

    </div>

    <div class="card">

        <h3>🤖 AI Status</h3>

        <h1 style="
        margin-top:15px;
        font-size:22px;
        color:#22c55e;
        ">

            Active

        </h1>

    </div>

</div>

<!-- QUICK ACTIONS -->

<h2 style="
margin-top:50px;
margin-bottom:25px;
">

    ⚡ Quick Actions

</h2>

<div class="dashboard-grid">

    <a href="planner.php"
    class="card"
    style="text-decoration:none;">

        <h3>🧳 Plan New Trip</h3>

        <p style="margin-top:10px;">
            Generate smart travel itineraries.
        </p>

    </a>

    <a href="chatbot.php"
    class="card"
    style="text-decoration:none;">

        <h3>🤖 AI Assistant</h3>

        <p style="margin-top:10px;">
            Ask travel-related questions.
        </p>

    </a>

    <a href="weather.php"
    class="card"
    style="text-decoration:none;">

        <h3>🌦️ Weather</h3>

        <p style="margin-top:10px;">
            Check destination weather.
        </p>

    </a>

    <a href="saved-trips.php"
    class="card"
    style="text-decoration:none;">

        <h3>❤️ Saved Trips</h3>

        <p style="margin-top:10px;">
            Access saved itineraries.
        </p>

    </a>

</div>

<!-- RECENT TRIPS -->

<h2 style="
margin-top:50px;
margin-bottom:25px;
">

    🕒 Recent Trips

</h2>

<div class="dashboard-grid">

<?php while($trip = mysqli_fetch_assoc($recentTrips)): ?>

<div class="card">

    <h3 style="margin-bottom:15px;">

        <?php
        echo htmlspecialchars(
            $trip['title']
        );
        ?>

    </h3>

    <p>
        📍
        <?php
        echo htmlspecialchars(
            $trip['destinations']
        );
        ?>
    </p>

    <p style="margin-top:10px;">
        💰 Budget:
        LKR
        <?php echo $trip['budget']; ?>
    </p>

    <p style="margin-top:10px;">
        🗓️ <?php echo $trip['days']; ?> Days
    </p>

</div>

<?php endwhile; ?>

</div>

<?php include "includes/footer.php"; ?>