<?php
session_start();
require 'db_connect.php'; // Include your database configuration file

if (isset($_POST['comment_id']) && isset($_SESSION['uid'])) {
    $commentId = (int)$_POST['comment_id']; // Ensure comment_id is an integer
    $userId = $_SESSION['uid']; // Keep user_id as a string

    // Check if the user already liked this comment
    $checkLike = $pdo->prepare('SELECT 1 FROM comment_likes WHERE comment_id = ? AND user_id = ?');
    $checkLike->execute([$commentId, $userId]);
    $alreadyLiked = $checkLike->fetchColumn() !== false;

    if ($alreadyLiked) {
        // Unlike the comment
        $removeLike = $pdo->prepare('DELETE FROM comment_likes WHERE comment_id = ? AND user_id = ?');
        $removeLike->execute([$commentId, $userId]);

        // Decrement the like_count in the comments table
        $updateLikeCount = $pdo->prepare('UPDATE comments SET like_count = like_count - 1 WHERE id = ?');
        $updateLikeCount->execute([$commentId]);
    } else {
        // Like the comment
        $insertLike = $pdo->prepare('INSERT INTO comment_likes (comment_id, user_id) VALUES (?, ?)');
        $insertLike->execute([$commentId, $userId]);

        // Increment the like_count in the comments table
        $updateLikeCount = $pdo->prepare('UPDATE comments SET like_count = like_count + 1 WHERE id = ?');
        $updateLikeCount->execute([$commentId]);
    }

    // Retrieve updated like count
    $newLikeCount = $pdo->prepare('SELECT like_count FROM comments WHERE id = ?');
    $newLikeCount->execute([$commentId]);
    $likeCount = $newLikeCount->fetchColumn();

    echo json_encode(['status' => 'success', 'new_like_count' => $likeCount, 'action' => $alreadyLiked ? 'unlike' : 'like']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

?>
