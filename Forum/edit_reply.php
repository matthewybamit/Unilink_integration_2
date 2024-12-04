<?php
session_start();
require 'db_connect.php'; // Adjust to your database connection file

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit;
}

// Validate the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_id'], $_POST['content'])) {
    $replyId = intval($_POST['reply_id']);
    $newContent = trim($_POST['content']);
    $userId = $_SESSION['uid'];

    // Validate the input
    if (empty($newContent)) {
        echo json_encode(['success' => false, 'message' => 'Reply content cannot be empty.']);
        exit;
    }

    try {
        // Prepare the database query
        $stmt = $pdo->prepare("
            UPDATE replies 
            SET content = :content 
            WHERE id = :reply_id AND user_id = :user_id
        ");

        // Bind parameters and execute the query
        $stmt->bindParam(':content', $newContent, PDO::PARAM_STR);
        $stmt->bindParam(':reply_id', $replyId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Check if any row was updated
            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'Reply updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No changes made or unauthorized action.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>