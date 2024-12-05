<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNILINK</title>
    <link href="https://fonts.googleapis.com/css?family=Junge" rel="stylesheet">
    <link rel="stylesheet" href="css/play_answer.css">
</head>
<body>


<div class="container">
    <div class="content-box">
        <h2>Review your Answer/s:</h2>
        <div class="scrollable-box">
            <?php
            $conn = new mysqli('localhost', 'root', '', 'quiz_app'); 
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $quizTitle = $_SESSION['quiz_title'] ?? '';
            $creatorName = $_SESSION['creator_name'] ?? '';
            
            if (!empty($quizTitle) && !empty($creatorName)) {
                $sql = "SELECT question, option1, option2, option3, correct_answer FROM creatorquiz WHERE quiz_title = ? AND creator_name = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $quizTitle, $creatorName);
                $stmt->execute();
                $result = $stmt->get_result();
            
                if ($result->num_rows > 0) {
                    $questionNumber = 1;
                    while ($row = $result->fetch_assoc()) {
                        $correctOption = $row['correct_answer'];
                        $userAnswer = $_SESSION['user_answers'][$questionNumber - 1] ?? null; // Retrieve user's answer
            
                        $options = [$row['option1'], $row['option2'], $row['option3']];
                        echo "<div class='question'>";
                        echo "<p style='margin-bottom: 10px;'>" . $questionNumber . ". " . htmlspecialchars($row['question']) . "</p>";
                        echo "<div class='options'>";
            
                        // Display choices and mark them
                        foreach (['1', '2', '3'] as $index) {
                            $option = $row['option' . $index]; // Option text (option1, option2, option3)
                            $is_correct = ($correctOption == $index); // Check if it's the correct answer
                            $is_user_answer = ($userAnswer == $index); // Check if it's the user's answer

                            echo '<span class="answer-choice">';
            
                            // If it's the correct answer, display a green check
                            if ($is_correct) {
                                echo '<span class="green-check">✔ ' . htmlspecialchars($option) . ' <span class="label">(Correct answer)</span></span>';
                            }
                            // If it's the user's answer, display a red cross
                            elseif ($is_user_answer) {
                                echo '<span class="red-x">✘ ' . htmlspecialchars($option) . ' <span class="label">(Your answer)</span></span>';
                            } 
                            // Otherwise, display a red cross for incorrect options
                            else {
                                echo '<span class="red-x">✘ ' . htmlspecialchars($option) . '</span>';
                            }
            
                            echo '</span><br>';
                        }
            
                        echo "</div></div>";
                        $questionNumber++;
                    }
                } else {
                    echo "<p>No questions found for this quiz.</p>";
                }
            
                $stmt->close();
            } else {
                echo "<p>Quiz details not found in session.</p>";
            }
            
            $conn->close();
            ?>
        </div>
    </div>

    <div class="leaderboard-section">
        <a href="play_leaderboard.php" class="leaderboard-btn">Leaderboard</a>
    </div>
</div>

</body>
</html>
