<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'quiz_app');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$correct = isset($_GET['correct']) ? intval($_GET['correct']) : 0;
$total = isset($_GET['total']) ? intval($_GET['total']) : 0;
$totalScore = $correct;

$creatorName = $_SESSION['creator_name'] ?? '';
$quizTitle = $_SESSION['quiz_title'] ?? '';
$fullName = $_SESSION['fullname'] ?? '';
$strand = $_SESSION['strand'] ?? '';
$section = $_SESSION['section'] ?? '';

// Check if all required data is present
if (empty($creatorName) || empty($quizTitle) || empty($fullName) || empty($strand) || empty($section)) {
    echo "Error: Missing required data (Full Name, Strand, or Section).";
    exit();
}

// Insert score into leaderboards table
$sql = "INSERT INTO leaderboards (creator_name, quiz_title, full_name, strand, section, total_score) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $creatorName, $quizTitle, $fullName, $strand, $section, $totalScore);

if ($stmt->execute()) {
    // Success
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Congratulations</title>
  <link href="https://fonts.googleapis.com/css?family=Junge" rel="stylesheet">
  <link rel="stylesheet" href="css/play_score.css">
</head>

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
            transition: opacity 0.3s ease;
        }

        .button1-img {
            width: 130px;
            height: auto;
            margin-right: 60px;
        }

        .button2-img {
            width: 160px;
            height: auto;
        }

        .buttons img:hover {
            transform: scale(0.9);
            opacity: 0.9;
        }

        .buttons .button-container:hover .button-text {
            transform: translate(-50%, -50%) scale(1.05);
            transition: transform 0.3s ease;
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

        .score {
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 36px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
    </style>
<body>

  <nav>
    UNILINK
  </nav>

  <div class="container">
    <img src="css/star.png" class="star" alt="Star">
    <div class="congratulations">CONGRATULATIONS!</div>

    <?php
    // Displaying the total score (correct/total)
    $correct = isset($_GET['correct']) ? intval($_GET['correct']) : 0;
    $total = isset($_GET['total']) ? intval($_GET['total']) : 0;
    ?>

    <div class="score"><?php echo htmlspecialchars($correct) . '/' . htmlspecialchars($total); ?></div>

    <div class="button-container">
        <a href="play_answer.php" class="view-answer-button">VIEW ANSWER</a>
    </div>
  </div>

</body>
</html>
