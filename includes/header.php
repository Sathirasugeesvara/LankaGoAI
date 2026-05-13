<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🤖LankaGoAI</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

    <!-- Icons -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<div id="loader">
    <div class="spinner"></div>
</div>

<header class="navbar">

    <div class="logo">
        <a href="index.php">
            🤖LankaGoAI
        </a>
    </div>

    <nav>
        <ul class="nav-links">

            <li><a href="index.php">Home</a></li>

            <?php if(isset($_SESSION['user_id'])): ?>

                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="planner.php">Planner</a></li>
                <li><a href="chatbot.php">AI Chat</a></li>
                <li><a href="weather.php">Weather</a></li>
                <li><a href="saved-trips.php">Saved Trips</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>

            <?php else: ?>

                <li><a href="login.php">Login</a></li>
                <li><a href="register.php" class="register-btn">Register</a></li>

            <?php endif; ?>

        </ul>
    </nav>

</header>

<div class="main-content">


