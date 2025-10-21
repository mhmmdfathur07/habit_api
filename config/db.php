<?php
// --- Izinkan semua origin (CORS) ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// --- Tampilkan error hanya saat debugging (bisa matikan di produksi) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Konfigurasi koneksi database ---
$DATABASE_URL = getenv("DATABASE_URL");

if ($DATABASE_URL) {
    // Parsing DATABASE_URL dari Railway (contoh: mysql://user:pass@host:port/dbname)
    $url = parse_url($DATABASE_URL);

    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $database = ltrim($url["path"], '/'); // hapus "/" di awal
    $port = isset($url["port"]) ? $url["port"] : 3306; // tambahkan port jika ada
} else {
    // fallback lokal (jika dijalankan di XAMPP)
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "habit_db";
    $port = 3306;
}

// --- Membuat koneksi ---
$conn = new mysqli($server, $username, $password, $database, $port);

// --- Cek koneksi ---
if ($conn->connect_error) {
    die(json_encode([
        "error" => "Koneksi gagal ke database: " . $conn->connect_error,
        "server" => $server,
        "user" => $username,
        "db" => $database,
        "port" => $port
    ]));
}
?>
