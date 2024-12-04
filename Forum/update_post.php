<?php

require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['uid'])) {
    $postId = $_POST['post_id'] ?? null;
    $content = $_POST['content'] ?? null;
    $userId = $_SESSION['uid'];
    $imagePath = null;

    // Validate input
    if (!$postId || !$content) {
        echo "Invalid input data.";
        exit;
    }

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $imagePath = $targetDir . basename($_FILES["image"]["name"]);

        // Ensure uploads directory exists and is writable
        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            echo "Upload directory does not exist or is not writable.";
            exit;
        }

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            echo "Image upload failed.";
            exit;
        }
    }

    // Update the database
    $stmt = $conn->prepare(
        "UPDATE forum_posts 
         SET content = ?, image = IFNULL(?, image) 
         WHERE id = ? AND user_id = ?"
    );
    $stmt->bind_param("ssis", $content, $imagePath, $postId, $userId);

    if ($stmt->execute()) {
        header('Location: Forum.php');
        exit;
    } else {
        echo "Error updating post: " . $stmt->error;
        exit;
    }
} else {
    echo "Unauthorized or invalid request.";
    exit;
}
?>
