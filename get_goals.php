<?php
include "cors.php";
include "config/db.php";

$result = $conn->query("SELECT * FROM saving_goals ORDER BY id DESC");
$goals = [];

while ($row = $result->fetch_assoc()) {
    $goals[] = $row;
}

echo json_encode($goals);
$conn->close();
?>
