
<?php
session_start();

// Check if the user is logged in by verifying if 'uid' is set in the session
if (!isset($_SESSION['uid'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    header("Location: sign_up.php");
    exit; // Stop further execution
}

$userId = $_SESSION['uid'];  // Get user ID from session

// Generate a unique token for form submission if it doesn’t exist
if (!isset($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}

// Database connection settings
$host = 'localhost';
$dbname = 'unilink_database';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Function to fetch notifications
function fetchNotifications($user_id) {
    global $pdo;

    $notifications = [];

    // Fetch new likes on user's posts
    $stmt = $pdo->prepare("
        SELECT fp.id AS post_id, fp.content AS post_content, fp.image AS post_image, COUNT(pl.id) AS like_count
        FROM forum_posts fp
        LEFT JOIN post_likes pl ON fp.id = pl.post_id
        WHERE fp.user_id = :user_id
          AND (pl.created_at > COALESCE((SELECT last_checked FROM users WHERE id = :user_id), '0000-00-00 00:00:00'))
        GROUP BY fp.id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $notifications['likes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch new comments on user's posts
    $stmt = $pdo->prepare("
        SELECT fp.id AS post_id, fp.content AS post_content, fp.image AS post_image, COUNT(c.id) AS comment_count
        FROM forum_posts fp
        LEFT JOIN comments c ON fp.id = c.post_id
        WHERE fp.user_id = :user_id
          AND (c.created_at > COALESCE((SELECT last_checked FROM users WHERE id = :user_id), '0000-00-00 00:00:00'))
        GROUP BY fp.id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $notifications['comments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch new replies to user's comments
    $stmt = $pdo->prepare("
        SELECT c.id AS comment_id, c.content AS comment_content, COUNT(r.id) AS reply_count
        FROM comments c
        LEFT JOIN replies r ON c.id = r.comment_id
        WHERE c.user_id = :user_id
          AND (r.reply_created_at > COALESCE((SELECT last_checked FROM users WHERE id = :user_id), '0000-00-00 00:00:00'))
        GROUP BY c.id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $notifications['replies'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch likes on user's comments
    $stmt = $pdo->prepare("
        SELECT c.id AS comment_id, c.content AS comment_content, COUNT(cl.id) AS like_count
        FROM comments c
        LEFT JOIN comment_likes cl ON c.id = cl.comment_id
        WHERE c.user_id = :user_id
          AND (cl.created_at > COALESCE((SELECT last_checked FROM users WHERE id = :user_id), '0000-00-00 00:00:00'))
        GROUP BY c.id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $notifications['comment_likes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch likes on user's replies
    $stmt = $pdo->prepare("
        SELECT r.id AS reply_id, r.content AS reply_content, COUNT(rl.id) AS like_count
        FROM replies r
        LEFT JOIN reply_likes rl ON r.id = rl.reply_id
        WHERE r.user_id = :user_id
          AND (rl.created_at > COALESCE((SELECT last_checked FROM users WHERE id = :user_id), '0000-00-00 00:00:00'))
        GROUP BY r.id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $notifications['reply_likes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total notifications
    $totalNotifications = 0;
    foreach (['likes', 'comments', 'replies', 'comment_likes', 'reply_likes'] as $key) {
        if (!empty($notifications[$key])) {
            $totalNotifications += count($notifications[$key]);
        }
    }

    // Add total notification count to the response
    $notifications['totalCount'] = $totalNotifications;

    return $notifications;
}

// Function to update last checked timestamp
function updateLastChecked($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET last_checked = NOW() WHERE id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
}

// Check if notifications are requested
if (isset($_GET['fetch_notifications']) && isset($_SESSION['uid'])) {
    $user_id = $_SESSION['uid'];
    $notifications = fetchNotifications($user_id);
    updateLastChecked($user_id); // Update last checked time after fetching
    echo json_encode($notifications); // Return notifications as JSON
    exit;
}

// Mark notifications as viewed (for POST requests)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['uid']) && isset($_POST['mark_seen'])) {
    $user_id = $_SESSION['uid'];

    // Update the last viewed timestamp
    $stmt = $pdo->prepare("UPDATE users SET last_viewed_notifications_at = NOW() WHERE id = ?");
    $stmt->execute([$user_id]);

    echo json_encode(['status' => 'success', 'message' => 'Notifications marked as viewed.']);
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
    $notificationCount += count($notifications['replies']);
    $notificationCount += count($notifications['comment_likes']);
    $notificationCount += count($notifications['reply_likes']);
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="CSS/curtain.css">
    <link rel="stylesheet" href="Forum/notification.css">
    <script src="Javascripts/app.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>

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


<script>
document.addEventListener('DOMContentLoaded', () => {
    const notificationBell = document.getElementById('notification-bell');
    const notificationDropdown = document.getElementById('notification-dropdown');
    const notificationCountElement = document.querySelector('.notification-count'); // Notification count element
    const notificationBody = document.getElementById('notification-body');

    function fetchNotifications() {
        fetch('Forum_action.php?fetch_notifications=true')
            .then(response => response.json())
            .then(data => {
                // Clear old notifications
                notificationBody.innerHTML = '';

                if (data.error) {
                    notificationBody.innerHTML = `<p>${data.error}</p>`;
                    return;
                }

                // Display notifications
                let notificationHtml = '';

                // Likes on posts
                if (data.likes && data.likes.length) {
                    notificationHtml += '<h4>New Likes on Posts:</h4>';
                    data.likes.forEach(like => {
                        notificationHtml += `
                            <div class="notification-item" data-post-id="${like.post_id}">
                                <p>Post: "${like.post_content}" received ${like.like_count} new like(s).</p>
                                ${like.post_image ? `<img src="${like.post_image}" alt="Post Image" style="width: 50px; height: 50px; object-fit: cover;">` : ''}
                                <a href="post-details.php?id=${like.post_id}" class="view-post-link">View Post</a>
                            </div>
                        `;
                    });
                }

                // New comments
                if (data.comments && data.comments.length) {
                    notificationHtml += '<h4>New Comments:</h4>';
                    data.comments.forEach(comment => {
                        notificationHtml += `
                            <div class="notification-item" data-post-id="${comment.post_id}">
                                <p>Post: "${comment.post_content}" received ${comment.comment_count} new comment(s).</p>
                                ${comment.post_image ? `<img src="${comment.post_image}" alt="Post Image" style="width: 50px; height: 50px; object-fit: cover;">` : ''}
                                <a href="post-details.php?id=${comment.post_id}" class="view-post-link">View Post</a>
                            </div>
                        `;
                    });
                }

                // New replies
                if (data.replies && data.replies.length) {
                    notificationHtml += '<h4>New Replies:</h4>';
                    data.replies.forEach(reply => {
                        notificationHtml += `
                            <div class="notification-item" data-comment-id="${reply.comment_id}">
                                <p>Your comment: "${reply.comment_content}" received ${reply.reply_count} new reply(ies).</p>
                                <a href="post-details.php?id=${reply.comment_id}" class="view-post-link">View Post</a>
                            </div>
                        `;
                    });
                }

                // Likes on comments
                if (data.comment_likes && data.comment_likes.length) {
                    notificationHtml += '<h4>New Likes on Comments:</h4>';
                    data.comment_likes.forEach(like => {
                        notificationHtml += `
                            <div class="notification-item" data-comment-id="${like.comment_id}">
                                <p>Your comment: "${like.comment_content}" received ${like.like_count} new like(s).</p>
                            </div>
                        `;
                    });
                }

                // Likes on replies
                if (data.reply_likes && data.reply_likes.length) {
                    notificationHtml += '<h4>New Likes on Replies:</h4>';
                    data.reply_likes.forEach(like => {
                        notificationHtml += `
                            <div class="notification-item" data-reply-id="${like.reply_id}">
                                <p>Your reply: "${like.reply_content}" received ${like.like_count} new like(s).</p>
                            </div>
                        `;
                    });
                }

                if (!notificationHtml) {
                    notificationHtml = '<p>No new notifications.</p>';
                }

                notificationBody.innerHTML = notificationHtml;

                // Update the notification count if new notifications are present
                updateNotificationCount(data.totalCount);
            })
            .catch(error => {
                console.error(error);
                notificationBody.innerHTML = '<p>Error fetching notifications. Please try again later.</p>';
            });
    }

    function updateNotificationCount(count) {
        const lastViewedCount = parseInt(localStorage.getItem('lastViewedNotificationCount')) || 0;

        // Compare fetched count and last viewed count
        const newNotifications = count - lastViewedCount;

        if (newNotifications > 0) {
            // Show notification count only if there are new notifications
            notificationCountElement.textContent = newNotifications; // Show new notifications count
            notificationCountElement.style.display = 'inline';
        } else {
            // Hide the notification count if there are no new notifications
            notificationCountElement.style.display = 'none';
        }

        // Always save the total count in `localStorage`
        localStorage.setItem('lastFetchedNotificationCount', count);
    }

    // Mark notifications as viewed
    function markNotificationsAsViewed() {
        const currentCount = parseInt(localStorage.getItem('lastFetchedNotificationCount')) || 0;
        localStorage.setItem('lastViewedNotificationCount', currentCount); // Update the last viewed count

        // Update count display accordingly
        notificationCountElement.style.display = 'none'; // Hide the count
    }

    // Toggle the notification dropdown
    notificationBell.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent default link behavior

        // Toggle dropdown visibility
        notificationDropdown.style.display = notificationDropdown.style.display === 'none' ? 'block' : 'none';

        // Mark notifications as viewed only after showing them
        if (notificationDropdown.style.display === 'block') {
            fetchNotifications();
            markNotificationsAsViewed(); // Mark as viewed after displaying
        }
    });

    // Periodically fetch notifications to check for new updates
    setInterval(fetchNotifications, 60000); // Refresh notifications every minute
});


</script>

</body>
</html>
