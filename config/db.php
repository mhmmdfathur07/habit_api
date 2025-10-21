<?php
// --- Izinkan semua origin (CORS) ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// --- Error log aktif (untuk debugging, nonaktifkan di produksi) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Ambil DATABASE_URL dari Railway (jika ada) ---
$databaseUrl = getenv("DATABASE_URL");

// 🔹 Jika tidak ada DATABASE_URL (misalnya di lokal), gunakan fallback manual
if (!$databaseUrl) {
    $server = getenv("MYSQLHOST") ?: "localhost";
    $username = getenv("MYSQLUSER") ?: "root";
    $password = getenv("MYSQLPASSWORD") ?: "";
    $database = getenv("MYSQLDATABASE") ?: "habit_db";
    $port = getenv("MYSQLPORT") ?: 3306;
} else {
    // 🔹 Parse DATABASE_URL (format: mysql://user:pass@host:port/dbname)
    $url = parse_url($databaseUrl);
    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $database = ltrim($url["path"], '/');
    $port = $url["port"];
}

// --- Buat koneksi ---
$conn = new mysqli($server, $username, $password, $database, $port);

// --- Cek koneksi ---
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "Gagal koneksi ke database",
        "error" => $conn->connect_error,
        "server" => $server,
        "database" => $database,
        "user" => $username,
        "port" => $port
    ]));
}

// --- Pastikan encoding UTF-8 ---
$conn->set_charset("utf8mb4");
?>
