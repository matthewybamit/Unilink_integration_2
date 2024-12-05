<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'quiz_app');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$quizTitle = $_GET['quiz_title'] ?? '';
$creatorName = $_GET['creator_name'] ?? '';

if (empty($quizTitle) || empty($creatorName)) {
    echo "Quiz details not found in URL.";
    exit();
}

$totalItems = $_SESSION['total_items'] ?? 0;

$sql = "SELECT full_name, strand, section, total_score FROM leaderboards WHERE creator_name = ? AND quiz_title = ? ORDER BY total_score DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $creatorName, $quizTitle);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
    <link href="https://fonts.googleapis.com/css?family=Junge" rel="stylesheet">
    <link rel="stylesheet" href="css/leaderboards.css">
</head>
<body>
<header>
    <nav>
        <h1>UNILINK</h1>
    </nav>
</header>

<div class="leaderboard">
        <div class="header">LEADERBOARD</div>
        
        <div class="entry-container">
            <?php
            $rank = 1;

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='entry'>";
                    echo "<div class='box rank'>{$rank}</div>";
                    echo "<div class='box name'>" . htmlspecialchars($row['full_name']) . "</div>";
                    echo "<div class='box section'>" . htmlspecialchars($row['section']) . "</div>";
                    echo "<div class='box strand'>" . htmlspecialchars($row['strand']) . "</div>";
                    echo "<div class='box score'>" . $row['total_score'] . "/{$totalItems}</div>";  // Display score out of total questions
                    echo "</div>";
                    $rank++;
                }
            } else {
                echo "<p>No leaderboard data available for this quiz.</p>";
            }
            ?>
        </div>
        
        <div class="footer">
            <a href="home.php" class="home-button">
                <i class="fas fa-home"></i>
                Home
            </a>
            <a href="creators_quiz.php" class="back-button">
                
                    Back
                
            </a>
        </div>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>