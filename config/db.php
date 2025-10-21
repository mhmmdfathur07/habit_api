<?php
// --- Izinkan semua origin (CORS) ---
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// --- Aktifkan error log (nonaktifkan di produksi) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- Ambil DATABASE_URL dari Railway ---
$databaseUrl = getenv("DATABASE_URL");

// --- Debug opsional (hapus nanti kalau sudah jalan) ---
// echo "DATABASE_URL: " . $databaseUrl;

// --- Gunakan fallback manual kalau DATABASE_URL tidak tersedia ---
if (!$databaseUrl || trim($databaseUrl) === "") {
    $server = getenv("MYSQLHOST") ?: "mysql.railway.internal";
    $username = getenv("MYSQLUSER") ?: "root";
    $password = getenv("MYSQLPASSWORD") ?: "";
    $database = getenv("MYSQLDATABASE") ?: "habit_db";
    $port = getenv("MYSQLPORT") ?: 3306;
} else {
    // --- Parse DATABASE_URL (format: mysql://user:pass@host:port/dbname)
    $url = parse_url($databaseUrl);

    $server = $url["host"] ?? "mysql.railway.internal";
    $username = $url["user"] ?? "root";
    $password = $url["pass"] ?? "";
    $database = isset($url["path"]) ? ltrim($url["path"], '/') : "habit_db";
    $port = $url["port"] ?? 3306;
}

// --- Buat koneksi ---
$conn = @new mysqli($server, $username, $password, $database, $port);

// --- Cek koneksi ---
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "❌ Gagal koneksi ke database",
        "error" => $conn->connect_error,
        "server" => $server,
        "database" => $database,
        "user" => $username,
        "port" => $port
    ]));
}

// --- Set UTF-8 ---
$conn->set_charset("utf8mb4");
?>
