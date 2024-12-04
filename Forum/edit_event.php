<?php
session_start();

 $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
 if (!$uid) {
    // Redirect to sign-up page if not logged in
 header("Location: sign_up.php");
 exit();
 }


// Check if event_id is provided
if (!isset($_GET['event_id'])) {
    die("Event ID not provided.");
}

$event_id = $_GET['event_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'unilink_database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch event data
$stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("Event not found.");
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
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Body styling */
    body {
        background-color: #f4f4f9;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    /* Container styling */
    .container {
        max-width: 1000px;
        margin-top: 150px;
        margin-left: 409px;
        padding: 30px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    /* Heading styling */
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    /* Form-group spacing */
    .form-group {
        margin-bottom: 15px;
    }

    /* Input field and textarea styling */
    input[type="text"],
    input[type="datetime-local"],
    textarea,
    input[type="color"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    /* Textarea height and resizing */
    textarea {
        height: 150px;
        resize: vertical;
    }

    /* Button styling */
    button {
        width: 100%;
        padding: 12px;
        border-radius: 5px;
        border: none;
        font-size: 16px;
        cursor: pointer;
        background-color: #4CAF50;
        color: white;
        margin-top: 20px;
    }

    button:hover {
        background-color: #45a049;
    }

    /* Close button styling */

        .close-btn {
            background-color: #9e9e9e;
            color: white;
        }
    

    .close-btn:hover {
        background-color: #9e9999;
    }

    /* Delete button styling */
    .delete-btn {
        background-color: #f44336;
        margin-top: 10px;
    }

    .delete-btn:hover {
        background-color: #e53935;
    }
</style>
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

    <div class="container">
        <h2>Edit Event</h2>
        <form action="update_event.php" method="POST">
            <div class="form-group">
                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['event_id']); ?>">
            </div>
            <div class="form-group">
                <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" placeholder="Event Title" required>
            </div>
            <div class="form-group">
                <textarea name="description" placeholder="Event Description"><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>
            <div class="form-group">
                <input type="datetime-local" name="start_time" value="<?php echo date('Y-m-d\TH:i', strtotime($event['start_time'])); ?>" required>
            </div>
            <div class="form-group">
                <label for="color">Choose Event Color:</label>
                <input type="color" id="color" name="color" value="<?php echo htmlspecialchars($event['color']); ?>">
            </div>
            <button type="submit">Update Event</button>
        </form>

        <!-- Form for deleting the event -->
        <form action="delete_event.php" method="GET" onsubmit="return confirm('Are you sure you want to delete this event?');">
            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['event_id']); ?>">
            <button type="submit" class="delete-btn">Delete Event</button>
        </form>

        <form action="taskmanager.php" method="GET">
            <button type="submit" class="close-btn">Close</button>
        </form>
    </div>
</body>
</html>
