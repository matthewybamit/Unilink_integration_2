<?php

/** @var Connection $connection */
$connection = require_once "notes_pdo.php";
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
// Check if the user is logged in
if (!isset($_SESSION["uid"])) {
    // Redirect to sign-up page if not logged in
    header("Location: sign_up.php");
    exit();
}

// Retrieve search query (if any)
$searchQuery = isset($_GET["search"]) ? $_GET["search"] : "";

// Fetch notes for the logged-in user based on search query
$notes = $connection->getNotes($_SESSION["uid"], $searchQuery);

$currentNote = [
    "id" => "",
    "subject" => "",
    "content" => "",
];

// Fetch a specific note by its ID (if provided)
if (isset($_GET["id"])) {
    $currentNote = $connection->getNoteById($_GET["id"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Unilink</title>

    <!-- Stylesheets for various components -->
    <link rel="stylesheet" href="notes_style.css">
    <link rel="stylesheet" href="CSS/notes.css">
    <link rel="stylesheet" href="CSS/unistyle.css">
    <link rel="stylesheet" href="CSS/Feature.css">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/Planner.css">
    <link rel="stylesheet" href="CSS/curtain.css">
    <link rel="stylesheet" href="Forum/notification.css">   
    <!-- External JS and CSS Libraries -->
    <script src="Javascripts/app.js"></script>
    <script src="planner.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css">

    <!-- Inline CSS for Modal -->
    <style>
        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Fixed position */
            z-index: 1000; /* On top */
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto; /* Enable scrolling */
            background-color: rgba(0, 0, 0, 0.4); /* Black with opacity */
        }

        .modal-content h5 {
            font-size: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
            display: block;
        }

        .close-btn {
            float: right;
            font-size: 30px;
            cursor: pointer;
            position: absolute;
            background: transparent;
            right: 0;
            top: -35px;
        }
    </style>

    <!-- JS for Modal and Deletion Confirmation -->
    <script>
        function confirmDeletion(event) {
            if (!confirm("Are you sure?")) {
                event.preventDefault(); // Prevent the form submission
            }
        }

        function openModal(noteId) {
            document.getElementById('modal').style.display = 'block';
            // If editing an existing note, populate the modal
            if (noteId) {
                document.getElementById('noteId').value = noteId;
                document.getElementById('noteSubject').value = document.getElementById('subject-' + noteId).innerText;
                document.getElementById('noteContent').value = document.getElementById('content-' + noteId).innerText;
            } else {
                document.getElementById('noteId').value = '';
                document.getElementById('noteSubject').value = '';
                document.getElementById('noteContent').value = '';
            }
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }
    </script>
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

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <ul class="sidebar__menu">
        <li class="sidebar__item">
            <a href="taskmanager.php" class="sidebar__link">
                <i class="fas fa-tasks"></i>
                <span class="sidebar__text">PLANNER</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="CreateTask.php" class="sidebar__link">
                <i class="fas fa-plus"></i>
                <span class="sidebar__text">CREATE TASK</span>
            </a>
        </li>
        <li class="sidebar__item active">
            <a href="notes.php" class="sidebar__link">
                <i class="fas fa-sticky-note"></i>
                <span class="sidebar__text">NOTE</span>
            </a>
        </li>
    </ul>
</div>

<div class="bgg">
    <div class="top-bar">
        <button onclick="openModal()">+ Add Note</button>
        <div class="search-input">
            <i class="fa-regular fa-magnifying-glass"></i>
            <input type="search" name="search" placeholder="Search..." id="taskSearch" value="<?php echo htmlspecialchars($searchQuery); ?>" />
        </div>
    </div>

    <hr>

    <div class="notes">
        <?php foreach ($notes as $note): ?>
            <div class="note">
                <div class="subject" id="subject-<?php echo $note["id"]; ?>">
                    <a href="javascript:void(0);" onclick="openModal(<?php echo $note["id"]; ?>)">
                        <?php echo htmlspecialchars($note["subject"]); ?>
                    </a>
                </div>
                <div class="content" id="content-<?php echo $note["id"]; ?>">
                    <?php echo htmlspecialchars($note["content"]); ?>
                </div>
                <small><?php echo date("d/m/Y H:i", strtotime($note["created_at"])); ?></small>
                <form action="notes_delete.php" method="post" onsubmit="confirmDeletion(event)">
                    <input type="hidden" name="id" value="<?php echo $note["id"]; ?>">
                    <button type="submit" class="close">X</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal for adding/editing notes -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span onclick="closeModal()" style="float:right;cursor:pointer;">&times;</span>
        <h5>Create a Note</h5>
        <form class="new-note" action="notes_create.php" method="post">
            <input type="hidden" id="noteId" name="id" value="<?php echo $currentNote["id"]; ?>">
            <label for="task-name">Note Subject</label>
            <input type="text" id="noteSubject" name="subject" autocomplete="off" required maxlength="25">
            <label for="task-name">Note Content</label>
            <textarea id="noteContent" name="content" cols="60" rows="10"></textarea>
            <button type="submit">Save Note</button>
        </form>
    </div>
</div>

<script src="ForumJs/notification.js"></script>

</body>

<script>
// Search functionality to filter notes based on input
document.getElementById("taskSearch").addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();  // Get the search term
    const notes = document.querySelectorAll('.note');  // Get all the notes

    notes.forEach(note => {
        const subject = note.querySelector('.subject').textContent.toLowerCase();
        const content = note.querySelector('.content').textContent.toLowerCase();

        // Show or hide note based on search term
        if (subject.includes(searchTerm) || content.includes(searchTerm)) {
            note.style.display = '';  // Show the note
        } else {
            note.style.display = 'none';  // Hide the note
        }
    });
});
</script>

</html>
