<?php
include "config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$id = $_POST['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM saving_goals WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
