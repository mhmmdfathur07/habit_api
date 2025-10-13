<?php
include "config/db.php";

// 🔹 Izinkan akses dari Flutter
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// 🔹 Cegah error preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// 🔹 Tangkap data dari JSON body atau form
$input = json_decode(file_get_contents("php://input"), true);

if ($input) {
    $id = $input['id'] ?? 0;
    $current_amount = $input['current_amount'] ?? 0;
} else {
    $id = $_POST['id'] ?? 0;
    $current_amount = $_POST['current_amount'] ?? 0;
}

// 🔹 Validasi sederhana
if (empty($id)) {
    echo json_encode(["success" => false, "error" => "ID tidak valid"]);
    exit;
}

// 🔹 Query update
$stmt = $conn->prepare("UPDATE saving_goals SET current_amount = ? WHERE id = ?");
$stmt->bind_param("di", $current_amount, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Goal berhasil diperbarui"]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
