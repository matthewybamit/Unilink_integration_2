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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unilink</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <a href="">Home</a>
    <a href="services.html">Services</a>
    <a href="#">About</a>   
    <a href="#">Contact</a>
</div>
<!--NAV BAR END-->

<div class="carousel">
    <div class="carousel-slide" id="carouselSlide">
        <div class="carousel-content">
            <div class="carousel-text">
                <h1>Welcome to Vicente Malapitan Senior High School</h1>
                <p>Late-start classes are still available. Register now.</p>
                <a href="#" class="btn-apply">Apply</a>
            </div>
            <img src="https://images.pexels.com/photos/8197535/pexels-photo-8197535.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                alt="Students Image" class="carousel-img">
        </div>
        <div class="carousel-content">
            <div class="carousel-text">
                <h1>Achieve Your Dreams</h1>
                <p>Explore our programs and make your future brighter.</p>
                <a href="#" class="btn-apply">Learn More</a>
            </div>
            <img src="https://images.pexels.com/photos/9489765/pexels-photo-9489765.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                alt="Campus Image" class="carousel-img">
        </div>
        <!-- Add more slides as needed -->
    </div>

    <!-- Dots for navigation -->
    <div class="dots">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
    </div>
</div>

<script>
let slideIndex = 0;
const slides = document.querySelectorAll('.carousel-content');
const totalSlides = slides.length;
const carouselSlide = document.getElementById('carouselSlide');
const dots = document.querySelectorAll('.dot');

function showSlides() {
    if (slideIndex >= totalSlides) { 
        slideIndex = 0; 
    } else if (slideIndex < 0) {
        slideIndex = totalSlides - 1;
    }
    
    carouselSlide.style.transform = `translateX(${-slideIndex * 50}%)`;
    
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === slideIndex);
    });
}

function nextSlide() {
    slideIndex++;
    showSlides();
}

function currentSlide(n) {
    slideIndex = n - 1;
    showSlides();
}

// Initial display of slides
showSlides();

// Auto-slide every 5 seconds
let autoSlide = setInterval(nextSlide, 5000);

// Stop auto-slide on hover and resume after hover
const carousel = document.querySelector('.carousel');
carousel.addEventListener('mouseover', () => {
    clearInterval(autoSlide);
});
carousel.addEventListener('mouseout', () => {
    autoSlide = setInterval(nextSlide, 2000);
});
</script>

<!-- New Section Between Carousel and Background -->
<div class="quick-links">
    <h2>"Quick Links"</h2>
    <div class="links-container">
        <div class="link-card">
            <h3>Student Portal</h3>
            <p>Learn about the Student portal process and requirements.</p>
            <a href="../../Unilink_integration_2/SIS2/student_information_system-main/students/stud.homepage.php">Read More</a>
        </div>
        <div class="link-card">
            <h3>Curriculum</h3>
            <p>Explore our comprehensive curriculum designed for success.</p>
            <a href="#">Read More</a>
        </div>
        <div class="link-card">
            <h3>Events Calendar</h3>
            <p>Stay updated with upcoming school events and activities.</p>
            <a href="#">View Calendar</a>
        </div>
    </div>
</div>

<div class="bg">
    <div class="title0">
        <h1>About Vicente Malapitan Senior High School</h1>   
    </div>
    
    <div class="container1">
        <div class="descrip hidden-left">
            <p>
                Vicente Malapitan Senior High School is dedicated to providing a comprehensive and innovative education that empowers students to reach their full potential. With a focus on academic excellence, our school offers a variety of programs designed to foster critical thinking, creativity, and lifelong learning. Our dedicated faculty and staff are committed to nurturing a supportive and inclusive environment where every student feels valued and inspired to succeed. 
                <br><br>
                Located in the heart of Caloocan City, our state-of-the-art facilities include modern classrooms, science and computer laboratories, and spacious recreational areas. We believe in a holistic approach to education, integrating academic rigor with extracurricular activities that promote personal growth and community engagement. 
               
            </p>
        </div>
        <div class="image hidden-right">
            <img src="images/Student_sitting.png" alt="Placeholder Image">
        </div>
    </div>
</div>




<div class="news-section">
    <img src="images/Building.png" alt="Background Image" class="background-image">

    <h2>WHAT'S HAPPENING AT VMSH?</h2>
    <div class="news-container">
        <?php foreach ($newsItems as $news): ?>
        <div class="news-card">
            <img src="<?= $news['image_url'] ?>" alt="<?= htmlspecialchars($news['title']) ?>" class="news-image">
            <div class="news-content">
                <h3><?= htmlspecialchars($news['title']) ?></h3>
                <p><a href="news-details.php?id=<?= $news['news_id'] ?>">Read More</a></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <a class="more-news" href="news.php">More News</a> <!-- Link to a page that shows more news -->
</div>




 <!-- Main content 
<div class="news-section">
  
    <img src="images/Building.png" alt="Background Image" class="background-image">

   
    <h2>WHAT'S HAPPENING AT VMSH?</h2>
    <div class="news-container">
        <div class="news-card">
            <img src="https://via.placeholder.com/300" alt="Event 1">
            <div class="news-content">
                <h3>Labor extensive work for the new construction of buildings</h3>
                <p>Read More</p>
            </div>
        </div>
        <div class="news-card">
            <img src="https://via.placeholder.com/300" alt="Event 2">
            <div class="news-content">
                <h3>Does a career in Information Technology interest you?</h3>
                <p>Read More</p>
            </div>
        </div>
        <div class="news-card">
            <img src="https://via.placeholder.com/300" alt="Event 3">
            <div class="news-content">
                <h3>Find out what is new in our School!</h3>
                <p>Read More</p>
            </div>
        </div>
    </div>
    <a class="more-news">More News</a>
</div>-->


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
