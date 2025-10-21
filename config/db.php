<?php
// --- Izinkan semua origin (CORS) ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// --- Debugging (aktifkan hanya di dev) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Ambil DATABASE_URL dari Railway ---
$databaseUrl = getenv("DATABASE_URL");

if ($databaseUrl) {
    // Format: mysql://user:pass@host:port/dbname
    $url = parse_url($databaseUrl);
    $server   = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $database = ltrim($url["path"], '/');
    $port     = isset($url["port"]) ? $url["port"] : 3306;
} else {
    // Fallback lokal
    $server = getenv("MYSQLHOST") ?: "localhost";
    $username = getenv("MYSQLUSER") ?: "root";
    $password = getenv("MYSQLPASSWORD") ?: "";
    $database = getenv("MYSQLDATABASE") ?: "habit_db";
    $port = getenv("MYSQLPORT") ?: 3306;
}

// --- Buat koneksi ---
$conn = new mysqli($server, $username, $password, $database, $port);

// --- Cek koneksi ---
if ($conn->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        "success" => false,
        "message" => "Gagal koneksi ke database",
        "error" => $conn->connect_error,
        "server" => $server,
        "database" => $database,
        "user" => $username,
        "port" => $port
    ]);
    exit;
}

$conn->set_charset("utf8mb4");
?>
