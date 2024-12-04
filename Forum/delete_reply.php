<?php
session_start();
require_once 'db_connect.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Check if reply_id is provided
if (isset($_POST['reply_id'])) {
    $replyId = (int)$_POST['reply_id'];  // Ensure reply_id is treated as an integer
    $userId = $_SESSION['uid']; // User ID from session (it may be a string)
    
    // Debug: Log the received reply_id and user_id
    error_log("Attempting to delete reply with ID: " . $replyId . " for user: " . $userId);
    
    // Prepare the SQL query to delete the reply, considering user_id is varchar(255)
    $query = "DELETE FROM replies WHERE id = :reply_id AND user_id = :user_id";
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':reply_id', $replyId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_STR); // Ensure the user_id is treated as a string
        
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // If the reply was deleted successfully
            echo json_encode(['success' => true]);
        } else {
            // If no rows were affected (e.g., reply doesn't exist or doesn't belong to the user)
            echo json_encode(['success' => false, 'message' => 'Reply not found or you do not have permission to delete it']);
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Reply ID not provided']);
}
?>
