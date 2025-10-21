<?php
require_once "cors.php";
require_once "config/db.php";

try {
    $result = $conn->query("SELECT * FROM saving_goals ORDER BY id DESC");

    if (!$result) {
        throw new Exception("Query gagal: " . $conn->error);
    }

    $goals = [];
    while ($row = $result->fetch_assoc()) {
        $goals[] = $row;
    }

    echo json_encode($goals);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
?>
