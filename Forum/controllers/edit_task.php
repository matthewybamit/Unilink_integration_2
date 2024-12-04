<?php
include '../db_connect.php';
session_start();

if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$uid = $_SESSION['uid'];
$data = json_decode(file_get_contents("php://input"), true);

$taskId = $data['taskId'];
$taskName = $data['taskName'];
$description = $data['description'];
$duration = $data['duration'];

try {
    $stmt = $pdo->prepare("UPDATE tasks SET name = ?, description = ?, duration = ? WHERE id = ? AND user_id = ?");
    $success = $stmt->execute([$taskName, $description, $duration, $taskId, $uid]);

    echo json_encode(['success' => $success]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
