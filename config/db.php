<?php
$DATABASE_URL = getenv("DATABASE_URL");

if ($DATABASE_URL) {
    // Untuk Railway
    $url = parse_url($DATABASE_URL);
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $database = substr($url["path"], 1);
    $conn = new mysqli($server, $username, $password, $database);
} else {
    // Untuk XAMPP lokal
    $conn = new mysqli("localhost", "root", "", "habit_db");
}

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]));
}
?>
