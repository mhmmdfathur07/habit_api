<?php
include "cors.php";
include "config/db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? 0;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Habit ID required"]);
    exit;
}

$query = $conn->prepare("DELETE FROM habits WHERE id=?");
$query->bind_param("i", $id);

if ($query->execute()) {
    echo json_encode(["success" => true, "message" => "Habit deleted"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete habit"]);
}

$query->close();
$conn->close();
?>
