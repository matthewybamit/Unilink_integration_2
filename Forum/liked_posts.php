<?php
include 'Forum_action.php';
include 'post_edit.php';
require 'db_connect.php'; 


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


// Fetch the user's created_at (join date) from the users table

$uid = $_SESSION['uid'];

// Fetch liked posts for the user
$stmt = $pdo->prepare("
    SELECT 
        fp.id,
        fp.content,
        fp.username,
        fp.user_image,
        fp.created_at,
        fp.image,
        fp.like_count,
        fp.user_id
    FROM post_likes pl
    INNER JOIN forum_posts fp ON pl.post_id = fp.id
    WHERE pl.user_id = ?
    ORDER BY fp.created_at DESC
");
$stmt->execute([$uid]);
$liked_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="Forum/Forum1.css">
    <link rel="stylesheet" href="CSS/curtain.css">
    <link rel="stylesheet" href="Forum/report.css">
    <link rel="stylesheet" href="Forum/edit_post.css">
    <link rel="stylesheet" href="Forum/forumpost1.css">
    <link rel="stylesheet" href="Forum/notification.css">   
    <link rel="stylesheet" href="Forum/Hashtag_Search.css">
    <link rel="stylesheet" href="Forum/users_profile.css">
    <link rel="stylesheet" href="Forum/comment_edit.css">
    <script src="Javascripts/app.js"></script>
    <title>Liked Posts</title>
     
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<style>

/* Sidebar Styling */
.forum-sidebar {
    width: 15%;
    padding-right: 20px;
    margin-top: 20px;
}

.forum-btn {
    display: flex;
    align-items: center;
    justify-content: start;
    padding: 10px;
    font-size: 16px;
    border: none;

    cursor: pointer;
    margin-bottom: 10px;

}

.forum-btn:hover {
    background: #ff975e;
}

.forum-btn-icon {
    margin-right: 10px;
    font-size: 20px; /* Adjust icon size */
}

.logout-btn {
    background: #ffcccc;
    color: #900;
}

.logout-btn:hover {
    background: #ff9999;
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
                <a href="../Gamify/landingpage.php" class="nav__links">
    <i class="fa-solid fa-chalkboard-teacher"></i>
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



<!-- User Profile Layout -->
<div class="profile-container">
            
    <div class="profile-header">
        <div class="profile-info">
            <!-- Profile Picture -->
            <div class="profile-picture-wrapper">
                <img 
                    id="profilePicture" 
                    class="profile-picture" 
                    src="<?php echo htmlspecialchars($_SESSION['profilePicture'] ?? 'default-avatar.png'); ?>" 
                    alt="Profile Picture" />
            </div>
            
            <!-- User Information -->
            <div class="user-details">
                <h2 id="username"><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                <p id="email"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <p class="join-date" id="joinDate">Joined on    <?php
        if ($joinDate) {
            echo date("F jS, Y", strtotime($joinDate));} else { echo "Date not available"; }  ?></p>
            </div>

        </div>

        <!-- Social Links -->
        <div class="social-links">
            <a href="#"><i class="fa-brands fa-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-linkedin"></i></a>
        </div>
    </div>

    <!-- Profile Tabs Section -->
    <div class="profile-tabs">

    </div>



<!-- Modal for Creating Post -->
<div id="create-post-modal" class="create-post-modal">
    <div class="create-post-content">
        <!-- Close Button -->
        <span id="close-post-modal" class="close-post-modal">&times;</span>

        <!-- Title -->
        <h2 class="modal-title">Create Post</h2>

        <!-- User Section -->
        <div class="user-profile-section">
            <img src="<?= $_SESSION['profilePicture'] ?? 'images/default-avatar.png'; ?>" alt="User Profile Picture" class="user-profile-picture">
            <span class="user-username"><?= $_SESSION['username'] ?? 'Guest'; ?></span>
        </div>

        <!-- Form -->
        <form id="create-post-form" action="Forum.php" method="POST" enctype="multipart/form-data">
            <!-- Image Upload -->
            <label for="image-upload" class="image-upload-label">
                <div class="image-upload-icon">
                    <i class="fas fa-images"></i>
                </div>
            </label>
            <input type="file" id="image-upload" name="images[]" accept="image/*" multiple hidden>

            <!-- Preview Section -->
            <div id="image-preview-container" class="image-preview-container"></div>

            <!-- Text Area -->
            <textarea 
                name="post_content" 
                placeholder="Type your ideas!" 
                class="forum-post-input" 
                required></textarea>

            <!-- Hidden Fields -->
            <input type="hidden" name="form_token" value="<?= $_SESSION['form_token'] ?>">
            <input type="hidden" name="user_id" value="<?= $_SESSION['uid'] ?>">

            <!-- Publish Button -->
            <button type="submit" class="forum-post-btn">Publish</button>
        </form>
    </div>
</div>


<!-- FORUM SECTION -->
<div class="forum-container">
<div class="forum-sidebar">
    <button class="forum-btn" onclick="location.href='user_posts.php'"><i class="fas fa-user-circle forum-btn-icon"></i> About </button>
    <button class="forum-btn " onclick="location.href='user_posts.php?user_id=<?= $_SESSION['uid'] ?>'"><i class="fas fa-file-alt forum-btn-icon"></i> Posts </button>
    <button class="forum-btn active" onclick="location.href='liked_posts.php?user_id=<?= $_SESSION['uid'] ?>'"><i class="fas fa-heart forum-btn-icon"></i> Liked Posts </button>
    <button class="forum-btn" onclick="location.href='taskmanager.php?user_id=<?= $_SESSION['uid'] ?>'"><i class="fas fa-tasks forum-btn-icon"></i> Task Manager </button>
    <button class="forum-btn logout-btn" onclick="location.href='logout.php'"><i class="fas fa-sign-out-alt forum-btn-icon"></i> Log Out</button>
</div>



    <!-- Main Forum Area -->
    <div class="forum-content">
    <div class="forum-search">
    <div class="search-container">
        <input type="text" id="search-bar" placeholder="Search posts..." onkeyup="filterPosts()" />
        <button id="search-icon" aria-label="Search">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>

        <div class="forum-header">
            <h1>General Discussions</h1>
            <p>For anything and everything that doesn't fit in other categories</p>
        </div>

<!-- Add Search Functionality -->



<!-- Add Search Functionality -->


        <div class="user-avatar">
            <img src="<?= htmlspecialchars($_SESSION['profilePicture'] ?? 'images/default-avatar.png') ?>" alt="User Avatar" class="user-image">
            <textarea id="forum-post-text" placeholder="What's in your mind?" class="forum-post-text"></textarea>
            <button id="create-post-text" class="forum-post-btn"><i class="fas fa-paper-plane"></i> Create Post</button>
            
        </div>

        <div class="popular-hashtags">
    <h3>Popular Hashtags</h3>
    <!-- Time period selection dropdown -->
    <form method="GET" action="">
        <label for="timePeriod">Show hashtags from:</label>
        <select id="timePeriod" name="timePeriod" onchange="this.form.submit()">
            <option value="day" <?= (isset($_GET['timePeriod']) && $_GET['timePeriod'] === 'day') ? 'selected' : '' ?>>Today</option>
            <option value="week" <?= (isset($_GET['timePeriod']) && $_GET['timePeriod'] === 'week') ? 'selected' : '' ?>>This Week</option>
            <option value="month" <?= (isset($_GET['timePeriod']) && $_GET['timePeriod'] === 'month') ? 'selected' : '' ?>>This Month</option>
        </select>
    </form>

    <?php if (!empty($popularHashtags)): ?>
        <ul>
            <?php foreach ($popularHashtags as $hashtag): ?>
                <a class="hashtag-link <?= ($selectedHashtag === $hashtag['hashtag']) ? 'active-hashtag' : '' ?>" 
                   href="?hashtag=<?= urlencode($hashtag['hashtag']) ?>" 
                   data-hashtag="<?= urlencode($hashtag['hashtag']) ?>">
                    <?= htmlspecialchars($hashtag['hashtag']) ?> (<?= $hashtag['count'] ?>)
                </a>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No trending hashtags yet. Be the first to tag something!</p>
    <?php endif; ?>
</div>

<?php if (!empty($liked_posts)): ?>
    <?php foreach ($liked_posts as $post): ?>
        <div class="forum-post" id="post-<?= $post['id'] ?>">
            <div class="post-header">
                <div class="post-user-info">
                    <a href="users_info.php?user_id=<?= htmlspecialchars($post['user_id']) ?>" class="username-link">
                        <img src="<?= htmlspecialchars($post['user_image'] ?? 'images/default-avatar.png') ?>" alt="User Avatar" class="avatar-image">
                        <span class="username"><?= htmlspecialchars($post['username']) ?></span>
                    </a>
                    <span class="timestamp">posted        <?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?></span>
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
        Comment (<?php echo isset($comments[$post['id']]) ? count($comments[$post['id']]) : 0; ?>)
    </button>
    <!-- Report Button -->
    <button class="post-footer__btn report-btn" data-post-id="<?= $post['id'] ?>" onclick="openReportModal(<?= $post['id'] ?>)">
        <i class="fa-solid fa-flag"></i> Report
    </button>
</div>
<!-- Report Modal -->
<div id="report-modal-<?= $post['id'] ?>" class="report-modal" style="display: none;">
    <div class="report-modal-content">
        <!-- Close button for the modal -->
 
<!-- Report Modal -->
<div id="report-modal-<?= $post['id'] ?>" class="report-modal" style="display: none;">
    <div class="report-modal-content">
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

            <!-- Submit Button -->
            <button type="submit" class="submit-report-btn">Submit Report</button>
        </form>

        <!-- Close Button Outside the Form -->
        <button class="modal-close-btn" onclick="closeReportModal(<?= $post['id'] ?>)">Close</button>

        <div id="report-status-<?= $post['id'] ?>" class="report-status"></div> <!-- To show success/failure -->
    </div>
</div>






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
                    <!-- New Close Button -->
        <button class="modal-close-btn" onclick="closeReportModal(<?= $post['id'] ?>)">Close</button>
        
        </form>

        <div id="report-status-<?= $post['id'] ?>" class="report-status"></div> <!-- To show success/failure -->
    </div>
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
    </div>
</div>

<script> // Open the report modal
function openReportModal(postId) {
    const modal = document.getElementById(`report-modal-${postId}`);
    modal.style.display = 'flex';
}

// Close the report modal
function closeReportModal(postId) {
    const modal = document.getElementById(`report-modal-${postId}`);
    modal.style.display = 'none';
}

// Form submission (optional - if using AJAX)
document.querySelectorAll('.report-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const postId = this.querySelector('[name="post_id"]').value;
        const reason = this.querySelector('[name="reason"]').value;
        const comments = this.querySelector('[name="comments"]').value;

        // Send the data to the server via AJAX
        fetch('report_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `post_id=${postId}&reason=${reason}&comments=${comments}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Post has been reported.');
                closeReportModal(postId); // Close the modal after submission
            } else {
                alert('Error reporting post.');
            }
        })
        .catch(error => {
            alert('An error occurred.');
            console.error('Error:', error);
        });
    });
});


</script>


<script src="ForumJs/preview_image.js"></script>
<script src="ForumJs/reply_comment_like.js"></script>
<script src="ForumJs/reporting.js"></script>
<script src="ForumJs/comment_reply_delete.js"></script>
<script src="ForumJs/notification.js"></script>
<script src="ForumJs/edit_delete.js"></script>
<script src="Forum_sort/search_function.js"></script>
<script src="ForumJs/reply_to_a_reply.js"></script>
<script src="Forum_sort/sorting_function.js"></script>
<script src="ForumJs/likesFunction.js"></script>
<script src="ForumJs/noCommentRefresh.js"></script>
<script src="ForumJs/showFunction.js"></script>
<script src="ForumJs/toggleOption.js"></script>
<script src="ForumJs/deletePost.js"></script>
<script src="ForumJs/forumpost.js"></script>
<script src="ForumJs/DynamicComment.js"></script>
</body>
</html>
