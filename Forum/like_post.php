<?php
session_start();
require 'db_connect.php'; // Include your database configuration file

if (isset($_POST['post_id']) && isset($_SESSION['uid'])) {
    $postId = (int)$_POST['post_id']; // Ensure post_id is an integer
    $userId = $_SESSION['uid']; // Keep user_id as a string

    // Check if the user already liked this post
    $checkLike = $pdo->prepare('SELECT 1 FROM post_likes WHERE post_id = ? AND user_id = ?');
    $checkLike->execute([$postId, $userId]);
    $alreadyLiked = $checkLike->fetchColumn() !== false;

    if ($alreadyLiked) {
        // Unlike the post
        $removeLike = $pdo->prepare('DELETE FROM post_likes WHERE post_id = ? AND user_id = ?');
        $removeLike->execute([$postId, $userId]);

        // Decrement the like_count in the forum_posts table
        $updateLikeCount = $pdo->prepare('UPDATE forum_posts SET like_count = like_count - 1 WHERE id = ?');
        $updateLikeCount->execute([$postId]);

        
        echo json_encode(['status' => 'success', 'message' => 'Post unliked successfully', 'action' => 'unlike']);
    } else {
        // Like the post
        $insertLike = $pdo->prepare('INSERT INTO post_likes (post_id, user_id) VALUES (?, ?)');
        $insertLike->execute([$postId, $userId]);

        // Increment the like_count in the forum_posts table
        $updateLikeCount = $pdo->prepare('UPDATE forum_posts SET like_count = like_count + 1 WHERE id = ?');
        $updateLikeCount->execute([$postId]);

        echo json_encode(['status' => 'success', 'message' => 'Post liked successfully', 'action' => 'like']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
