<?php 

// Get the user ID from the session
$userId = $_SESSION['uid'];

// Database connection setup
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

// Query for posts made by the current session user using prepared statements
$postsStmt = $conn->prepare("SELECT * FROM forum_posts WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$postsStmt->bind_param("s", $userId);
$postsStmt->execute();
$postsResult = $postsStmt->get_result();
$posts = $postsResult->fetch_all(MYSQLI_ASSOC);

// Query for interactions on the current user’s posts (comments, likes, replies)
$interactionsQuery = "
-- Comments on the user's posts, but not their own comments
SELECT 'comment' AS type, c.content, c.created_at, fp.content AS post_content
FROM comments c
INNER JOIN forum_posts fp ON c.post_id = fp.id
WHERE fp.user_id = ? AND c.user_id != ?

UNION

-- Replies to the user's comments, but not their own replies
SELECT 'reply' AS type, r.content, r.reply_created_at AS created_at, fp.content AS post_content
FROM replies r
INNER JOIN comments c ON r.comment_id = c.id
INNER JOIN forum_posts fp ON c.post_id = fp.id
WHERE fp.user_id = ? AND r.user_id != ?

UNION

-- Likes on the user's posts
SELECT 'like' AS type, '' AS content, pl.created_at, fp.content AS post_content
FROM post_likes pl
INNER JOIN forum_posts fp ON pl.post_id = fp.id
WHERE fp.user_id = ?

ORDER BY created_at DESC LIMIT 10";

$interactionsStmt = $conn->prepare($interactionsQuery);
$interactionsStmt->bind_param("sssss", $userId, $userId, $userId, $userId, $userId);
$interactionsStmt->execute();
$interactionsResult = $interactionsStmt->get_result();
$interactions = $interactionsResult->fetch_all(MYSQLI_ASSOC);

// Get notification count (number of posts + interactions)
$notificationCount = count($posts) + count($interactions);

// Fetch the user's created_at (join date) from the users table
$joinDateQuery = "SELECT created_at FROM users WHERE uid = ?";
$joinDateStmt = $conn->prepare($joinDateQuery);
$joinDateStmt->bind_param("s", $userId);
$joinDateStmt->execute();
$joinDateResult = $joinDateStmt->get_result();
$joinDateRow = $joinDateResult->fetch_assoc();

// Check if the user exists and retrieve the join date
if ($joinDateRow) {
    $joinDate = $joinDateRow['created_at'];
} else {
    $joinDate = null; // Default or error handling if needed
}
    
// Close the database connection
// Close the connection
$conn->close();


?>