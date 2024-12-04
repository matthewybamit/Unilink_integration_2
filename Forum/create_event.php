    <?php
    session_start();

    $uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
    if (!$uid) {
       // Redirect to sign-up page if not logged in
    header("Location: sign_up.php");
    exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $start_time = $_POST['start_time'];
        $color = $_POST['color'];

        $conn = new mysqli('localhost', 'root', '', 'unilink_database');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Include user_id in the query
        $stmt = $conn->prepare("INSERT INTO events (user_id, title, description, start_time, color) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $uid, $title, $description, $start_time, $color);

        if ($stmt->execute()) {
            header("Location: taskmanager.php");
            exit();  
        } else {
            echo "Error executing query: " . $stmt->error;
        }
        

        $stmt->close();
        $conn->close();
    }
    ?>


    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Event</title>
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

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background-color: #f4f4f9;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            h2 {
                text-align: center;
                margin-bottom: 20px;
                color: #333;
            }

            .form-group {
                margin-bottom: 15px;
            }

            input[type="text"],
            input[type="datetime-local"],
            textarea,
            input[type="color"] {
                width: 100%;
                padding: 10px;
                margin-top: 5px;
                border-radius: 5px;
                border: 1px solid #ccc;
                font-size: 16px;
            }

            textarea {
                height: 150px;
                resize: vertical;
            }

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

            .close-btn {
                background-color: #9e9e9e;
                color: white;
            }
        

        .close-btn:hover {
            background-color: #9e9999;
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
                <a href="taskmanager.php" class="nav__links">
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
            <h2>Create Event</h2>
            <form action="create_event.php" method="POST">
                <div class="form-group">
                    <input type="text" name="title" placeholder="Event Title" required>
                </div>
                <div class="form-group">
                    <textarea name="description" placeholder="Event Description"></textarea>
                </div>
                <div class="form-group">
                    <input type="datetime-local" name="start_time" required>
                </div>
                <div class="form-group">
                    <label for="color">Choose Event Color:</label>
                    <input type="color" id="color" name="color" value="#FFC107">
                </div>
                <button type="submit">Create Event</button>
            </form>

            <form action="taskmanager.php" method="GET">
                <button type="submit" class="close-btn">Close</button>
            </form>
        </div>
    </body>
    </html>
