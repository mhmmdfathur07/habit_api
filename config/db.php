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

// --- Tambahan debug (sementara saja) ---
if (!$DATABASE_URL) {
    die("❌ DATABASE_URL tidak terbaca. Pastikan sudah diatur di Railway → Variables tab.");
}

$url = parse_url($DATABASE_URL);

// --- Parsing URL database Railway ---
$server   = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$database = ltrim($url["path"], '/');
$port     = isset($url["port"]) ? $url["port"] : 3306;

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
