<?php
include '../db_connect.php';
session_start();

if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$uid = $_SESSION['uid'];
$data = json_decode(file_get_contents("php://input"), true);

$taskName = $data['taskName'];
$description = $data['description'];
$duration = $data['duration'];
$startTime = date('Y-m-d H:i:s');

try {
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, name, description, duration, start_time, status) VALUES (?, ?, ?, ?, ?, 'active')");
    $success = $stmt->execute([$uid, $taskName, $description, $duration, $startTime]);

    echo json_encode(['success' => $success]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
