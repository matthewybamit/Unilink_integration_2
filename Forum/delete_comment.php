<?php
session_start();
require_once 'db_connect.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Check if comment_id is provided
if (isset($_POST['comment_id'])) {
    $commentId = (int)$_POST['comment_id'];  // Ensure comment_id is treated as an integer
    $userId = $_SESSION['uid']; // User ID from session (it may be a string)

    // Debug: Log the received comment_id and user_id
    error_log("Attempting to delete comment with ID: " . $commentId . " for user: " . $userId);
    
    // Prepare the SQL query to delete the comment, considering user_id is varchar(255)
    $query = "DELETE FROM comments WHERE id = :comment_id AND user_id = :user_id";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':comment_id', $commentId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR); // Ensure the user_id is treated as a string
        
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // If the comment was deleted successfully
            echo json_encode(['success' => true]);
        } else {
            // If no rows were affected (e.g., comment doesn't exist or doesn't belong to the user)
            echo json_encode(['success' => false, 'message' => 'Comment not found or you do not have permission to delete it']);
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Comment ID not provided']);
}
?>
