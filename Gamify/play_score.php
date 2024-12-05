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

$sql = "INSERT INTO leaderboards (creator_name, quiz_title, full_name, strand, section, total_score) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $creatorName, $quizTitle, $fullName, $strand, $section, $totalScore);

if ($stmt->execute()) {
  
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
<body>



  <div class="container">
    <img src="css/star.png" class="star" alt="Star">
    <div class="congratulations">CONGRATULATIONS!</div>

    <?php
    // Get the score and total from query parameters
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