<?php
session_start();

$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
if (!$uid) {
    header("Location: sign_up.php"); 
    exit();
}

if (!isset($_GET['day']) || !isset($_GET['month']) || !isset($_GET['year'])) {
    die("Invalid request.");
}

$day = $_GET['day'];
$month = $_GET['month'];
$year = $_GET['year'];

$conn = new mysqli('localhost', 'root', '', 'unilink_database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$start_date = "$year-$month-$day 00:00:00";
$end_date = "$year-$month-$day 23:59:59";

// Fetch events only for the authenticated user
$stmt = $conn->prepare("SELECT * FROM events WHERE user_id = ? AND start_time BETWEEN ? AND ?");
$stmt->bind_param("sss", $uid, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events on <?php echo "$month/$day/$year"; ?></title>
    <link rel="stylesheet" href="calendar_style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="CSS/Feature.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/curtain.css">
    <script src="Javascripts/app.js"></script>
    <script src="planner.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>

      <!--NAV BAR-->
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

<div class="sidebar" id="sidebar">
    <ul class="sidebar__menu">
        <li class="sidebar__item active"> <!-- Make this item active -->
            <a href="#" class="sidebar__link">
                <i class="fas fa-tasks"></i> <!-- Icon for PLANNER -->
                <span class="sidebar__text">PLANNER</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="CreateTask.php" class="sidebar__link">
                <i class="fas fa-plus"></i> <!-- Icon for CREATE TASK -->
                <span class="sidebar__text">CREATE TASK</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="notes.php" class="sidebar__link">
                <i class="fas fa-sticky-note"></i> <!-- Icon for NOTE -->
                <span class="sidebar__text">NOTE</span>
            </a>
        </li>
    </ul>
</div>


<!--NAV BAR END-->

    <div class="event-list-container">
        <h2>Events for <?php echo "$month/$day/$year"; ?></h2>

        <?php foreach ($events as $event): ?>
            <div class="event-item">
                <h3><?php echo $event['title']; ?></h3>
                <p><?php echo nl2br($event['description']); ?></p>
                <p><strong>Start:</strong> <?php echo date('Y-m-d H:i', strtotime($event['start_time'])); ?></p>
                
                <!-- Using Material Icons for Edit and Delete -->
                <div class="event-actions">
                    <a href="edit_event.php?event_id=<?php echo $event['event_id']; ?>" class="icon-link" title="Edit Event">
                        <span class="material-icons">edit</span>
                    </a>
                    <a href="delete_event.php?event_id=<?php echo $event['event_id']; ?>" class="icon-link" onclick="return confirmDelete();" title="Delete Event">
                        <span class="material-icons">delete</span>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Close Button -->
        <div>
            <a href="taskmanager.php?month=<?php echo $month; ?>&year=<?php echo $year; ?>" class="close-btn">Close</a>
        </div>
    </div>

    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this event?");
    }
    </script>
</body>
</html>
