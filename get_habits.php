<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Pastikan file config dan cors diakses dengan benar
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/config/db.php';

// Pastikan koneksi berhasil
if (!$conn) {
    echo json_encode(["error" => "Koneksi ke database gagal."]);
    exit;
}

try {
    $query = "SELECT * FROM habits ORDER BY id DESC";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Query gagal: " . $conn->error);
    }

    $habits = [];
    while ($row = $result->fetch_assoc()) {
        $habits[] = $row;
    }

    echo json_encode($habits);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();
?>
