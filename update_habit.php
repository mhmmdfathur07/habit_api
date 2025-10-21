<?php
include "cors.php";
include "config/db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? 0;
$name = $data["name"] ?? "";
$target_type = $data["target_type"] ?? "";
$target = $data["target"] ?? 7;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Habit ID is required"]);
    exit;
}

$query = $conn->prepare("UPDATE habits SET name=?, target_type=?, target=? WHERE id=?");
$query->bind_param("ssii", $name, $target_type, $target, $id);

if ($query->execute()) {
    echo json_encode(["success" => true, "message" => "Habit updated"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update habit"]);
}

$query->close();
$conn->close();
?>
