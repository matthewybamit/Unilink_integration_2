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
// Set the number of news items per page
$newsPerPage = 5;

// Get the current page number from the query string (default is page 1)
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting index for the SQL query
$startFrom = ($currentPage - 1) * $newsPerPage;

// Fetch news items for the current page
$stmt = $pdo->prepare("SELECT * FROM news ORDER BY date_published DESC LIMIT ?, ?");
$stmt->bindParam(1, $startFrom, PDO::PARAM_INT);
$stmt->bindParam(2, $newsPerPage, PDO::PARAM_INT);
$stmt->execute();
$newsItems = $stmt->fetchAll();

// Get the total number of news items to calculate the total pages
$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM news");
$stmtTotal->execute();
$totalNews = $stmtTotal->fetchColumn();
$totalPages = ceil($totalNews / $newsPerPage);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="CSS/Feature.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/carousel.css">
    <link rel="stylesheet" href="CSS/landing.css">
    <link rel="stylesheet" href="CSS/curtain.css">
    <link rel="stylesheet" href="CSS/fullevents.css">
    <link rel="stylesheet" href="Forum/notification.css">   
    <link rel="stylesheet" href="CSS/quick-links.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<style> 

footer {
    background: #16222A;
    background: linear-gradient(59deg, #1b0e60, #16222A);
    color: white;
    padding: 20px 0;
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}
    /* News Section */
.news-section {
    padding: 40px 20px;
    background-color: #f5f5f5;
    text-align: center;
}

h2 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 30px;
}

/* News Grid */
.news-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    justify-items: center;
    margin-bottom: 40px;
}

.news-card {
    width: 100%;
    max-width: 280px;
    background-color: #ffffff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.news-card:hover {
    transform: scale(1.05);
}

.news-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.news-content {
    padding: 15px;
}

.news-content h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.news-content a {
    color: #3498db;
    text-decoration: none;
    font-size: 1.1em;
}

.news-content a:hover {
    text-decoration: underline;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
}

.pagination a {
    padding: 10px 20px;
    background-color: #3498db;
    color: white;
    border-radius: 30px;
    text-decoration: none;
    font-size: 1em;
    transition: background-color 0.3s ease;
}

.pagination a:hover {
    background-color: #2980b9;
}

.pagination .active {
    background-color: #2980b9;
}

.pagination .prev-page, .pagination .next-page {
    font-weight: bold;
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
    <a href="">Home</a>
    <a href="services.html">Services</a>
    <a href="#">About</a>   
    <a href="#">Contact</a>
</div>
<!--NAV BAR END-->
<!-- News Section -->
<div class="news-section">
    <h2>Latest News</h2>
    <div class="news-container">
        <?php foreach ($newsItems as $news): ?>
        <div class="news-card">
            <img src="<?= htmlspecialchars($news['image_url']) ?>" alt="<?= htmlspecialchars($news['title']) ?>" class="news-image">
            <div class="news-content">
                <h3><?= htmlspecialchars($news['title']) ?></h3>
                <p><a href="news-details.php?id=<?= $news['news_id'] ?>">Read More</a></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="news.php?page=<?= $currentPage - 1 ?>" class="prev-page">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="news.php?page=<?= $i ?>" class="page-number <?= ($i == $currentPage) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="news.php?page=<?= $currentPage + 1 ?>" class="next-page">Next</a>
        <?php endif; ?>
    </div>
</div>

<footer class="mainfooter" role="contentinfo">
    <div class="footer__middle">
        <div class="footer__container">
        
            <!--Logo Column-->
            <div class="footer__column footer__logo">
                <img src="images/Logo.png" alt="Company Logo" class="footer__logo-image">
            </div>

            <!--Column2-->
            <div class="footer__column">
                <div class="footer__pad">
                    <h4>Support</h4>
                    <ul class="list__unstyled">
                        <li><a href="#">Website Tutorial</a></li>
                        <li><a href="#">Accessibility</a></li>
                        <li><a href="#">Disclaimer</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Webmaster</a></li>
                    </ul>
                </div>
            </div>

            <!--Column3-->
            <div class="footer__column">
                <div class="footer__pad">
                    <h4>Disclaimer</h4>
                    <ul class="list__unstyled">
                        <li><a href="#">Parks and Recreation</a></li>
                        <li><a href="#">Public Works</a></li>
                        <li><a href="#">Police Department</a></li>
                        <li><a href="#">Fire</a></li>
                        <li><a href="#">Mayor and City Council</a></li>
                    </ul>
                </div>
            </div>

            <!--Socials-->
            <div class="footer__column">
                <h4>Follow Us</h4>
                <ul class="social__network social-circle">
                    <li>
                        <a href="#" class="icoFacebook" title="Facebook">
                            <img src="images/Facebook.png" alt="Facebook" class="social__image">
                        </a>
                    </li>
                    <li>
                        <a href="#" class="icoInstagram" title="Instagram">
                            <img src="images/Instagram.png" alt="Instagram" class="social__image">
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Address and Phone Section -->
        <div class="footer__address text__center">
            <p>
                <i class="fas fa-map-marker-alt"></i> Phase 6, Package 1, Area D, Barangay 178, Camarin, Caloocan City
            </p>
            <p>
                <i class="fas fa-phone"></i> +63 912 345 6789
            </p>
        </div>

        <!-- Copyright Section -->
        <div class="footer__row">
            <div class="footer__copy">
                <p class="text__center">&copy; Copyright 2024 - Vicente Malapitan Senior High School. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>


<script src="ForumJs/notification.js"></script>
</body>
</html>
