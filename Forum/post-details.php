<?php
include 'Forum_action.php';
require 'db_connect.php'; // Database connection

// Check if post_id is provided in the query string (e.g., from notification)
if (isset($_GET['id'])) {
    $postId = intval($_GET['id']);

    // Fetch the post by id
    $postQuery = $pdo->prepare('SELECT * FROM forum_posts WHERE id = :id');
    $postQuery->execute(['id' => $postId]);
    $post = $postQuery->fetch(PDO::FETCH_ASSOC);

    // Fetch the comments related to this post
    $commentQuery = $pdo->prepare('
        SELECT * FROM comments WHERE post_id = :post_id
    ');
    $commentQuery->execute(['post_id' => $postId]);
    $comments = $commentQuery->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the replies for each comment
    foreach ($comments as &$comment) {
        $replyQuery = $pdo->prepare('
            SELECT * FROM replies WHERE comment_id = :comment_id
        ');
        $replyQuery->execute(['comment_id' => $comment['id']]);
        $comment['replies'] = $replyQuery->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    echo "No post selected.";
    exit;
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
    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="Forum/Forum1.css">
    <link rel="stylesheet" href="Forum/edit_post.css">
    <link rel="stylesheet" href="CSS/curtain.css">
    <link rel="stylesheet" href="Forum/report.css">
    <link rel="stylesheet" href="Forum/forumpost1.css">
    <link rel="stylesheet" href="Forum/notification.css">  
    <link rel="stylesheet" href="Forum/comment_edit.css">
    <link rel="stylesheet" href="Forum/Hashtag_Search.css">
    <title>Post Details</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
/* Report Modal Styles */
.post-images-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.post-image-wrapper {
    max-width: 100%;
    flex: 1;
}

.post-image {
    width: 100%;
    height: auto;
    border-radius: 5px;
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



<div class="container">



    <?php if ($post): ?>
        <div class="forum-post" id="post-<?= $post['id'] ?>">
            <div class="post-header">
                <div class="post-user-info">
                    <a href="users_info.php?user_id=<?= htmlspecialchars($post['user_id']) ?>" class="username-link">
                        <img src="<?= htmlspecialchars($post['user_image'] ?? 'images/default-avatar.png') ?>" alt="User Avatar" class="avatar-image">
                        <span class="username"><?= htmlspecialchars($post['username']) ?></span>
                    </a>
                    <span class="timestamp">posted <?= htmlspecialchars($post['created_at']) ?></span>
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



            <div id="edit-content-<?= $post['id'] ?>" class="post-content-edit" style="display: none;">
    <form id="edit-form-<?= $post['id'] ?>" method="post" action="user_posts.php" enctype="multipart/form-data">
        <!-- Textarea for content editing without border -->
        <textarea name="content" class="edit-textarea" required><?= htmlspecialchars($post['content']) ?></textarea>

        <!-- Display current image if exists -->
        <div class="current-image-container">
            <?php if (!empty($post['image'])): ?>
                <img src="images/<?= $post['image'] ?>" alt="Current Image" class="current-image">
            <?php else: ?>
                <p>No image uploaded yet.</p>
            <?php endif; ?>
        </div>

        <!-- File upload for new image (optional) -->
        <div class="file-upload-container">
            <input type="file" name="image" accept="image/*" class="edit-image-upload">
            <span class="file-text">Choose a new image (optional)</span>
        </div>

        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

        <!-- Save/Cancel buttons -->
        <div class="edit-actions">
            <button type="submit" class="save-button">Save</button>
            <button type="button" class="cancel-button" onclick="cancelEditPost(<?= $post['id'] ?>)">Cancel</button>
        </div>
    </form>
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
    <!-- Report Button -->
    <button class="post-footer__btn report-btn" data-post-id="<?= $post['id'] ?>" onclick="openReportModal(<?= $post['id'] ?>)">
        <i class="fa-solid fa-flag"></i> Report
    </button>
</div>

<!-- Report Modal -->
<div id="report-modal-<?= $post['id'] ?>" class="report-modal" style="display: none;">
    <div class="report-modal-content">
        <span class="close-btn" onclick="closeReportModal(<?= $post['id'] ?>)">&times;</span>
        <h3>Report Post</h3>
        <form id="report-form-<?= $post['id'] ?>" onsubmit="submitReportForm(event, <?= $post['id'] ?>)">
            <label for="reason">Select Reason:</label>
            <select name="reason" id="reason" required>
                <option value="Spam">Spam</option>
                <option value="Offensive Content">Offensive Content</option>
                <option value="Harassment">Harassment</option>
                <option value="Other">Other</option>
            </select>

            <textarea name="comments" placeholder="Additional comments (optional)" rows="4"></textarea>

            <!-- Hidden field for post ID -->
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

            <button type="submit" class="submit-report-btn">Submit Report</button>
        </form>

        <div id="report-status-<?= $post['id'] ?>" class="report-status"></div> <!-- To show success/failure -->
    </div>
</div>

<!-- Comments Section -->
<div class="comments" id="comments-<?= $post['id'] ?>">
    <?php foreach ($comments as $comment): ?>
        <div class="comment" id="comment-<?= $comment['id'] ?>" style="display: <?= $commentCount >= 2 ? 'none' : 'block'; ?>"> <!-- Initially hide all comments beyond the first two -->
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

                            
                                 <!-- The ellipsis button will be shown only to the owner of the reply -->
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

            <!-- Reply Form -->
            <form method="POST" class="reply-form" id="reply-form-<?= $comment['id'] ?>">
                <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['id']) ?>">
                <input type="hidden" name="form_token" value="<?= $_SESSION['form_token'] ?>">
                <input type="text" name="reply_content" placeholder="Write a reply..." required>
                <button type="submit" style="display: none;"><i class="fa-solid fa-paper-plane"></i></button>
            </form>
        </div>
     
    <?php endforeach; ?>

    <?php if (count($comments) > 2): ?>
        <button class="show-more-comments" data-post-id="<?= $post['id'] ?>">Show more comments</button>
        <button class="view-less-comments" data-post-id="<?= $post['id'] ?>" style="display:none;">View less comments</button>
    <?php endif; ?>

    <!-- Comment Form -->
    <form method="POST" class="comment-form" id="comment-form-<?= $post['id'] ?>">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
        <input type="hidden" name="form_token" value="<?= $_SESSION['form_token'] ?>">
        <input type="text" name="comment_content" placeholder="Write a comment..." required>
        <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
    </form>
</div>

    <?php else: ?>
        <p>Post not found.</p>
    <?php endif; ?>
</div>


<script>
function submitReportForm(event, postId) {
    event.preventDefault();  // Prevent the form from submitting normally
    
    const form = document.getElementById('report-form-' + postId);
    const formData = new FormData(form);
    const modal = document.getElementById('report-modal-' + postId); // Get the modal element
    const statusDiv = document.getElementById('report-status-' + postId); // Div to show success/failure messages

    // Make the AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'report_post.php', true);

    xhr.onload = function() {
        const response = JSON.parse(xhr.responseText);

        if (response.success) {
            // Show success message
            statusDiv.innerHTML = `<span style="color: green;">${response.message}</span>`;
            form.reset();  // Clear the form fields
            
            // Close the modal after success
            modal.style.display = 'none';
        } else {
            // Show failure message
            statusDiv.innerHTML = `<span style="color: red;">${response.message}</span>`;
        }
    };

    xhr.onerror = function() {
        statusDiv.innerHTML = '<span style="color: red;">An error occurred. Please try again later.</span>';
    };

    xhr.send(formData);
}

</script>
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
