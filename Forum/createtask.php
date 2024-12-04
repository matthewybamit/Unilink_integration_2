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

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    // If no session, redirect to sign_up.php
    header("Location: sign_up.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Unilink</title>
  <link rel="stylesheet" href="CSS/unistyle.css" />
  <link rel="stylesheet" href="CSS/Feature.css" />
  <link rel="stylesheet" href="CSS/footer.css" />
  <link rel="stylesheet" href="Forum/notification.css">   
  <link rel="stylesheet" href="CSS/sidebar.css" />
  <link rel="stylesheet" href="CSS/Planner.css" />
  <link rel="stylesheet" href="CSS/curtain.css" />
  <link rel="stylesheet" href="CSS/createtask.css" />
  <script src="Javascripts/app.js"></script>
  <script src="planner.js"></script>
  <link rel="stylesheet" href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css">
</head>
--
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



<div class="sidebar" id="sidebar">
    <ul class="sidebar__menu">
        <li class="sidebar__item "> <!-- Make this item active -->
            <a href="taskmanager.php" class="sidebar__link">
                <i class="fas fa-tasks"></i> <!-- Icon for PLANNER -->
                <span class="sidebar__text">PLANNER</span>
            </a>
        </li>
        <li class="sidebar__item active">
            <a href="createTask.php" class="sidebar__link">
                <i class="fas fa-plus"></i> <!-- Icon for CREATE TASK -->
                <span class="sidebar__text">CREATE TASK</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="Notes.php" class="sidebar__link">
                <i class="fas fa-sticky-note"></i> <!-- Icon for NOTE -->
                <span class="sidebar__text">NOTE</span>
            </a>
        </li>
    </ul>
</div>

<body>
  <div class="body2">
    <div class="top-bar">
      <button class="create-task" id="createTaskBtn"><span>+</span> Create Task</button>
      <div class="search-input">
        <i class="fa-regular fa-magnifying-glass"></i>
        <input type="search" placeholder="Search..." id="taskSearch" />
      </div>
    </div>

    <div class="task-list">
      <h2>Tasks</h2>
      <div class="overflowww" id="task-container">
        <!-- Tasks will be added dynamically -->
      </div>
    </div>
  </div>

  <!-- Create Task Modal -->
  <div id="createTaskModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Create a Task</h5>
        <span class="close-btn">&times;</span>
      </div>
      <form id="createTaskForm" method="POST">
        <div class="modal-body">
          <label for="task-name">Task Name</label>
          <input type="text" name="taskName" id="task-name" required>

          <label for="desc">Task Description</label>
          <textarea name="desc" id="desc"></textarea>

          <label for="timer">Timer</label>
          <div class="timers">
            <div class="hour">
              <label for="hour">H</label>
              <input type="number" name="hours" id="hour" placeholder="00" min="0" max="23">
            </div>
            <div class="minute">
              <label for="minute">M</label>
              <input type="number" name="minutes" id="minute" placeholder="00" min="0" max="59">
            </div>
            <div class="second">
              <label for="minute">S</label>
              <input type="number" name="seconds" id="second" placeholder="00" min="0" max="59">
            </div>
          </div>
          <!-- Error Message for Timer Duration -->
        <div id="time-error" style="color: black; display: none;">------ Please specify the duration of the timer. ------</div>
          <div class="modal-footer">
            <button type="submit" class="create-btn">Create</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Task Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <h3>Delete Task</h3>
      <p>Are you sure you want to delete this task?</p>
      <div class="modal-buttons">
        <button id="confirmDeleteBtn">Confirm</button>
        <button id="cancelDeleteBtn">Cancel</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const taskModal = document.getElementById('createTaskModal');
      const createTaskBtn = document.getElementById('createTaskBtn');
      const closeBtn = document.querySelector('.close-btn');
      const taskSearch = document.getElementById('taskSearch');

      // Toggle Modal Visibility
      createTaskBtn.addEventListener('click', () => {
        taskModal.style.display = "block";
      });

      // Close modal event
closeBtn.addEventListener('click', () => {
  taskModal.style.display = "none";
  document.getElementById('createTaskForm').reset(); // Clear form
  isEditing = false; // Reset editing state
  editTaskId = null; // Reset task ID
});


      window.addEventListener('click', (event) => {
        if (event.target === taskModal) {
          taskModal.style.display = "none";
        }
      });

      // Handle Task Creation
      document.getElementById('createTaskForm').addEventListener('submit', function(event) {
  event.preventDefault();

  const taskName = document.getElementById('task-name').value.trim();
  if (!taskName) {
    alert('Please enter a task title.');
    return;
  }

  const description = document.getElementById('desc').value;
  const hours = parseInt(document.getElementById('hour').value) || 0;
  const minutes = parseInt(document.getElementById('minute').value) || 0;
  const seconds = parseInt(document.getElementById('second').value) || 0;
  const duration = (hours * 3600) + (minutes * 60) + seconds;

  if (duration <= 0) {
    document.getElementById('time-error').style.display = 'block';
    return;
  } else {
    document.getElementById('time-error').style.display = 'none';
  }

  const url = isEditing ? 'controllers/edit_task.php' : 'controllers/add_task.php';
  const payload = { taskName, description, duration };

  // If editing, include taskId in the payload
  if (isEditing) {
    payload.taskId = editTaskId;
  }

  fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(isEditing ? 'Task updated successfully!' : 'Task created successfully!');
      
      // Close modal and reset the form
      taskModal.style.display = "none";
      loadTasks();
      document.getElementById('createTaskForm').reset();

      // Reset editing state
      isEditing = false;
      editTaskId = null;
    } else {
      alert(isEditing ? 'Error updating task.' : 'Error creating task.');
    }
  })
  .catch(error => console.error('Error:', error));
});




      // Load Tasks from Database
      loadTasks();

      function loadTasks() {
  fetch('controllers/get_tasks.php')
    .then(response => response.json())
    .then(tasks => {
      const taskContainer = document.getElementById('task-container');
      taskContainer.innerHTML = ''; // Clear existing tasks

      tasks.forEach(task => {
        const taskElement = document.createElement('div');
        taskElement.classList.add('task-item');
        taskElement.setAttribute('data-task-id', task.id);

        taskElement.innerHTML = `
          <div class="taskBtns">
    <button class="play-btn"><i class="fa-solid fa-play"></i></button>
    <div class="task-name">${task.name}</div>
</div>
<div class="task-timer" data-duration="${task.duration}">${formatTime(task.duration)}</div>
<div>
    <button class="reset-btn"><i class="fa-solid fa-rotate-right"></i></button>
    <!-- Replace Edit text with an eye icon -->
    <button class="edit-btn"><i class="fa-solid fa-eye"></i></button>
    <button class="delete-btn">Delete</button>
</div>

        `;

        taskContainer.appendChild(taskElement); // Add to container

        // Attach event listeners to buttons after adding to DOM
        taskElement.querySelector('.play-btn').addEventListener('click', () => playTask(task.id));
        taskElement.querySelector('.reset-btn').addEventListener('click', () => resetTask(task.id, task.duration));
        taskElement.querySelector('.edit-btn').addEventListener('click', () => editTask(task)); // Attach edit listener
        taskElement.querySelector('.delete-btn').addEventListener('click', () => showDeleteModal(task.id));
      });
    })
    .catch(error => console.error('Error fetching tasks:', error));
}


      // Format Duration as Time
      function formatTime(seconds) {
        const hrs = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
      }

      let intervalId = null;
      let activeTaskId = null;
      let taskIdToDelete = null;

      // Play Task Timer
      function playTask(taskId) {
        const taskElement = document.querySelector(`.task-item[data-task-id="${taskId}"]`);
        
        // If task is not found (deleted), exit
        if (!taskElement) {
          console.error(`Task with ID ${taskId} not found`);
          return;
        }

        const playButton = taskElement.querySelector('.taskBtns button:first-child i');
        const timerDisplay = taskElement.querySelector('.task-timer');
        let duration = parseInt(timerDisplay.getAttribute('data-duration'));
        const defaultDuration = duration;

        if (activeTaskId === taskId) {
          clearInterval(intervalId);
          intervalId = null;
          playButton.classList.replace('fa-pause', 'fa-play');
          activeTaskId = null;
        } else {
          if (intervalId !== null) {
            clearInterval(intervalId);
            const activePlayButton = document.querySelector(`.task-item[data-task-id="${activeTaskId}"] .taskBtns button:first-child i`);
            activePlayButton.classList.replace('fa-pause', 'fa-play');
          }

          activeTaskId = taskId;
          playButton.classList.replace('fa-play', 'fa-pause');

          intervalId = setInterval(() => {
            if (duration > 0) {
              duration -= 1;
              timerDisplay.innerText = formatTime(duration);
              timerDisplay.setAttribute('data-duration', duration);
            } else {
              clearInterval(intervalId);
              playButton.classList.replace('fa-pause', 'fa-play');
              alert("Time's Up!");
              resetTask(taskId, defaultDuration);
            }
          }, 1000);
        }
      }

      // Reset Task Timer
      function resetTask(taskId, defaultDuration) {
  const taskElement = document.querySelector(`.task-item[data-task-id="${taskId}"]`);
  if (!taskElement) return; // If task is deleted, do nothing

  // Stop the active timer (if any)
  if (activeTaskId === taskId) {
    clearInterval(intervalId); // Stop the current interval
    intervalId = null; // Reset the intervalId
    const playButton = taskElement.querySelector('.taskBtns button:first-child i');
    playButton.classList.replace('fa-pause', 'fa-play'); // Change play button back to play
    activeTaskId = null; // Clear the active task
  }

  // Reset the task timer to its initial value
  const timerDisplay = taskElement.querySelector('.task-timer');
  timerDisplay.innerText = formatTime(defaultDuration);
  timerDisplay.setAttribute('data-duration', defaultDuration);
}

      // Show Delete Modal
      function showDeleteModal(taskId) {
        taskIdToDelete = taskId;
        document.getElementById('deleteModal').style.display = "block";
      }

      // Confirm Delete Task
      document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        fetch('controllers/delete_task.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ taskId: taskIdToDelete })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Task deleted successfully!');
            // Remove the task element from the DOM
            const taskElement = document.querySelector(`.task-item[data-task-id="${taskIdToDelete}"]`);
            if (taskElement) {
              taskElement.remove();
            }
            document.getElementById('deleteModal').style.display = "none";
          } else {
            alert('Error deleting task.');
          }
        })
        .catch(error => console.error('Error deleting task:', error));
      });

      // Close Delete Modal
      document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
        document.getElementById('deleteModal').style.display = "none";
      });

      // Real-time Search
      taskSearch.addEventListener('input', function() {
        const searchTerm = taskSearch.value.toLowerCase();
        const tasks = document.querySelectorAll('.task-item');

        tasks.forEach(task => {
          const taskName = task.querySelector('.task-name').textContent.toLowerCase();
          task.style.display = taskName.includes(searchTerm) ? '' : 'none';
        });
      });
    });

    let isEditing = false;
let editTaskId = null;

function editTask(task) {
  isEditing = true;
  editTaskId = task.id;

  // Populate the form with task data
  document.getElementById('task-name').value = task.name;
  document.getElementById('desc').value = task.description;
  const hours = Math.floor(task.duration / 3600);
  const minutes = Math.floor((task.duration % 3600) / 60);
  const seconds = task.duration % 60;
  document.getElementById('hour').value = hours;
  document.getElementById('minute').value = minutes;
  document.getElementById('second').value = seconds;

  // Show the modal
  document.getElementById('createTaskModal').style.display = "block";
}


  </script>
<script src="ForumJs/notification.js"></script>
</body>

</html>