<?php

include "includes/auth.php";

include "includes/header.php";

?>

<h1 style="margin-bottom:15px;">
    Welcome,
    <?php echo $_SESSION['username']; ?> 🚀
</h1>

<p style="color:#94a3b8; margin-bottom:40px;">
    Start planning your AI powered journey across Sri Lanka.
</p>

<div class="dashboard-grid">

    <div class="dashboard-card">
        <h3>🤖 AI Planner</h3>

        <p>
            Generate smart itineraries using AI.
        </p>
    </div>

    <div class="dashboard-card">
        <h3>🌦️ Weather Alerts</h3>

        <p>
            Get real-time weather updates.
        </p>
    </div>

    <div class="dashboard-card">
        <h3>💰 Budget Optimizer</h3>

        <p>
            Optimize your travel expenses.
        </p>
    </div>

</div>

<?php include "includes/footer.php"; ?>