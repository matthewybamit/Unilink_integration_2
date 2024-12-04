<?php
include 'Forum_action.php'; // Database connection and session setup

// Check if user_id is provided
if (!isset($_GET['user_id'])) {
    echo "User ID not provided.";
    exit();
}

$user_id = $_GET['user_id'];

// Fetch user comments along with the post they commented on
try {
    $stmt = $pdo->prepare("SELECT comments.*, forum_posts.content AS post_content 
                           FROM comments 
                           JOIN forum_posts ON comments.post_id = forum_posts.id 
                           WHERE comments.user_id = ? 
                           ORDER BY comments.comment_created_at DESC");
    $stmt->execute([$user_id]);
    $userComments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching user comments: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Comments</title>
    <link rel="stylesheet" href="CSS/unistyle.css">
</head>
<body>

<div class="profile-container">
    <h2>User's Comments</h2>
    <?php if (!empty($userComments)): ?>
        <?php foreach ($userComments as $comment): ?>
            <div class="user-comment">
                <p><strong>Commented on post:</strong> <?= htmlspecialchars($comment['post_content']) ?></p>
                <p><?= htmlspecialchars($comment['content']) ?></p>
                <span class="timestamp"><?= date('F j, Y, g:i a', strtotime($comment['comment_created_at'])) ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No comments available.</p>
    <?php endif; ?>
</div>

</body>
</html>
