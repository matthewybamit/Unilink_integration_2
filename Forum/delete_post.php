<?php
session_start();
include('db_connect.php'); // Include your database connection file

// Ensure that the user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

// Get the JSON input from the fetch request
$postData = json_decode(file_get_contents("php://input"), true);
$postId = isset($postData['post_id']) ? (int)$postData['post_id'] : null;
$action = isset($postData['action']) ? $postData['action'] : null;

// Validate the action and post ID
if ($action === 'delete' && $postId) {
    try {
        // Check if the post exists and belongs to the current user
        $stmt = $pdo->prepare("SELECT * FROM forum_posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$postId, $_SESSION['uid']]);
        $post = $stmt->fetch();

        if ($post) {
            // Delete the post
            $stmt = $pdo->prepare("DELETE FROM forum_posts WHERE id = ?");
            $stmt->execute([$postId]);

            // Check if the deletion was successful
            if ($stmt->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Post deleted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete post.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Post not found or permission denied.']);
        }
    } catch (PDOException $e) {
        error_log("Error deleting post: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error deleting post: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
