<?php
// --- Izinkan semua origin (CORS) ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// --- Tampilkan error (aktifkan hanya saat debugging) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Ambil variabel environment dari Railway ---
$server   = getenv("MYSQLHOST") ?: 'localhost';
$username = getenv("MYSQLUSER") ?: 'root';
$password = getenv("MYSQLPASSWORD") ?: '';
$database = getenv("MYSQLDATABASE") ?: 'habit_db';
$port     = getenv("MYSQLPORT") ?: 3306;

// --- Koneksi ke MySQL ---
$conn = new mysqli($server, $username, $password, $database, $port);

// --- Cek koneksi ---
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "Gagal koneksi ke database. Silakan cek konfigurasi Railway atau environment.",
        "error" => $conn->connect_error,
        "server" => $server,
        "port" => $port,
        "db" => $database
    ]));
}

// --- Pastikan koneksi UTF-8 ---
$conn->set_charset("utf8mb4");
?>
