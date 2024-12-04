<?php
session_start();
require_once 'db_connect.php'; // Database configuration

// Ensure the user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Check if comment ID and content are provided
if (isset($_POST['comment_id'], $_POST['content'])) {
    $commentId = (int)$_POST['comment_id'];
    $newContent = trim($_POST['content']);
    $userId = $_SESSION['uid'];

    // Validate content length
    if (empty($newContent)) {
        echo json_encode(['success' => false, 'message' => 'Comment content cannot be empty']);
        exit;
    }

    // Update the comment in the database
    $query = "UPDATE comments SET content = :content WHERE id = :comment_id AND user_id = :user_id";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':content', $newContent, PDO::PARAM_STR);
        $stmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Comment not found or permission denied']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
