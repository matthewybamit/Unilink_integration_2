<?php
include 'Forum_action.php';
include 'post_edit.php';
include 'db_connect.php';  // Include your database connection

// Fetch news titles and image URLs
$stmt = $pdo->prepare("SELECT news_id, title, image_url FROM news WHERE status = 'Published' ORDER BY date_published DESC");
$stmt->execute();
$newsItems = $stmt->fetchAll();


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
// Fetch news details based on the ID passed from the previous page
if (isset($_GET['id'])) {
    $news_id = $_GET['id'];

    // Prepare the SQL query
    $stmt = $pdo->prepare("SELECT * FROM news WHERE news_id = :news_id");
    $stmt->bindParam(':news_id', $news_id);
    $stmt->execute();
    $newsItem = $stmt->fetch();

    if (!$newsItem) {
        echo "<p>News not found!</p>";
        exit;
    }
} else {
    echo "<p>Invalid news item!</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($newsItem['title']) ?></title>
    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="CSS/Feature.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/carousel.css">
    <link rel="stylesheet" href="CSS/landing.css">
    <link rel="stylesheet" href="CSS/curtain.css">
    <link rel="stylesheet" href="CSS/fullevents.css">
    <link rel="stylesheet" href="Forum/notification.css">   
    <link rel="stylesheet" href="CSS/quick-links.css">
    <script src="Javascripts/app.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
/* Main Wrapper */
/* Main Wrapper */
.wrapper-news {
    width: 100%;
    margin-top: 110px;
    overflow: hidden;
    position: relative;

  
}
/* Hero Image Section with Adjusted Size */
.new_image {
    width: 100%; /* Make image width responsive to container */
    height: 350px; /* Fixed height to control the size */
    object-fit: cover; /* Ensures the image fills the area without distorting */
 
    margin-bottom: 20px;
}

/* Title on Image */
.wrapper-news h1 {
    font-size: 2.5rem;
    color: #333;
    text-align: center;
    margin-top: 10px;
}

/* Author & Date Section */
.author, .date {
    font-size: 1.1em;
    color: #7f8c8d;
    text-align: center;
    margin-top: 10px;
}

.author {
    font-weight: bold;
}

.date {
    font-style: italic;
}

/* Content Area */
.content {
    padding: 30px;
    background-color: #f9f9f9; /* Light background for content */
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    margin-top: 20px;
    line-height: 1.8;
    color: #333;
}

.content p {
    margin-bottom: 15px; /* Spacing between paragraphs */
}

/* Back to News Link */
.back-link {
    display: inline-block;
    background-color: #3498db;
    color: white;
    padding: 15px 30px;
    border-radius: 40px;
    text-decoration: none;
    font-size: 1.1em;
    text-align: center;
    margin-top: 30px;
    transition: transform 0.3s ease, background-color 0.3s ease;
    position: relative;
    left: 50%;
    transform: translateX(-50%);
}

.back-link:hover {
    background-color: #2980b9;
    transform: translateX(-50%) scale(1.05);
}

/* Media Query for Smaller Screens */
@media (max-width: 768px) {
    .new_image {
        max-height: 300px; /* Adjust for smaller screens */
    }

    .wrapper-news h1 {
        font-size: 2rem; /* Adjust title size */
        margin-top: 15px;
    }

    .content {
        padding: 15px;
        font-size: 1em; /* Adjust font size */
    }

    .back-link {
        font-size: 1em;
        padding: 10px 20px;
    }
}



  
    </style>
</head>
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
    <a href="">Home</a>
    <a href="services.html">Services</a>
    <a href="#">About</a>   
    <a href="#">Contact</a>
</div>
<!-- Main Wrapper -->
<div class="wrapper-news">

    <!-- Article Title -->
 

    <!-- Article Image -->
    <img class="new_image" src="<?= htmlspecialchars($newsItem['image_url']) ?>" alt="<?= htmlspecialchars($newsItem['title']) ?>">
    <h1><?= htmlspecialchars($newsItem['title']) ?></h1>

    <!-- Article Content -->
    <div class="content">
        <?= nl2br(htmlspecialchars($newsItem['content'])) ?>
    </div>
    <!-- Author & Date -->
    <p class="author"><strong>By:</strong> <?= htmlspecialchars($newsItem['author']) ?></p>
    <p class="date"><em>Published on: <?= htmlspecialchars($newsItem['date_published']) ?></em></p>

    <!-- Back to News Link -->
    <a href="unilink.php" class="back-link">Back to News</a>

</div>

</body>
</html>
