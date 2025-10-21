<?php
include "config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? 0;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Goal ID required"]);
    exit;
}

$query = $conn->prepare("DELETE FROM saving_goals WHERE id=?");
$query->bind_param("i", $id);

if ($query->execute()) {
    echo json_encode(["success" => true, "message" => "Goal deleted"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete goal"]);
}

$query->close();
$conn->close();
?>
