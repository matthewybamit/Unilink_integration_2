<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'quiz_app');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve quiz title and creator name from session variables
$quizTitle = $_SESSION['quiz_title'] ?? '';
$creatorName = $_SESSION['creator_name'] ?? '';

// Ensure that both values are available before proceeding
if (empty($quizTitle) || empty($creatorName)) {
    echo "Quiz details not found in session.";
    exit();
}

// Get the total number of items (questions) from the session
$totalItems = $_SESSION['total_items'] ?? 0;

// Fetch leaderboard data
$sql = "SELECT full_name, section, total_score FROM leaderboards WHERE creator_name = ? AND quiz_title = ? ORDER BY total_score DESC";
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
    <link rel="stylesheet" href="css/play_leaderboard.css">
</head>
<body>
<header>
   
</header>

<div class="leaderboard">
        <div class="leaderboard-title">LEADERBOARD</div>
        
        <div class="entry-container">
    <?php
    $rank = 1;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='entry'>";
            echo "<div class='box rank'>{$rank}</div>";
            echo "<div class='box name'>" . htmlspecialchars($row['full_name']) . "</div>";
            echo "<div class='box section'>" . htmlspecialchars($row['section']) . "</div>";
            echo "<div class='box score'>" . $row['total_score'] . "/{$totalItems}</div>";  
            echo "</div>";
            $rank++;
        }
    } else {
        echo "<p>No leaderboard data available for this quiz.</p>";
    }
    ?>
</div>

<div class="footer">
    <a href="reset.php" class="home-button">
        <i class="fas fa-home"></i>
        Home
    </a>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>