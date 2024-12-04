<?php
include '../db_connect.php';
session_start();

if (!isset($_SESSION['uid'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$uid = $_SESSION['uid'];

try {
    // Pag-update ng query upang ayusin ang mga tasks mula sa pinakabago
    $stmt = $pdo->prepare("SELECT id, name, description, duration FROM tasks WHERE status = 'active' AND user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$uid]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tasks);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
