<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unilink_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $postId = $_POST['post_id'];
    $content = $_POST['content'];
    $userId = $_SESSION['uid'];  // Ensure the user is logged in

    // Check if the current user owns the post
    $postStmt = $conn->prepare("SELECT * FROM forum_posts WHERE id = ? AND user_id = ?");
    $postStmt->bind_param("ii", $postId, $userId);
    $postStmt->execute();
    $postResult = $postStmt->get_result();
    $post = $postResult->fetch_assoc();

    if ($post) {
        // Default to the current image if no new image is uploaded
        $newImage = $post['image'];

        // Check if a new image is uploaded
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image'];
            $targetDir = "images/";  // Directory for uploaded images
            $targetFile = $targetDir . uniqid() . "_" . basename($image['name']);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Ensure the file is an image
            $check = getimagesize($image['tmp_name']);
            if ($check !== false) {
                // Move the uploaded file to the server
                if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                    // Delete the old image if it exists
                    if ($post['image'] && file_exists($post['image'])) {
                        unlink($post['image']);
                    }

                    // Set the new image path for the database
                    $newImage = $targetFile;  // Save the full path in the database
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    exit;
                }
            } else {
                echo "File is not an image.";
                exit;
            }
        }

        // Update the post content and image path in the database
        $updateQuery = "UPDATE forum_posts SET content = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssi", $content, $newImage, $postId);
        if ($stmt->execute()) {
            // Redirect to the post after editing
            header("Location: Forum.php");
            exit();
        } else {
            echo "Error updating post.";
        }
    } else {
        echo "Unauthorized action.";
    }
}

?>
