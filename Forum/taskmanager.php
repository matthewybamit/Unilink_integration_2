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

$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
if (!$uid) {
   // Redirect to sign-up page if not logged in
   header("Location: sign_up.php");
   exit();
}

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$conn = new mysqli('localhost', 'root', '', 'unilink_database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$start_date = "$year-$month-01";
$end_date = date('Y-m-t', strtotime($start_date));

// Modify query to include user_id
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
    <title>Unilink</title>
    <link rel="stylesheet" href="calendar_style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="CSS/Feature.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/curtain.css">
    <link rel="stylesheet" href="Forum/notification.css">   
    <script src="Javascripts/app.js"></script>
    <script src="planner.js"></script>
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



    <div class="calendar-container">
        <header class="calendar-header">
            <h1>Calendar for <?php echo $month . '/' . $year; ?></h1>
            
            <!-- "Create Event" Button -->
            <div class="calendar-nav">
                <a href="create_event.php" class="create-event-btn">Create Event</a>
            </div>

            <div class="calendar-nav">
              <!-- Previous month link -->
              <a href="taskmanager.php?month=<?php echo ($month == 1) ? 12 : $month - 1; ?>&year=<?php echo ($month == 1) ? $year - 1 : $year; ?>" class="prev-month">Previous</a>

              <!-- Next month link -->
              <a href="taskmanager.php?month=<?php echo ($month == 12) ? 1 : $month + 1; ?>&year=<?php echo ($month == 12) ? $year + 1 : $year; ?>" class="next-month">Next</a>
            </div>
        </header>

        <div class="calendar-body">
            <table class="calendar-table">
                <thead>
                    <tr>
                        <th>Sun</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        $first_day = date('w', strtotime($start_date));
                        $days_in_month = date('t', strtotime($start_date));

                        // Output empty cells before the first day of the month
                        for ($i = 0; $i < $first_day; $i++) {
                            echo "<td></td>";
                        }

                        // Loop through each day of the month
                        for ($day = 1; $day <= $days_in_month; $day++) {
                            if (($day + $first_day - 1) % 7 == 0) {
                                echo "</tr><tr>";
                            }
                            echo "<td class='calendar-day' data-day='$day'>$day"; // Day number inside the cell
                            
                            // Show events for the day (limit to 2)
                            $event_count = 0;
                            // Display events for each day
                            foreach ($events as $event) {
                                $event_date = date('d', strtotime($event['start_time']));
                                if ($event_date == $day) {
                                    if ($event_count < 2) {
                                        // Link event title to edit page
                                        // Limit the event title to 7 characters
$title = (strlen($event['title']) > 7) ? substr($event['title'], 0, 7) . "..." : $event['title'];

echo "<div class='event' style='background-color: {$event['color']};'>
        <a href='edit_event.php?event_id={$event['event_id']}' class='event-title'>{$title}</a>
      </div>";
                                    }
                                    $event_count++;
                                }
                            }

                            // If there are more than 2 events, show "View more"
                            if ($event_count > 2) {
                                echo "<div class='view-more'>";
                                echo "<a href='view_events.php?day=$day&month=$month&year=$year' title='View more events'>...</a>";
                                echo "</div>";
                            }
                            echo "</td>";
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete this event?");
    } 
    </script>
<script src="ForumJs/notification.js"></script>
</body>
</html>
