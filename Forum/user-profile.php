<?php
session_start();

// Check if user is logged in by checking session data
if (!isset($_SESSION['username'])) {
    // Redirect to login page if user is not logged in
    header("Location: sign_up.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="CSS/curtain.css">
    <link rel="stylesheet" href="CSS/profile.css">
    <script src="Javascripts/app.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar__container">
        <a href="unilink.php" class="nav__logo">
            <img src="images/unilink_logo.png" alt="">
        </a>
        <div class="seperator__line"></div>
        <ul class="nav__menu">
            <li class="nav__items">
                <a href="Forum.php" class="nav__links">
                    <i class="fas fa-comments"></i>
                    <span class="nav__text">Forum</span>
                </a>
                <a href="#" class="nav__links">
                    <i class="fas fa-blog"></i>
                    <span class="nav__text">Blog</span>
                </a>
                <a href="Taskmanager.html" class="nav__links">
                    <i class="fa-solid fa-book"></i>
                    <span class="nav__text">QuizCU</span>
                </a>
                <a href="user-profile.php" class="nav__links">
                    <i class="fas fa-user"></i>
                    <span class="nav__text">User</span>
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

<div id="curtainMenu" class="curtain-menu">
    <a href="">Home</a>
    <a href="services.html">Services</a>
    <a href="#">About</a>
    <a href="#">Contact</a>
</div>

<!-- User Profile Layout -->
<div class="profile-container">
    <div class="profile-header">
        <div class="profile-info">
            <img id="profilePicture" class="profile-picture" src="<?php echo $_SESSION['profilePicture']; ?>" alt="Profile Picture" />
            <h2 id="username"><?php echo $_SESSION['username']; ?></h2>
            <p id="email"><?php echo $_SESSION['email']; ?></p>
            <p class="join-date" id="joinDate">Joined on April 14th, 2018</p> <!-- You can customize this -->
            
            <div class="social-links">
            
            </div>
        </div>
    </div>

    <div class="profile-tabs">
        <button class="tab-btn active onclick="location.href='user-profile.php'>About</button>
        <button class="tab-btn" onclick="location.href='user_posts.php?user_id=<?= $_SESSION['uid'] ?>'">Posts</button>
        <button class="tab-btn" onclick="location.href='taskmanager.php?user_id=<?= $_SESSION['uid']?>'">Taskmanager</button>
        <button id="logoutButton">Log Out</button>
    </div>
    <div class="profile-container">


</div>



<script>
    // Log out functionality
    document.getElementById('logoutButton').addEventListener('click', () => {
        // Make an AJAX request to logout.php to clear the PHP session
        fetch('logout.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear session storage
                    sessionStorage.clear();
                    // Redirect back to sign-in page
                    window.location.href = 'unilink.php';
                } else {
                    console.error('Error logging out.');
                }
            })
            .catch(error => console.error('Error logging out: ', error));
    });
</script>

</body>
</html>
