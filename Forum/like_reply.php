<?php
session_start();
require 'db_connect.php'; // Include your database configuration file

if (isset($_POST['reply_id']) && isset($_SESSION['uid'])) {
    $replyId = (int)$_POST['reply_id']; // Ensure reply_id is an integer
    $userId = $_SESSION['uid']; // Keep user_id as a string

    // Check if the user already liked this reply
    $checkLike = $pdo->prepare('SELECT 1 FROM reply_likes WHERE reply_id = ? AND user_id = ?');
    $checkLike->execute([$replyId, $userId]);
    $alreadyLiked = $checkLike->fetchColumn() !== false;

    if ($alreadyLiked) {
        // Unlike the reply
        $removeLike = $pdo->prepare('DELETE FROM reply_likes WHERE reply_id = ? AND user_id = ?');
        $removeLike->execute([$replyId, $userId]);

        // Decrement the like_count in the replies table
        $updateLikeCount = $pdo->prepare('UPDATE replies SET like_count = like_count - 1 WHERE id = ?');
        $updateLikeCount->execute([$replyId]);
    } else {
        // Like the reply
        $insertLike = $pdo->prepare('INSERT INTO reply_likes (reply_id, user_id) VALUES (?, ?)');
        $insertLike->execute([$replyId, $userId]);

        // Increment the like_count in the replies table
        $updateLikeCount = $pdo->prepare('UPDATE replies SET like_count = like_count + 1 WHERE id = ?');
        $updateLikeCount->execute([$replyId]);
    }

    // Retrieve updated like count
    $newLikeCount = $pdo->prepare('SELECT like_count FROM replies WHERE id = ?');
    $newLikeCount->execute([$replyId]);
    $likeCount = $newLikeCount->fetchColumn();

    echo json_encode(['status' => 'success', 'new_like_count' => $likeCount, 'action' => $alreadyLiked ? 'unlike' : 'like']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

?>
