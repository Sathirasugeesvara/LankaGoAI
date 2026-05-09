<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "lankago_ai";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
else {
    // echo "Database connection successful!";
}

?>