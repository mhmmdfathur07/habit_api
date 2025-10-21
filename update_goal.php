<?php
include "config/db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? 0;
$current_amount = $data["current_amount"] ?? 0;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Goal ID required"]);
    exit;
}

$query = $conn->prepare("UPDATE saving_goals SET current_amount=? WHERE id=?");
$query->bind_param("di", $current_amount, $id);

if ($query->execute()) {
    echo json_encode(["success" => true, "message" => "Goal updated"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update goal"]);
}

$query->close();
$conn->close();
?>
