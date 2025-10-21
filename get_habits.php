<?php
include "cors.php";
include "config/db.php";

$result = $conn->query("SELECT * FROM habits ORDER BY id DESC");
$habits = [];

while ($row = $result->fetch_assoc()) {
    $habits[] = $row;
}

echo json_encode($habits);
$conn->close();
?>
