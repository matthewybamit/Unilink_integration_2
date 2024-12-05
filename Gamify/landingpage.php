<?php
session_start();
date_default_timezone_set('Asia/Singapore');

$conn = new mysqli('localhost', 'root', '', 'quiz_app');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentDateTime = new DateTime();
$currentDate = $currentDateTime->format('Y-m-d');
$currentTime = $currentDateTime->format('H:i:s');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accessCode = $_POST['access_code'];

    $sql = "SELECT title, creator_name, deadline FROM quizzes WHERE access_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $accessCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $quizTitle = $row['title'];
        $creatorName = $row['creator_name'];
        $deadline = $row['deadline'];

        $deadlineDate = (new DateTime($deadline))->format('Y-m-d');
        $deadlineTime = (new DateTime($deadline))->format('H:i:s');

        if ($currentDate > $deadlineDate || ($currentDate == $deadlineDate && $currentTime > $deadlineTime)) {
            echo '<script>alert("The deadline for this quiz has passed. Access is no longer allowed."); window.location.href = "landingpage.php";</script>';
            exit();
        }

        $_SESSION['quiz_title'] = $quizTitle;
        $_SESSION['creator_name'] = $creatorName;

        $sqlCreatorQuiz = "SELECT * FROM creatorquiz WHERE quiz_title = ? AND creator_name = ?";
        $stmtCreatorQuiz = $conn->prepare($sqlCreatorQuiz);
        $stmtCreatorQuiz->bind_param("ss", $quizTitle, $creatorName);
        $stmtCreatorQuiz->execute();
        $resultCreatorQuiz = $stmtCreatorQuiz->get_result();

        if ($resultCreatorQuiz->num_rows > 0) {
            header("Location: enterinfo.php");
            exit();
        } else {
            echo '<script>alert("Quiz not found in creatorquiz table.");</script>';
        }
    } else {
        echo '<script>alert("Invalid access code.");</script>';
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pixel Buttons</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/nes.css/css/nes.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      display: flex;
      flex-direction: column; 
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #1b0e60;
      font-family: 'Press Start 2P', sans-serif;
      overflow: hidden;
    }

    .container {
      display: flex;
      gap: 20px;
      margin-top: -230px;
      align-items: center;
    }

    .button-container {
      display: flex;
      gap: 20px;
    }

    .pixel-button {
      position: relative;
      width: 390px; 
      height: 290px; 
      background-image: url(pixelboxs.jpg);
      background-size: cover; 
      background-position: center; 
      border: none; 
      cursor: pointer;
      font-family: inherit;
      color: white;
      text-transform: uppercase;
      font-size: 26px; 
      text-shadow: 2px 2px 0px #000; 
      display: flex;
      justify-content: center;
      align-items: center;
      transition: transform 0.2s ease; 
    }

    .pixel-button:hover {
      transform: scale(1.05); 
    }

    .pixel-button:active {
      transform: scale(0.95); 
    }

    .bottom-container {
      position: absolute;
      bottom: 50px;
      width: 90%;
      display: flex;
      justify-content: space-between; 
    }

    .character-container img {
      margin-left:110px;
      width: 310px; 
      max-width: 100%; 
      height: 310px; 
      transition: transform 0.3s ease;
    }

    .display-image {
  position: absolute; 
  bottom: 110px;     
  right: 150px;        
  width: 900px;      
  height: auto;      
    }
    
    .navbar {
      display: flex;
      align-items: center;
      padding: 10px 20px;
      background-color: #1b0e60;
      position: fixed; 
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000; 
    }

    .display-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
      margin-top: 50px; /* Ensure proper spacing from the container */
    }

    .display-text {
      position: absolute;
      max-width: 80%;
      height: auto;
      margin-top: 40px;
      margin-left: 470px;
      z-index: 100;
    }

  
    .searchbar {
  position: absolute;
  max-width: 80%;
  height: auto;
  margin-top: 80px;
  margin-left: 470px;
  z-index: 100;
}

.search-input {
  padding: 10px;
  width: 300px;
  font-size: 16px;
  border: none; /* Remove border for a clean look */
  border-radius: 20px;
  background-color: orange; /* Orange background */
  color: white; /* White text for contrast */
  outline: none; /* Remove outline on focus */
}

.search-input::placeholder {
  color: white; /* White placeholder text */
  opacity: 0.8; /* Slightly transparent for better readability */
}

.join-button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: orange; /* Orange background for button */
  color: white; /* White text for contrast */
  border: none;
  cursor: pointer;
  border-radius: 4px;
  text-transform: uppercase;
  transition: background-color 0.3s ease;
}

.join-button:hover {
  background-color: #e69500; /* Slightly darker orange on hover */
}

  </style>
</head>
<body>
  <div class="navbar"><a  href="../Forum/unilink.php">
    <img src="Logo.png" alt="Logo"></a>
  </div>

  <div class="container">
    <button class="pixel-button" id="create-btn">CREATE</button>
    <button class="pixel-button" id="play-btn">PLAY</button>
    <button class="pixel-button" id="leaderboard-btn">LEADERBOARD</button>
  </div>

  <div class="display-wrapper">
    <img src="ENTER THE CODE.png" alt="Enter the Code Text" class="display-text">
    <img src="enterthecodebody.jpg" alt="Enter the Code Display" class="display-image">
    <div class="searchbar">
      <form method="POST" action="">
        <input type="text" name="access_code" placeholder="ENTER CODE HERE" class="search-input" required>
        <button type="submit" class="join-button">Join</button>
      </form>
    </div>
  </div>

  <div class="bottom-container">
    <div class="character-container">
      <img src="boycharacter.png" alt="Character">
    </div>
  </div>

  <script>
    document.getElementById('create-btn').addEventListener('click', function () {
      window.location.href = 'creatorname.php';
    });

    document.getElementById('play-btn').addEventListener('click', function () {
      window.location.href = 'strand.php';
    });

    document.getElementById('leaderboard-btn').addEventListener('click', function () {
      window.location.href = 'creators_quiz.php';
    });
  </script>
</body>
</html>