<?php
// Include your database connection
require_once 'db_connect.php';

// Check if the user is logged in and required fields are present
session_start();
if (isset($_SESSION['uid']) && isset($_POST['post_id']) && isset($_POST['reason'])) {
    $postId = (int)$_POST['post_id'];
    $reason = htmlspecialchars($_POST['reason']);
    $comments = htmlspecialchars($_POST['comments']);
    $userId = $_SESSION['uid'];  // Directly use the session UID for the user_id

    // Step 1: Check if the user exists in the users table (optional, as you already have the session UID)
    $stmt = $pdo->prepare("SELECT uid FROM users WHERE uid = ?");
    $stmt->execute([$userId]);
    $userExists = $stmt->fetchColumn();

    if (!$userExists) {
        // If user doesn't exist, return an error message
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit;  // Stop further execution
    }

    // Step 2: Insert the report into the post_reports table
    $stmt = $pdo->prepare("INSERT INTO post_reports (post_id, user_id, reason, comments, reported_at) VALUES (?, ?, ?, ?, NOW())");
    $result = $stmt->execute([$postId, $userId, $reason, $comments]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Post has been reported successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to report the post.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>
