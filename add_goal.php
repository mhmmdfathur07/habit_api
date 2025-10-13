<?php
include "config/db.php";

// 🔹 Izinkan akses dari semua origin (supaya Flutter bisa konek)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// 🔹 Jika request adalah OPTIONS (CORS preflight), hentikan di sini
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// 🔹 Ambil data dari POST
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$target_amount = isset($_POST['target_amount']) ? floatval($_POST['target_amount']) : 0;
$current_amount = isset($_POST['current_amount']) ? floatval($_POST['current_amount']) : 0;
$deadline = isset($_POST['deadline']) && $_POST['deadline'] !== '' ? $_POST['deadline'] : null;

// 🔹 Validasi data dasar
if ($title === '' || $target_amount <= 0) {
    echo json_encode(["success" => false, "error" => "Nama goal dan target harus diisi"]);
    exit;
}

// 🔹 Siapkan query
$stmt = $conn->prepare("
    INSERT INTO saving_goals (title, target_amount, current_amount, deadline)
    VALUES (?, ?, ?, ?)
");

// 🔹 Bind parameter (deadline bisa null)
$stmt->bind_param("sdds", $title, $target_amount, $current_amount, $deadline);

// 🔹 Eksekusi & kirim respon
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "id" => $conn->insert_id,
        "message" => "Goal berhasil ditambahkan"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error" => $stmt->error
    ]);
}

// 🔹 Tutup koneksi
$stmt->close();
$conn->close();
?>
