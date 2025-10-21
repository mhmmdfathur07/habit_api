<?php
// --- Izinkan semua origin (CORS) ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// --- Tampilkan error (aktifkan hanya saat debugging) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Ambil URL database dari Railway ---
$DATABASE_URL = getenv("DATABASE_URL");

if ($DATABASE_URL) {
    // Parsing URL database Railway: mysql://user:pass@host:port/dbname
    $url = parse_url($DATABASE_URL);

    $server   = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $database = ltrim($url["path"], '/');
    $port     = isset($url["port"]) ? $url["port"] : 3306;
} else {
    // fallback untuk lokal (XAMPP)
    $server   = "localhost";
    $username = "root";
    $password = "";
    $database = "habit_db";
    $port     = 3306;
}

// --- Koneksi ke MySQL ---
$conn = new mysqli($server, $username, $password, $database, $port);

// --- Cek koneksi ---
if ($conn->connect_error) {
    // Jangan tampilkan kredensial sensitif ke publik
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "Gagal koneksi ke database. Silakan cek konfigurasi Railway atau environment.",
        "error" => $conn->connect_error
    ]));
}

// --- Pastikan koneksi UTF-8 (supaya tidak error saat simpan teks) ---
$conn->set_charset("utf8mb4");
?>
