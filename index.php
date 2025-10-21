<?php
include "cors.php";
include "config/db.php";

echo json_encode([
    "status" => "success",
    "message" => "Habit Tracker API connected 🚀"
]);
?>
