<?php
include "config/db.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$result = $conn->query("SELECT * FROM habits ORDER BY id DESC");
$habits = [];

while ($row = $result->fetch_assoc()) {
    $habits[] = $row;
}

echo json_encode($habits);
$conn->close();
?>
