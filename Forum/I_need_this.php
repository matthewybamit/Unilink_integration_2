<?php
include 'Forum_action.php'; // Database connection and session setup

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Fetch posts by the logged-in user
if (!isset($_SESSION['uid'])) {
    echo "User not logged in.";
    exit();
}
$user_id = $_SESSION['uid']; // Use session user ID

// Fetch user posts
try {
    $stmt = $pdo->prepare("SELECT * FROM forum_posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $userPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching user posts: " . $e->getMessage();
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_content']) && isset($_POST['post_id'])) {
    $comment_content = $_POST['comment_content'];
    $post_id = $_POST['post_id'];

    if (!empty($comment_content)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content, username) VALUES (?, ?, ?, ?)");
            $stmt->execute([$post_id, $_SESSION['uid'], $comment_content, $_SESSION['username']]);
        } catch (PDOException $e) {
            echo "Error inserting comment: " . $e->getMessage();
            exit();
        }
    }
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_content']) && isset($_POST['comment_id'])) {
    $reply_content = $_POST['reply_content'];
    $comment_id = $_POST['comment_id'];

    if (!empty($reply_content)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO replies (comment_id, user_id, content, username) VALUES (?, ?, ?, ?)");
            $stmt->execute([$comment_id, $_SESSION['uid'], $reply_content, $_SESSION['username']]);
        } catch (PDOException $e) {
            echo "Error inserting reply: " . $e->getMessage();
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User's Posts</title>
    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="CSS/profile.css">
    <script src="Javascripts/app.js"></script>
</head>
<body>

<!-- User Profile Layout -->
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-info">
            <img id="profilePicture" class="profile-picture" src="<?php echo $_SESSION['profilePicture']; ?>" alt="Profile Picture" />
            <h2 id="username"><?php echo $_SESSION['username']; ?></h2>
            <p id="email"><?php echo $_SESSION['email']; ?></p>
        </div>
    </div>

    <!-- User Posts Section -->
    <div class="profile-container">
        <h2>User's Posts</h2>
        <?php if (!empty($userPosts)): ?>
            <?php foreach ($userPosts as $post): ?>
                <div class="user-post">
                    <p><strong>Post:</strong> <?= htmlspecialchars($post['content']) ?></p>
                    <?php if (!empty($post['image'])): ?>
                        <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post Image" class="post-image">
                    <?php endif; ?>
                    <span class="timestamp"><?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></span>

                    <!-- Comment Section -->
                    <div class="comment-section">
                        <h4>Comments:</h4>
                        <?php
                        // Fetch comments for this post
                        try {
                            $commentStmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY comment_created_at ASC");
                            $commentStmt->execute([$post['id']]);
                            $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            echo "Error fetching comments: " . $e->getMessage();
                            exit();
                        }

                        if (!empty($comments)):
                            foreach ($comments as $comment):
                        ?>
                            <div class="comment">
                                <div class="comment-header">
                                    <img src="<?= htmlspecialchars($comment['user_image']) ?>" alt="Commenter Image" class="commenter-image">
                                    <p><strong><?= htmlspecialchars($comment['username']) ?></strong></p>
                                </div>
                                <p><strong>Comment:</strong> <?= htmlspecialchars($comment['content']) ?></p>
                                <span class="timestamp"><?= date('F j, Y, g:i a', strtotime($comment['comment_created_at'])) ?></span>

                                <!-- Reply Section -->
                                <div class="replies">
                                    <?php
                                    try {
                                        $replyStmt = $pdo->prepare("SELECT * FROM replies WHERE comment_id = ? ORDER BY reply_created_at ASC");
                                        $replyStmt->execute([$comment['id']]);
                                        $replies = $replyStmt->fetchAll(PDO::FETCH_ASSOC);
                                    } catch (PDOException $e) {
                                        echo "Error fetching replies: " . $e->getMessage();
                                        exit();
                                    }

                                    if (!empty($replies)):
                                        foreach ($replies as $reply):
                                    ?>
                                        <div class="reply">
                                            <p><strong>Reply:</strong> <?= htmlspecialchars($reply['content']) ?></p>
                                            <span class="timestamp"><?= date('F j, Y, g:i a', strtotime($reply['reply_created_at'])) ?></span>
                                        </div>
                                    <?php endforeach; else: ?>
                                        <p>No replies yet.</p>
                                    <?php endif; ?>
                                </div>

                                <!-- Reply Form -->
                                <form action="user_posts.php?user_id=<?= $_SESSION['uid'] ?>" method="post">
                                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                    <textarea name="reply_content" placeholder="Write your reply..." required></textarea>
                                    <button type="submit">Submit Reply</button>
                                </form>
                            </div>
                        <?php endforeach; else: ?>
                            <p>No comments available for this post.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Comment Form -->
                    <form action="user_posts.php?user_id=<?= $_SESSION['uid'] ?>" method="post">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <textarea name="comment_content" placeholder="Write your comment..." required></textarea>
                        <button type="submit">Submit Comment</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>






<div class="pagination">
   <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="pagination-link">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>" class="pagination-link <?= $i == $page ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>" class="pagination-link">Next</a>
    <?php endif; ?>
</div> 





// Check if sorting preference is set in session, otherwise default to 'new'
if (isset($_SESSION['sort'])) {
    $sort = $_SESSION['sort'];
} else {
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'new';
    $_SESSION['sort'] = $sort; // Save the sort preference to session
}

if ($sort === 'popular') {
    // Sort by like_count in descending order (popular)
    $query = 'SELECT forum_posts.*, COUNT(post_likes.id) AS like_count 
              FROM forum_posts 
              LEFT JOIN post_likes ON forum_posts.id = post_likes.post_id 
              GROUP BY forum_posts.id 
              ORDER BY like_count DESC';

    $stmt = $pdo->prepare($query);
    $stmt->execute();

} elseif ($sort === 'hot') {
    // Get the current date and time minus 24 hours
    $timeLimit = (new DateTime())->modify('-24 hours')->format('Y-m-d H:i:s');

    // Get posts with the most likes in the last 24 hours (even if the post is old)
    $query = 'SELECT forum_posts.*, COUNT(post_likes.id) AS like_count 
              FROM forum_posts 
              LEFT JOIN post_likes ON forum_posts.id = post_likes.post_id 
              WHERE post_likes.created_at >= :time_limit 
              GROUP BY forum_posts.id 
              ORDER BY like_count DESC';

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':time_limit', $timeLimit); // bind the time limit for likes
    $stmt->execute();

} else {
    // Default sorting is by newest posts
    $query = 'SELECT * FROM forum_posts ORDER BY created_at DESC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
}

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
