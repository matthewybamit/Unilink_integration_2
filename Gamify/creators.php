<?php
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "quiz_app"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['creator_name'])) {
    $_SESSION['creator_name'] = $_POST['creator_name'];
    header("Location: question.php"); 
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creator Name</title>
    <link href="https://fonts.googleapis.com/css2?family=Junge&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/creators.css">
</head>
<body>

    <div class="navbar">
        UNILINK
    </div>

    <div class="name-container">
        <form action="" method="POST">
            <label>Creator's Full Name:</label>
            <input type="text" id="creator_name" name="creator_name" placeholder="Enter your name here" required>
            
            <button type="submit" class="submit-btn">
                <span class="play-icon">▶</span>
            </button>
        </form>
    </div>
</body>
</html>
