<?php 

// Check if a hashtag is selected via GET request
if (isset($_GET['hashtag'])) {
    // Store the selected hashtag in the session
    $_SESSION['selectedHashtag'] = $_GET['hashtag'];
}

// Retrieve the hashtag from the session if available
$selectedHashtag = isset($_SESSION['selectedHashtag']) ? $_SESSION['selectedHashtag'] : null;

// Modify your query to filter posts by the selected hashtag
if ($selectedHashtag) {
    // Fetch posts that contain the selected hashtag
    $posts = getPostsByHashtag($selectedHashtag); // Function to get posts by hashtag
} else {
    // Fetch all posts normally (without hashtag filtering)
    $posts = getAllPosts(); // Function to get all posts
}

// Example function to fetch posts by hashtag
function getPostsByHashtag($hashtag) {
    // Database connection (adjust credentials)
    $conn = new mysqli('localhost', 'root', '', 'unilink_database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT * FROM forum_posts WHERE content LIKE ?");
    $searchTerm = '%' . $hashtag . '%'; // Search for the hashtag within the post content
    $stmt->bind_param('s', $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $posts;
}

// Example function to fetch all posts
function getAllPosts() {
    // Database connection (adjust credentials)
    $conn = new mysqli('localhost', 'root', '', 'unilink_database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT * FROM forum_posts");
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $posts;
}

unset($_SESSION['selectedHashtag']);//

?> 