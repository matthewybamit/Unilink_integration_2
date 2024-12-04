<?php
// Include necessary files
include 'Forum_action.php';
require 'db_connect.php';

// Initialize variables
$user = null;
$posts = [];
$comments = [];
$userUid = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING); // Accept user_id as a string (for uid)

// Validate `user_id`
if (!$userUid) {
    echo "Invalid or missing user ID.";
    exit;
}

// Fetch user details using `uid` instead of `id`
$userQuery = $pdo->prepare('SELECT * FROM users WHERE uid = :uid');
$userQuery->execute(['uid' => $userUid]);
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

// Fetch user's posts (forum_posts links to `users.uid`)
$postsQuery = $pdo->prepare('SELECT * FROM forum_posts WHERE user_id = :user_id ORDER BY created_at DESC');
$postsQuery->execute(['user_id' => $userUid]);
$posts = $postsQuery->fetchAll(PDO::FETCH_ASSOC);

// Fetch comments and replies for each post
foreach ($posts as $post) {
    $commentQuery = $pdo->prepare('SELECT * FROM comments WHERE post_id = :post_id');
    $commentQuery->execute(['post_id' => $post['id']]);
    $postComments = $commentQuery->fetchAll(PDO::FETCH_ASSOC);

    foreach ($postComments as &$comment) {
        $replyQuery = $pdo->prepare('SELECT * FROM replies WHERE comment_id = :comment_id');
        $replyQuery->execute(['comment_id' => $comment['id']]);
        $comment['replies'] = $replyQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    $comments[$post['id']] = $postComments;
}

if (!isset($_SESSION['uid'])) {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['uid'];

// Fetch notifications
$notifications = fetchNotifications($user_id);

// Calculate total notification count
$notificationCount = 0;
if ($notifications) {
    $notificationCount += count($notifications['likes']);
    $notificationCount += count($notifications['comments']);

}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - <?= htmlspecialchars($user['username']) ?></title>

    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="Forum/Forum1.css">
    <link rel="stylesheet" href="Forum/edit_post.css">
    <link rel="stylesheet" href="CSS/curtain.css">
    <link rel="stylesheet" href="Forum/forumpost1.css">
    <link rel="stylesheet" href="Forum/report.css">
    <link rel="stylesheet" href="Forum/Image_slideshow.css">
    <link rel="stylesheet" href="Forum/comment_edit.css">
    <link rel="stylesheet" href="Forum/notification.css">   
    <link rel="stylesheet" href="Forum/Hashtag_Search.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<style>
/* General styling for profile container */
.profile-container {
    display: flex;
    justify-content: center;
    padding: 20px;
    background-color: #f4f7fc;
}

/* Profile card styling */
.profile-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;
    padding: 20px;
    text-align: center;
    margin: 0 auto;
}

/* Profile header section */
.profile-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;
}

/* Profile image styling */
.profile-image {
    border-radius: 50%;
    width: 120px;
    height: 120px;
    object-fit: cover;
    margin-bottom: 15px;
}

/* Username styling */
.profile-header h2 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
}

/* Profile info styling */
.profile-info p {
    font-size: 16px;
    color: #555;
    margin: 10px 0;
}

/* Strong labels for fields */
.profile-info strong {
    color: #333;
    font-weight: 600;
}

    
</style>
<body>
<!-- NAV BAR -->
<nav class="navbar">
    <div class="navbar__container">
        <a href="unilink.php" class="nav__logo">
            <img src="images/unilink_logo.png" alt="UniLink">
        </a>
        <div class="seperator__line"></div>
        <ul class="nav__menu">
            <li class="nav__items">
                <a href="Forum.php" class="nav__links">
                    <i class="fas fa-comments"></i>
                    <span class="nav__text">Forum</span>
                </a>
                <a href="Taskmanager.html" class="nav__links">
                    <i class="fa-solid fa-book"></i>
                    <span class="nav__text">QuizCU</span>
                </a>
                <a href="user_posts.php" class="nav__links">
                    <i class="fas fa-user"></i>
                    <span class="nav__text">User</span>
                </a>                
                
                <a href="#" class="nav__links" id="notification-bell">
                    <i class="fas fa-bell"></i>
                    <span class="nav__text">Notification</span>
                    <span class="notification-count"><?php echo $notificationCount; ?></span>
                </a>
           
                <div id="menu__bar" onclick="toggleCurtainMenu(this)">
                    <div class="bar1"></div>
                    <div class="bar2"></div>
                    <div class="bar3"></div>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- Notification Dropdown -->
<div class="notification-dropdown" id="notification-dropdown" style="display: none;">
    <div class="notification-header">
        <h3>Recent Updates</h3>
    </div>
    <div class="notification-body" id="notification-body">
        <!-- Notifications will be dynamically loaded here -->
    </div>
</div>

<div id="curtainMenu" class="curtain-menu">
    <a href="unilink.php">Home</a>
    <a href="services.html">Services</a>
    <a href="#">About</a>
    <a href="#">Contact</a>
</div>


<div class="profile-container">
    <div class="profile-header">
        <img src="<?= htmlspecialchars($user['profile_picture'] ?? 'images/default-avatar.png') ?>" alt="Profile Image" class="profile-image">
        <h2><?= htmlspecialchars($user['username']) ?></h2>
    </div>

    <div class="profile-info">
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'N/A') ?></p>
        <p><strong>Bio:</strong> <?= htmlspecialchars($user['bio'] ?? 'No bio available') ?></p>
        <p><strong>Joined on:</strong> <?= date('F j, Y', strtotime($user['registration_date'] ?? '')) ?></p>
    </div>
    </div>

    
    <?php if ($posts): ?>
    <?php foreach ($posts as $post): ?>
        <div class="forum-post" id="post-<?= $post['id'] ?>">
            <div class="post-header">
                <div class="post-user-info">
                    <a href="users_info.php?user_id=<?= htmlspecialchars($post['user_id']) ?>" class="username-link">
                        <img src="<?= htmlspecialchars($post['user_image'] ?? 'images/default-avatar.png') ?>" alt="User Avatar" class="avatar-image">
                        <span class="username"><?= htmlspecialchars($post['username']) ?></span>
                    </a>
                    <span class="timestamp">posted <?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></span>
                </div>

                <?php if ($_SESSION['uid'] === $post['user_id']): ?>
                <div class="post-options">
                    <i class="fa-solid fa-ellipsis-vertical" onclick="togglePostOptions(<?= $post['id'] ?>)"></i>
                    <div class="post-options-menu" id="post-options-<?= $post['id'] ?>" style="display: none;">
                        <button onclick="enableEditPost(<?= $post['id'] ?>)">Edit Post</button>
                        <button onclick="deletePost(<?= $post['id'] ?>)">Delete</button>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="post-body">
                <div id="post-content-<?= $post['id'] ?>" class="post-content-view">
                    <p><?= htmlspecialchars($post['content']) ?></p>

                    <?php
                    // Fetch all images associated with this post
                    $stmt = $pdo->prepare("SELECT image_path FROM post_images WHERE post_id = ?");
                    $stmt->execute([$post['id']]);
                    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($images)): ?>
                        <div class="post-images-wrapper">
                            <?php foreach ($images as $image): ?>
                                <div class="post-image-wrapper">
                                    <img src="<?= htmlspecialchars($image['image_path']) ?>" alt="Post Image" class="post-image">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Post Footer -->
            <div class="post-footer">
                <button class="post-footer__btn like-btn" data-post-id="<?= $post['id'] ?>">
                    <i class="fa-solid fa-thumbs-up"></i> Like <span class="like-count"><?= $post['like_count'] ?></span>
                </button>
                <button class="post-footer__btn">
                    <i class="fa-solid fa-comment"></i>
                    Comment (<?= isset($comments[$post['id']]) ? count($comments[$post['id']]) : 0; ?>)
                </button>
                <button class="post-footer__btn report-btn" data-post-id="<?= $post['id'] ?>" onclick="openReportModal(<?= $post['id'] ?>)">
                    <i class="fa-solid fa-flag"></i> Report
                </button>
            </div>

            <!-- Comments Section -->
            <div class="comments" id="comments-<?= $post['id'] ?>">
                <?php if (isset($comments[$post['id']])): ?>
                    <?php
                    $commentCount = 0; // Counter for comments
                    foreach ($comments[$post['id']] as $comment): ?>
                        <div class="comment" id="comment-<?= $comment['id'] ?>"> <!-- Main comment container -->
                            <div class="comment-user-info">
                                <img src="<?= htmlspecialchars($comment['user_image'] ?? 'images/default-avatar.png') ?>" alt="Comment User Avatar" class="comment-avatar-image">
                                <span class="comment-user"><?= htmlspecialchars($comment['username']) ?></span>
                                <div class="comment-time">
                                    <?= date('F j, Y, g:i a', strtotime($comment['created_at'])) ?>
                                </div>
                                <!-- Ellipsis Menu for Edit/Delete -->
                                <div class="comment-actions">
                                    <?php if ($_SESSION['uid'] === $comment['user_id']): ?>
                                        <span class="action-ellipsis" onclick="toggleCommentActions('<?= $comment['id'] ?>')">...</span>
                                        <div class="comment-action-menu" id="comment-actions-<?= $comment['id'] ?>" style="display: none;">
                                            <button onclick="editComment(<?= $comment['id'] ?>)">Edit</button>
                                            <button onclick="deleteComment(<?= $comment['id'] ?>)">Delete</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <p class="comment-text"><?= htmlspecialchars($comment['content']) ?></p>

                            <!-- Like Button for Comments -->
                            <div class="comment-likes">
                                <button class="comment-footer__btn comment-like-btn" data-comment-id="<?= $comment['id'] ?>">
                                    <i class="fa-solid fa-thumbs-up"></i> Like 
                                    <span class="like-count"><?= $comment['like_count'] ?></span>
                                </button>
                            </div>

                            <!-- Replies Section -->    
                            <div class="replies" id="replies-<?= $comment['id'] ?>">
                                <?php if (isset($comment['replies']) && !empty($comment['replies'])): ?>
                                    <?php
                                    $replyCount = 0; // Counter for replies
                                    foreach ($comment['replies'] as $reply): ?>
                                        <div class="reply" id="reply-<?= $reply['id'] ?>" style="display: <?= $replyCount >= 1 ? 'none' : 'block'; ?>"> <!-- Initially hide all replies except the first one -->
                                            <div class="reply-user-info">
                                                <img src="<?= htmlspecialchars($reply['user_image'] ?? 'images/default-avatar.png') ?>" alt="Reply User Avatar" class="reply-avatar-image">
                                                <span class="reply-user"><?= htmlspecialchars($reply['username']) ?></span>
                                                <div class="reply-time">
                                                    <?= date('F j, Y, g:i a', strtotime($reply['reply_created_at'])) ?>
                                                </div>

                                                <!-- Reply Actions (Ellipsis) -->
                                                <div class="reply-actions">
                                                    <?php if ($_SESSION['uid'] === $reply['user_id']): ?>
                                                        <span class="action-ellipsis" onclick="toggleReplyActions('<?= $reply['id'] ?>')">...</span>
                                                        <div class="reply-action-menu" id="reply-actions-<?= $reply['id'] ?>" style="display: none;">
                                                            <button onclick="editReply(<?= $reply['id'] ?>)">Edit</button>
                                                            <button onclick="deleteReply(<?= $reply['id'] ?>)">Delete</button>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <p class="reply-text"><?= htmlspecialchars($reply['content']) ?></p>

                                            <!-- Like Button for Replies -->
                                            <div class="reply-likes">
                                                <button class="reply-footer__btn reply-like-btn" data-reply-id="<?= $reply['id'] ?>">
                                                    <i class="fa-solid fa-thumbs-up"></i> Like 
                                                    <span class="like-count"><?= $reply['like_count'] ?></span>
                                                </button>
                                            </div>

                                        </div>
                                        <?php $replyCount++; ?>
                                    <?php endforeach; ?>

                                    <?php if (count($comment['replies']) > 1): ?>
                                        <button class="show-more-replies" data-comment-id="<?= $comment['id'] ?>">Show more replies</button>
                                        <button class="view-less-replies" data-comment-id="<?= $comment['id'] ?>" style="display:none;">View less replies</button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <!-- Reply Form (for the comment itself) -->
                            <form method="POST" class="reply-form" id="reply-form-<?= $comment['id'] ?>">
                                <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['id']) ?>">
                                <input type="hidden" name="form_token" value="<?= $_SESSION['form_token'] ?>">
                                <input type="text" name="reply_content" placeholder="Write a reply..." required>
                                <button type="submit" style="display: none;"><i class="fa-solid fa-paper-plane"></i></button>
                            </form>
                        </div>
                        <?php $commentCount++; ?>
                    <?php endforeach; ?>

                    <?php if (count($comments[$post['id']]) > 2): ?>
                        <button class="show-more-comments" data-post-id="<?= $post['id'] ?>">Show more comments</button>
                        <button class="view-less-comments" data-post-id="<?= $post['id'] ?>" style="display:none;">View less comments</button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Comment Form (at the bottom of the post) -->
            <form action="Forum.php" method="POST" class="comment-form" id="comment-form-<?= $post['id'] ?>">
                <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                <input type="hidden" name="form_token" value="<?= $_SESSION['form_token'] ?>">
                <input type="text" name="comment_content" placeholder="Write a comment..." required>
                <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
            </form>

        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No posts available.</p>
<?php endif; ?>


<script src="ForumJs/reply_comment_like.js"></script>
<script src="ForumJs/reporting.js"></script>
<script src="ForumJs/comment_reply_delete.js"></script>
<script src="ForumJs/post-details.js"></script>
<script src="ForumJs/edit_delete.js"></script>
<script src="ForumJs/deletePost.js"></script>
<script src="ForumJs/likesFunction.js"></script>
<script src="ForumJs/showFunction.js"></script>
<script src="ForumJs/toggleOption.js"></script>
<script src="ForumJs/notification.js"></script>
</body>
</html>
