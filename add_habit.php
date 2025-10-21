<?php
include "cors.php";
include "config/db.php";

$data = json_decode(file_get_contents("php://input"), true);
$name = $data["name"] ?? "";
$target_type = $data["target_type"] ?? "daily";
$target = $data["target"] ?? 7;

if ($name == "") {
    echo json_encode(["success" => false, "message" => "Habit name is required"]);
    exit;
}

$query = $conn->prepare("INSERT INTO habits (name, last_done, streak, target_type, target) VALUES (?, CURDATE(), 0, ?, ?)");
$query->bind_param("ssi", $name, $target_type, $target);

if ($query->execute()) {
    echo json_encode(["success" => true, "message" => "Habit added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add habit"]);
}

$query->close();
$conn->close();
?>
