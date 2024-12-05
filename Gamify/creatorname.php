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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['creator_name'])) {
    $_SESSION['creator_name'] = $_POST['creator_name'];
    header("Location: quiztitle.php"); 
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
  <title>Enter Information</title>
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
            background: url('creatornamecontainer.jpg') no-repeat center center;
            background-size: contain;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .header {
            margin-bottom: 20px;
            position: relative; /* For positioning the input over the image */
        }

        .textbox-header {
            margin-top: 15px;
            max-width: 1150px;
            height: auto;
            display: block;
        }

        /* Styled input field */
        .interactive-input {
            position: absolute;
            top: 57%; /* Center vertically */
            left: 50%; /* Center horizontally */
            transform: translate(-50%, -50%);
            width: 70%; /* Adjust as needed */
            height: 40px;
            border: none;
            border-bottom: 2px solid #ff6600;
            font-size: 1em;
            text-align: center;
            background-color: transparent;
            color: black;
            outline: none;
            caret-color: orange; /* Cursor color */
        }

        .interactive-input::placeholder {
            color: gray; /* Placeholder color */
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
            filter: brightness(1.2); /* Hover effect */
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
                    name="creator_name" 
                    class="interactive-input" 
                    placeholder="ENTER YOUR NAME HERE" 
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
