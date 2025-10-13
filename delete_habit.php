<?php
// 🌍 Izinkan akses dari Flutter Web
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

// ✅ Tangani preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include "config/db.php";

// ✅ Baca body JSON
$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? '';

if ($id === '' || !is_numeric($id)) {
    echo json_encode(["success" => false, "error" => "ID tidak valid"]);
    exit;
}

// 🔹 Jalankan query hapus
$stmt = $conn->prepare("DELETE FROM habits WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Habit berhasil dihapus"]);
} else {
 echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
