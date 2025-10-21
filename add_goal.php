<?php
include "config/db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$title = $data["title"] ?? "";
$target_amount = $data["target_amount"] ?? 0;
$deadline = $data["deadline"] ?? null;

if ($title == "" || $target_amount <= 0) {
    echo json_encode(["success" => false, "message" => "Title and target_amount required"]);
    exit;
}

$query = $conn->prepare("INSERT INTO saving_goals (title, target_amount, current_amount, deadline) VALUES (?, ?, 0, ?)");
$query->bind_param("sds", $title, $target_amount, $deadline);

if ($query->execute()) {
    echo json_encode(["success" => true, "message" => "Goal added"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add goal"]);
}

$query->close();
$conn->close();
?>
