<?php
$DATABASE_URL = getenv("DATABASE_URL");

if ($DATABASE_URL) {
    $url = parse_url($DATABASE_URL);
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $database = substr($url["path"], 1);
} else {
    // fallback lokal (jika dijalankan di XAMPP)
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "habit_db";
}

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]));
}
?>
