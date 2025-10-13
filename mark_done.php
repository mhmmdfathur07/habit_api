<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include "config/db.php";

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? '';

if ($id === '' || !is_numeric($id)) {
    echo json_encode(["success" => false, "error" => "ID tidak valid"]);
    exit;
}

// 🔹 Ambil data habit
$query = $conn->prepare("SELECT streak, target, target_type, last_done FROM habits WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$habit = $result->fetch_assoc();

if (!$habit) {
    echo json_encode(["success" => false, "error" => "Habit tidak ditemukan"]);
    exit;
}

$streak = intval($habit['streak']);
$target = intval($habit['target']);
$targetType = $habit['target_type'];
$lastDone = $habit['last_done'];

// 🔹 Waktu sekarang
$now = new DateTime("now", new DateTimeZone("Asia/Jakarta"));

// 🔹 Cek apakah sudah mark done hari/minggu ini
if ($lastDone) {
    $lastDoneDate = new DateTime($lastDone);

    if ($targetType === 'daily') {
        // Cek apakah hari yang sama
        if ($lastDoneDate->format('Y-m-d') === $now->format('Y-m-d')) {
            echo json_encode(["success" => false, "error" => "Sudah dilakukan hari ini"]);
            exit;
        }
    } elseif ($targetType === 'weekly') {
        // Cek apakah masih dalam minggu yang sama
        if ($lastDoneDate->format('oW') === $now->format('oW')) {
            echo json_encode(["success" => false, "error" => "Sudah dilakukan minggu ini"]);
            exit;
        }
    }
}

// 🔹 Batasi streak agar tidak melebihi target
if ($streak >= $target) {
    echo json_encode(["success" => false, "error" => "Streak sudah mencapai target"]);
    exit;
}

// 🔹 Tambah streak dan update waktu terakhir
$newStreak = $streak + 1;
$update = $conn->prepare("UPDATE habits SET streak = ?, last_done = ? WHERE id = ?");
$timestamp = $now->format('Y-m-d H:i:s');
$update->bind_param("isi", $newStreak, $timestamp, $id);

if ($update->execute()) {
    echo json_encode(["success" => true, "streak" => $newStreak, "last_done" => $timestamp]);
} else {
    echo json_encode(["success" => false, "error" => $update->error]);
}

$query->close();
$update->close();
$conn->close();
?>
