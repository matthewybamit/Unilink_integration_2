<?php
include 'Forum_action.php';  // Ensure this includes your DB connection and functions

if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];
    $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

    // Fetch additional comments starting from the offset (2 in this case)
    $sql = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at ASC LIMIT 2 OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the comments in JSON format
    if ($comments) {
        echo json_encode(['status' => 'success', 'comments' => $comments]);
    } else {
        echo json_encode(['status' => 'fail', 'message' => 'No more comments.']);
    }
}
?>
