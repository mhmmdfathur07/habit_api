<?php
// --- Izinkan semua origin (CORS) ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// --- Konfigurasi koneksi database untuk Railway ---
$DATABASE_URL = getenv("DATABASE_URL");

if ($DATABASE_URL) {
    $url = parse_url($DATABASE_URL);
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $database = substr($url["path"], 1);
} else {
    // fallback lokal (XAMPP)
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
