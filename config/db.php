<?php
// Coba ambil environment variables dari Railway
$server   = getenv("MYSQLHOST");
$username = getenv("MYSQLUSER");
$password = getenv("MYSQLPASSWORD");
$database = getenv("MYSQLDATABASE");
$port     = getenv("MYSQLPORT");

// Jika tidak ada (berarti dijalankan di lokal/XAMPP)
if (!$server || !$username || !$database) {
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "habit_db";
    $port = 3306;
}

// Buat koneksi
$conn = new mysqli($server, $username, $password, $database, $port);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(["error" => "Koneksi gagal: " . $conn->connect_error]));
}
?>
