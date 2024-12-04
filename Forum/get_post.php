<?php
if (isset($_GET['action']) && $_GET['action'] === 'get_post' && isset($_GET['post_id'])) {
    $postId = (int)$_GET['post_id'];
    $stmt = $pdo->prepare("SELECT * FROM forum_posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$postId, $userId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        echo json_encode(['status' => 'success', 'post' => $post]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Post not found or permission denied.']);
    }
    exit;
}

?>