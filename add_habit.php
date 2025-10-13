<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include "config/db.php";

$input = json_decode(file_get_contents("php://input"), true);
if ($input) {
    $name = $input['name'] ?? '';
    $target_type = strtolower($input['target_type'] ?? 'daily');
    $target = isset($input['target']) ? intval($input['target']) : 7;
} else {
    $name = $_POST['name'] ?? '';
    $target_type = strtolower($_POST['target_type'] ?? 'daily');
    $target = isset($_POST['target']) ? intval($_POST['target']) : 7;
}

// normalisasi nilai target_type lama
if ($target_type === 'harian') $target_type = 'daily';
if ($target_type === 'mingguan') $target_type = 'weekly';

if (trim($name) === '' || trim($target_type) === '') {
    echo json_encode(["success" => false, "error" => "Data tidak lengkap"]);
    exit;
}

if ($target < 1) $target = ($target_type === 'weekly' ? 4 : 7);

$stmt = $conn->prepare("INSERT INTO habits (name, target_type, target) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $name, $target_type, $target);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "id" => $conn->insert_id,
        "message" => "Habit berhasil ditambahkan"
    ]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
