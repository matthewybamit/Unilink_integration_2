<?php
session_start();

// Handle the action from the client-side to update session state
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $postId = $_POST['postId'] ?? null;
    $commentId = $_POST['commentId'] ?? null;

    // Handle the comment state (expanded or collapsed)
    if ($action == 'expand_comments' && $postId) {
        $_SESSION['comments-expanded'][$postId] = true;
    } elseif ($action == 'collapse_comments' && $postId) {
        $_SESSION['comments-expanded'][$postId] = false;
    }

    // Handle the replies state (expanded or collapsed)
    if ($action == 'expand_replies' && $commentId) {
        $_SESSION['replies-expanded'][$commentId] = true;
    } elseif ($action == 'collapse_replies' && $commentId) {
        $_SESSION['replies-expanded'][$commentId] = false;
    }

    // Return success response
    echo json_encode(['success' => true]);
    exit;
}
?>
