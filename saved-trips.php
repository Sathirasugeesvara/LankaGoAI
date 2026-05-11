<?php

include "includes/auth.php";
include "includes/db.php";

$user_id = $_SESSION['user_id'];

$query = mysqli_prepare(
    $conn,

    "SELECT * FROM trips
     WHERE user_id=?
     ORDER BY created_at DESC"
);

mysqli_stmt_bind_param($query,"i",$user_id);

mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

include "includes/header.php";
?>

<h1 style="margin-bottom:15px;">
    ❤️ Saved Trips
</h1>

<p style="color:#94a3b8; margin-bottom:40px;">
    View and manage your travel plans.
</p>

<div class="trip-grid">

<?php if(mysqli_num_rows($result) > 0): ?>

    <?php while($trip = mysqli_fetch_assoc($result)): ?>

        <div class="trip-card">

            <img
            src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e"
            alt="Trip">

            <div class="trip-content">

                <h3>
                    <?php echo htmlspecialchars($trip['title']); ?>
                </h3>

                <p>
                    📍 <?php echo htmlspecialchars($trip['destinations']); ?>
                </p>

                <p>
                    💰 Budget:
                    LKR <?php echo number_format($trip['budget']); ?>
                </p>

                <p>
                    🗓️ Days:
                    <?php echo $trip['days']; ?>
                </p>

                <p>
                    ✈️ Style:
                    <?php echo $trip['travel_style']; ?>
                </p>

                <a href="#"
                class="btn btn-primary">

                    View Plan

                </a>

            </div>

        </div>

    <?php endwhile; ?>

<?php else: ?>

    <p>No saved trips found.</p>

<?php endif; ?>

</div>

<?php include "includes/footer.php"; ?>