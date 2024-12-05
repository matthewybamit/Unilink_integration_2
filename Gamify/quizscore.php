<?php
session_start();

$host = 'localhost';
$db = 'quiz_db';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT total_correct FROM q_results ORDER BY id DESC LIMIT 1";
$result = $conn->query($query);
$total_score = 0;

if ($result && $row = $result->fetch_assoc()) {
    $total_score = $row['total_correct'];
}

$total_questions = 5;

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Completed</title>
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

        .quiz-completed {
            position: relative;
            background: url('quizcompleted.jpg') no-repeat center center;
            background-size: contain;
            width: 1100px;
            height: 800px;
        }

        .buttons {
            position: absolute;
            bottom: 70px;
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 50px;
        }

        .button-container {
            position: relative;
            text-align: center;
        }

        .buttons img {
            width: 160px;
            height: auto;
            cursor: pointer;
            transition: transform 0.1s ease-in-out;
        }

        .button1-img {
            width: 130px;
            height: auto;
            margin-right: 5px;
        }

        .button2-img {
            width: 160px;
            height: auto;
        }

        .buttons img:hover {
            transform: scale(1.1);
        }

        .buttons .button-container:hover .button-text {
            transform: translate(-50%, -50%) scale(1.05);
        }

        .confetti {
            position: fixed;
            top: -15%;
            left: 50%;
            transform: translateX(-50%);
            width: 2000px;
            height: 115%;
            background: url('confetti.png') no-repeat center center;
            background-size: contain;
            pointer-events: none;
            z-index: 10;
        }

        .congratulations {
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 36px;
            font-weight: bold;
            color: #FFD700;
        }

        .score {
            width: 328px;
            height: 80px;
            background-color: #0966D6;
            position: absolute;
            top: 480px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 50px;
            color: #FFF;
            padding-top: 4px;
            text-align: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="quiz-completed">
        <div class="confetti"></div>

        <div class="congratulations">CONGRATULATIONS!</div>
        <div class="score"><?php echo $total_score; ?>/<?php echo $total_questions; ?></div>

        <div class="buttons">
            <div class="button-container">
                <img src="button1.jpg" alt="Button 1" class="button1-img" id="nextPageButton1">
            </div>
            <!-- <div class="button-container">
                <img src="button2.jpg" alt="Button 2" class="button2-img" id="nextPageButton2">
            </div> -->
        </div>

        <script>
            document.getElementById("nextPageButton1").addEventListener("click", function() {
                window.location.href = "overview.php";
            });

            document.getElementById("nextPageButton2").addEventListener("click", function() {
                window.location.href = "leaderboard.php";
            });
        </script>
    </div>
</body>

</html>