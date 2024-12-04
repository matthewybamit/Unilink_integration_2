<?php
session_start();

if (!isset($_SESSION['uid'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Get user_id from session
$userId = $_SESSION['uid'];

$searchTerm = isset($_GET['query']) ? $_GET['query'] : '';

include '../db_connect.php';

try {
    $stmt = $pdo->prepare("SELECT id, name, description, duration FROM tasks WHERE user_id = ? AND status = 'active' AND name LIKE :searchTerm");
    $stmt->execute([$userId, '%' . $searchTerm . '%']);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tasks);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
