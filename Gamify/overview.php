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

if (!isset($_SESSION['strand']) || !isset($_SESSION['difficulty'])) {
  header("Location: index.php");
  exit();
}

$selectedStrand = $_SESSION['strand'];
$selectedDifficulty = $_SESSION['difficulty'];
$quizTitle = $selectedStrand . " QUIZ";

// Fetch the questions
$query = "SELECT question_text, choice_a, choice_b, choice_c, correct_choice FROM questions WHERE strand = ? AND difficulty = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $selectedStrand, $selectedDifficulty);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
  $questions[] = $row;
}
$stmt->close();
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Overview</title>
  <style>
    body {
      margin: 0;
      background-color: #1b0e60;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Arial', sans-serif;
    }

    .overview-container {
      position: relative;
      width: 100%;
      max-width: 100%;
      height: 91vh;
      background: url('overviewcontainer.jpg') no-repeat center center;
      background-size: contain;
    }

    .content-box {
      position: absolute;
      top: 195px;
      left: 652px;
      transform: translateX(-50%);
      width: 680px;
      background-color: #fff;
      border-radius: 20px;
      padding: 20px;
      max-height: 260px;
      overflow-y: auto;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .content-box h2 {
      margin-top: 0;
    }

    .question {
      margin-bottom: 20px;
    }

    .options {
      margin-left: 20px;
    }

    .answer-choice {
      margin-bottom: 10px;
    }

    .green-check {
      color: green;
    }

    .red-x {
      color: red;
    }

    .label {
      font-size: 12px;
      color: gray;
    }

    .overview-button {
      position: absolute;
      bottom: 60px;
      left: calc(50% - 100px);
      width: 200px;
      height: 60px;
      background: url('overviewbutton.jpg') no-repeat center center;
      background-size: contain;
      cursor: pointer;
    }

    .overview-button:hover {
      transform: scale(0.9);
    }
  </style>
</head>

<body>
  <div class="overview-container">
    <div class="content-box">

      <?php foreach ($questions as $index => $question): ?>
        <div class="question">
          <p><?php echo ($index + 1) . '. ' . htmlspecialchars($question['question_text']); ?></p>
          <div class="options">
            <?php
            $correct_answer = $question['correct_choice'];
            $user_answer = isset($_SESSION['user_answers'][$index]) ? $_SESSION['user_answers'][$index] : null;

            // Display choices and mark them
            foreach (['A', 'B', 'C'] as $choice) {
              $option = $question['choice_' . strtolower($choice)];
              $is_correct = ($correct_answer == $choice);
              $is_user_answer = ($user_answer == $choice);
              echo '<span class="answer-choice">';
              if ($is_correct) {
                echo '<span class="green-check">✔ ' . htmlspecialchars($option) . ' <span class="label">(Correct answer)</span></span>';
              } elseif ($is_user_answer) {
                echo '<span class="red-x">✘ ' . htmlspecialchars($option) . ' <span class="label">(Your answer)</span></span>';
              } else {
                echo '<span class="red-x">✘ ' . htmlspecialchars($option) . '</span>';
              }
              echo '</span><br>';
            }
            ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="overview-button" onclick="goToNextPage()"></div>
  </div>

  <script>
    function goToNextPage() {
      window.location.href = 'landingpage.php';
    }
  </script>
</body>

</html>