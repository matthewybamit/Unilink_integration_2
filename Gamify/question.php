<?php
$host = 'localhost';
$db = 'quiz_db';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 0;
}

if (!isset($_SESSION['strand'])) {
    $_SESSION['strand'] = isset($_GET['strand']) ? strtoupper($_GET['strand']) : '';
}
if (!isset($_SESSION['difficulty'])) {
    $_SESSION['difficulty'] = isset($_GET['difficulty']) ? strtoupper($_GET['difficulty']) : '';
}

$selectedStrand = $_GET['strand'] ?? '';
$selectedDifficulty = $_GET['difficulty'] ?? '';
$quizTitle = $selectedStrand . " QUIZ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['answer'])) {
        $user_answer = $_POST['answer'];

        // Store user's answer in the session
        $_SESSION['user_answers'][$_SESSION['current_question']] = $user_answer;

        // Check correct answer
        $query = "SELECT correct_choice FROM questions WHERE strand = ? AND difficulty = ? LIMIT 1 OFFSET ?";
        $stmt = $conn->prepare($query);

        $current_question_id = $_SESSION['current_question'];
        $stmt->bind_param('ssi', $selectedStrand, $selectedDifficulty, $current_question_id);
        $stmt->execute();
        $stmt->bind_result($correct_answer);
        $stmt->fetch();
        $stmt->close();

        if ($user_answer === $correct_answer) {
            $_SESSION['score']++;
        }

        $_SESSION['current_question']++;

        $query = "SELECT COUNT(*) FROM questions WHERE strand = ? AND difficulty = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $selectedStrand, $selectedDifficulty);
        $stmt->execute();
        $stmt->bind_result($question_count);
        $stmt->fetch();
        $stmt->close();

        if ($_SESSION['current_question'] < $question_count) {
            header("Location: question.php?strand=" . urlencode($selectedStrand) . "&difficulty=" . urlencode($selectedDifficulty) . "&quiz_title=" . urlencode($quizTitle));
            exit();
        } else {
            $query = "INSERT INTO q_results (total_correct) VALUES (?)";
            $stmt = $conn->prepare($query);
            $total_correct = $_SESSION['score'];
            $stmt->bind_param('i', $total_correct);
            $stmt->execute();
            $stmt->close();

            $_SESSION['score'] = 0;
            $_SESSION['current_question'] = 0;

            header("Location: quizscore.php");
            exit();
        }
    }
}

$current_question_index = $_SESSION['current_question'];
$query = "SELECT * FROM questions WHERE strand = ? AND difficulty = ? LIMIT 1 OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ssi', $selectedStrand, $selectedDifficulty, $current_question_index);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $current_question = $result->fetch_assoc();
} else {
    $_SESSION['current_question'] = 0;
    header("Location: question.php?strand=" . urlencode($selectedStrand) . "&difficulty=" . urlencode($selectedDifficulty) . "&quiz_title=" . urlencode($quizTitle));
    exit();
}
$stmt->close();
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
      <div class="title">QUESTION <?php echo ($current_question_index + 1); ?></div>
      <div class="question"><?php echo htmlspecialchars($current_question['question_text']); ?></div>
    </div>
    <div class="answers-section" style="padding-left: 50px; padding-right: 180px;">
      <form method="POST" action="">
        <button type="submit" name="answer" value="A"><?php echo htmlspecialchars($current_question['choice_a']); ?></button>
        <button type="submit" name="answer" value="B"><?php echo htmlspecialchars($current_question['choice_b']); ?></button>
        <button type="submit" name="answer" value="C"><?php echo htmlspecialchars($current_question['choice_c']); ?></button>
      </form>
    </div>
  </div>
</body>
</html>


<?php
mysqli_close($conn);
?>
