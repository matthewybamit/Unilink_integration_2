<?php
session_start();

// Database configuration
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "quiz_app"; 

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $quiz_title = $_POST['quiz_title'];
    $_SESSION['quiz_title'] = $quiz_title;

    // Redirect to code_question.php
    header("Location: createquiz.php");
    exit();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enter Quiz Title</title>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/nes.css/css/nes.min.css" rel="stylesheet">
  <style>
        body {
            font-family: 'Press Start 2P', sans-serif;
            margin: 0;
            background-color: #1b0e60;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .quizzes-container {
            position: relative;
            width: 90%;
            max-width: 1200px;
            height: 90vh;
            background: url('quiztitlecontainer.jpg') no-repeat center center;
            background-size: contain;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .header {
            margin-bottom: 20px;
            position: relative;
        }

        .textbox-header {
            max-width: 1150px;
            height: auto;
            display: block;
        }

        .interactive-input {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            height: 40px;
            border: none;
            border-bottom: 2px solid #ff6600;
            font-size: 1em;
            text-align: center;
            background-color: transparent;
            color: black;
            outline: none;
            caret-color: orange;
        }

        .interactive-input::placeholder {
            color: gray;
        }

        .footer {
            position: absolute;
            bottom: 90px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .next-button {
            background: url('creatornamenextbutton.jpg') no-repeat center center;
            background-size: contain;
            width: 150px;
            height: 50px;
            border: none;
            cursor: pointer;
        }

        .next-button:hover {
            filter: brightness(1.2);
        }
    </style>
</head>
<body>
    <div class="quizzes-container">
        <form method="POST" action="">
            <div class="header">
                <!-- Background image for textbox -->
                <img src="creatornametextbox.png" alt="Textbox Header" class="textbox-header">
                <!-- Interactive input field -->
                <input 
                    type="text" 
                    name="quiz_title" 
                    class="interactive-input" 
                    placeholder="ENTER QUIZ TITLE HERE" 
                    required>
            </div>

            <!-- Footer with the button -->
            <div class="footer">
                <button type="submit" class="next-button"></button>
            </div>
        </form>
    </div>
</body>
</html>
