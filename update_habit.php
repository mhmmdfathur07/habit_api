<?php
// 🔹 Header untuk akses dari Flutter
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// CORS preflight check
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include "config/db.php";

// 🔹 Ambil data dari JSON (Flutter bisa kirim pakai JSON atau form)
$input = json_decode(file_get_contents("php://input"), true);

if ($input) {
    $id = intval($input['id'] ?? 0);
    $name = trim($input['name'] ?? '');
    $target_type = trim($input['target_type'] ?? '');
    $target = isset($input['target']) ? intval($input['target']) : null;
} else {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = trim($_POST['name'] ?? '');
    $target_type = trim($_POST['target_type'] ?? '');
    $target = isset($_POST['target']) ? intval($_POST['target']) : null;
}

// 🔹 Validasi ID
if ($id <= 0) {
    echo json_encode(["success" => false, "error" => "ID tidak valid"]);
    exit;
}

// 🔹 Siapkan bagian query dinamis
$fields = [];
$params = [];
$types = "";

// 🔹 Tambahkan field yang dikirim
if ($name !== '') {
    $fields[] = "name = ?";
    $params[] = $name;
    $types .= "s";
}

if ($target_type !== '') {
    $fields[] = "target_type = ?";
    $params[] = $target_type;
    $types .= "s";
}

if (!is_null($target)) {
    $fields[] = "target = ?";
    $params[] = $target;
    $types .= "i";
}

// 🔹 Pastikan ada data yang diupdate
if (count($fields) === 0) {
    echo json_encode(["success" => false, "error" => "Tidak ada data yang dikirim untuk diupdate"]);
    exit;
}

// 🔹 Buat query UPDATE
$sql = "UPDATE habits SET " . implode(", ", $fields) . " WHERE id = ?";
$params[] = $id;
$types .= "i";

// 🔹 Eksekusi query
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Gagal mempersiapkan query: " . $conn->error]);
    exit;
}

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Habit berhasil diperbarui"]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
