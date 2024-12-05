<?php
// Start the session
session_start();

// Database configuration
$host = 'localhost'; // Change if your database is hosted elsewhere
$user = 'root'; // Your database username
$password = ''; // Your database password
$database = 'quiz_app'; // Your database name

// Create a database connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch quiz titles and creator names
$sql = "SELECT title, creator_name FROM quizzes";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Store quizzes in an array
    $quizzes = [];
    while ($row = $result->fetch_assoc()) {
        $quizzes[] = $row;
    }
} else {
    
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNILINK Quiz Interface</title>
    <link href="https://fonts.googleapis.com/css?family=Junge" rel="stylesheet">
    <link rel="stylesheet" href="css/creators_quiz.css">

</head>
<body>
    
<div class="container">
<div class="content-box">
        <div class="header">Quizzes</div>

<?php if (!empty($quizzes)): ?>
    <?php foreach ($quizzes as $quiz): ?>
        <a href="leaderboards.php?quiz_title=<?php echo urlencode($quiz['title']); ?>&creator_name=<?php echo urlencode($quiz['creator_name']); ?>">
            <button class="quiz-button">
                <?php echo htmlspecialchars($quiz['title']); ?><br>
                <p style="font-size: 20px;">by</p>
                <span><?php echo htmlspecialchars($quiz['creator_name']); ?></span>
            </button>
        </a>
    <?php endforeach; ?>
<?php else: ?>
    <p>No quizzes available.</p>
<?php endif; ?>
</div>
    </div>
</body>
</html>