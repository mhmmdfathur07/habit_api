<?php
include "config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$result = $conn->query("SELECT * FROM saving_goals ORDER BY id DESC");
$goals = [];

while ($row = $result->fetch_assoc()) {
    $goals[] = $row;
}

echo json_encode($goals);
$conn->close();
?>
