<?php

include "includes/auth.php";
include "includes/db.php";
include "includes/Parsedown.php";

$parsedown = new Parsedown();

$user_id = $_SESSION['user_id'];

$message = "";

/* DELETE SAVED TRIP */

if(isset($_GET['delete'])){

    $saved_id =
    intval($_GET['delete']);

    $deleteQuery = mysqli_prepare(

        $conn,

        "DELETE FROM saved_trips

        WHERE id=?
        AND user_id=?"
    );

    mysqli_stmt_bind_param(

        $deleteQuery,

        "ii",

        $saved_id,
        $user_id
    );

    if(mysqli_stmt_execute($deleteQuery)){

        $message = "
        <div class='alert alert-success'>
            Saved trip deleted successfully 🗑️
        </div>";

    }else{

        $message = "
        <div class='alert alert-error'>
            Failed to delete trip
        </div>";
    }
}

/* GET SAVED TRIPS */

$query = mysqli_prepare(

    $conn,

    "SELECT

    saved_trips.id AS saved_id,

    trips.*

    FROM saved_trips

    JOIN trips

    ON saved_trips.trip_id = trips.id

    WHERE saved_trips.user_id=?

    ORDER BY saved_trips.created_at DESC"
);

mysqli_stmt_bind_param(
    $query,
    "i",
    $user_id
);

mysqli_stmt_execute($query);

$result =
mysqli_stmt_get_result($query);

include "includes/header.php";
?>


<h1 style="margin-bottom:15px;">
    ❤️ Saved Trips
</h1>

<p style="
color:#94a3b8;
margin-bottom:40px;
">

    Your saved AI travel itineraries.

</p>

<?php echo $message; ?>

<div class="dashboard-grid">

<?php if(mysqli_num_rows($result) > 0): ?>

<?php while($trip = mysqli_fetch_assoc($result)): ?>

<div class="card">

    <h2 style="margin-bottom:15px;">

        <?php
        echo htmlspecialchars(
            $trip['title']
        );
        ?>

    </h2>

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
        🗓️
        <?php echo $trip['days']; ?>
        Days
    </p>

    <p style="margin-top:10px;">
        ✈️
        <?php
        echo htmlspecialchars(
            $trip['travel_style']
        );
        ?>
    </p>

    <br>

    <details>

        <summary
        style="
        cursor:pointer;
        color:#38bdf8;
        ">

            View AI Plan

        </summary>

        <div class="ai-response">

            <?php
            echo $parsedown->text(
                $trip['ai_response']
            );
            ?>

        </div>

    </details>

    <a

    href="saved-trips.php?delete=<?php
    echo $trip['saved_id'];
    ?>"

    class="btn"

    style="
    margin-top:20px;
    display:inline-block;
    background:#ef4444;
    color:white;
    "

    onclick="
    return confirm(
    'Delete this saved trip?'
    );
    ">

        Delete Trip

    </a>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="card">

    <h2>
        No Saved Trips Yet
    </h2>

    <p style="
    margin-top:15px;
    color:#94a3b8;
    ">

        Start planning and save
        your favorite trips ❤️

    </p>

</div>

<?php endif; ?>

</div>

<?php include "includes/footer.php"; ?>