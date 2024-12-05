<?php
session_start();

// Ensure the session is not lost
if (!isset($_SESSION)) {
    session_start();
}

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

// Handle the question flow
if (isset($_GET['next']) && $_GET['next'] === 'true') {
    if (isset($_GET['selected_answer'])) {
        $selectedAnswer = $_GET['selected_answer'];
        
        // Store user's answer in session
        $_SESSION['user_answers'][$_SESSION['question_index']] = $selectedAnswer;
        
        // Debug: Check selected answer and correct answer
        error_log("Selected Answer: $selectedAnswer");
        error_log("Correct Answer: " . $questions[$_SESSION['question_index']]['correct_answer']);
        
        if ($selectedAnswer === $questions[$_SESSION['question_index']]['correct_answer']) {
            $_SESSION['correct_answers']++;
        }
    }
    $_SESSION['question_index']++;
}

// Redirect after last question
$isLastQuestion = $_SESSION['question_index'] >= count($questions);

if ($isLastQuestion) {
    header("Location: play_score2.php?correct=" . $_SESSION['correct_answers'] . "&total=" . $_SESSION['total_items']);
    exit();
}

$currentQuestion = $questions[$_SESSION['question_index']];
$timeLimit = $currentQuestion['time_limit'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz</title>
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

    .quiz-container {
      position: relative;
      width: 90%;
      max-width: 1200px;
      height: 91vh;
      background: url('quizcontainerbox.jpg') no-repeat center center;
      background-size: contain;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .question-section {
      flex: 1;
      text-align: left;
      padding: 20px;
      color: black;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .question-section .title {
      font-size: 16px;
      margin-bottom: 50px;
    }

    .question-section .question {
      font-size: 14px;
      line-height: 25px;
    }

    .answers-section {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 20px;
    }

    .answers-section button {
      width: 390px;
      padding: 10px;
      margin-bottom: 30px;
      font-size: 14px;
      text-align: left;
      background-color: #0966D6;
      color: white;
      border: none;
      border-radius: 5px;
      transition: transform 0.3s ease-in-out, background-color 0.3s ease-in-out;
    }
  </style>
</head>
<body>
  <div class="quiz-container">
    <div class="question-section" style="padding-left: 207px;">
      <div class="title">QUESTION <?php echo ($_SESSION['question_index'] + 1); ?></div>
      <div class="question"><?php echo htmlspecialchars($currentQuestion['question']); ?></div>
    </div>
    <div class="answers-section" style="padding-left: 50px; padding-right: 180px;">
      <form method="GET" action="">
        <button type="submit" name="selected_answer" value="A"><?php echo htmlspecialchars($currentQuestion['option1']); ?></button>
        <button type="submit" name="selected_answer" value="B"><?php echo htmlspecialchars($currentQuestion['option2']); ?></button>
        <button type="submit" name="selected_answer" value="C"><?php echo htmlspecialchars($currentQuestion['option3']); ?></button>
        <input type="hidden" name="next" value="true">
      </form>
    </div>
  </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
