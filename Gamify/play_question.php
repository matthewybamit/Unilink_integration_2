<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'quiz_app');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$quizTitle = $_SESSION['quiz_title'] ?? '';
$creatorName = $_SESSION['creator_name'] ?? '';

if (empty($quizTitle) || empty($creatorName)) {
    echo "Quiz details not found in session.";
    exit();
}

if (empty($_SESSION['fullname']) || empty($_SESSION['strand']) || empty($_SESSION['section'])) {
    echo "Error: Missing required data (Full Name, Strand, or Section). Please go back and enter all details.";
    exit();
}

$sql = "SELECT * FROM creatorquiz WHERE quiz_title = ? AND creator_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $quizTitle, $creatorName);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
} else {
    echo "No questions found for this quiz.";
    exit();
}

$_SESSION['total_items'] = count($questions);

if (!isset($_SESSION['question_index'])) {
    $_SESSION['question_index'] = 0;
    $_SESSION['correct_answers'] = 0;
}

if (isset($_GET['next']) && $_GET['next'] === 'true') {
    if (isset($_GET['selected_answer'])) {
        $selectedAnswer = $_GET['selected_answer'];
        
        // Store user's answer in session
        $_SESSION['user_answers'][$_SESSION['question_index']] = $selectedAnswer;
        
        if ($selectedAnswer === $questions[$_SESSION['question_index']]['correct_answer']) {
            $_SESSION['correct_answers']++;
        }
    }
    $_SESSION['question_index']++;
}

$isLastQuestion = $_SESSION['question_index'] >= count($questions);

if ($isLastQuestion) {
    header("Location: play_score.php?correct=" . $_SESSION['correct_answers'] . "&total=" . count($questions));
    exit();
}

$currentQuestion = $questions[$_SESSION['question_index']];
$timeLimit = $currentQuestion['time_limit'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="styles.css"> 
    <link rel="stylesheet" href="css/play_question.css"> 
</head>
<body>
 

    <div class="timer">
        <span>Time Left: <span id="countdown"><?php echo $timeLimit; ?></span> seconds</span>
    </div>

    <div class="quiz-title"><?php echo htmlspecialchars($currentQuestion['quiz_title']); ?></div>

    <div class="quiz-container">
        <div class="vertical-line"></div>

        <div class="question-section">
            <p><strong>QUESTION <?php echo $_SESSION['question_index'] + 1; ?></strong></p>
            <p><?php echo htmlspecialchars($currentQuestion['question']); ?></p>
        </div>

        <div class="options-section">
            <?php
            $options = ['option1', 'option2', 'option3']; 
            foreach ($options as $index => $option) {
                if (!empty($currentQuestion[$option])) {
                    $link = 'play_question.php?next=true&title=' . urlencode($quizTitle) . '&creator=' . urlencode($creatorName) . '&selected_answer=' . ($index + 1);
                    echo "<button class='option gray-option' onclick=\"location.href='$link'\">" . htmlspecialchars($currentQuestion[$option]) . "</button>";
                }
            }
            ?>
        </div>
    </div>

    <script>
        let timeLeft = <?php echo $timeLimit; ?>;
        const countdownElement = document.getElementById('countdown');

        function startCountdown() {
            const timer = setInterval(function() {
                timeLeft--;
                countdownElement.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    // Redirect to next question if time runs out
                    location.href = 'play_question.php?next=true';
                }
            }, 1000);
        }

        startCountdown();
    </script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
