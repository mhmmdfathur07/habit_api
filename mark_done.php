<?php
include "config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? 0;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Habit ID required"]);
    exit;
}

// Ambil data habit
$result = $conn->query("SELECT last_done, streak FROM habits WHERE id=$id");
if ($result->num_rows == 0) {
    echo json_encode(["success" => false, "message" => "Habit not found"]);
    exit;
}

$habit = $result->fetch_assoc();
$last_done = $habit["last_done"];
$streak = $habit["streak"];

$today = date("Y-m-d");

if ($last_done == $today) {
    echo json_encode(["success" => false, "message" => "Already marked today"]);
    exit;
}

if (strtotime($today) - strtotime($last_done) == 86400) {
    $streak++;
} else {
    $streak = 1;
}

$query = $conn->prepare("UPDATE habits SET last_done=?, streak=? WHERE id=?");
$query->bind_param("sii", $today, $streak, $id);
$query->execute();

echo json_encode(["success" => true, "message" => "Habit marked as done", "streak" => $streak]);

$query->close();
$conn->close();
?>
