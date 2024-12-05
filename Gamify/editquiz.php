<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'quiz_app';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['quiz_title']) && isset($_GET['question_id'])) {
    $quizTitle = $_GET['quiz_title'];
    $questionId = $_GET['question_id'];

    $sql = "SELECT question, option1, option2, option3, correct_answer 
            FROM creatorquiz 
            WHERE id = ? AND quiz_title = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $questionId, $quizTitle);
    $stmt->execute();
    $result = $stmt->get_result();

    $questionData = null;

    if ($result->num_rows > 0) {
        $questionData = $result->fetch_assoc();
    } else {
        echo "No question found for the specified quiz title and question ID.";
    }
    $stmt->close();
} else {
    echo "Quiz title or question ID not specified.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question_id'])) {
    $questionId = $_POST['question_id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $correct_answer = $_POST['correct_answer'];

    // Check if time_limit is set and assign a default value if not.
    $time_limit = isset($_POST['time_limit']) && !empty($_POST['time_limit']) ? $_POST['time_limit'] : null;

    // If time_limit is not set, handle the error or assign a default value
    if ($time_limit === null) {
        echo "<script>alert('Please select a time limit.');</script>";
        return;
    }

    $updateSql = "UPDATE creatorquiz 
                  SET question = ?, option1 = ?, option2 = ?, option3 = ?, correct_answer = ?, time_limit = ? 
                  WHERE id = ?";
    
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param('sssssii', $question, $option1, $option2, $option3, $correct_answer, $time_limit, $questionId);

    if ($updateStmt->execute()) {
        echo "<script>alert('Question updated successfully!');</script>";
        header("Location: summary.php"); 
        exit;
    } else {
        echo "<script>alert('Error updating question: " . $updateStmt->error . "');</script>";
    }
    $updateStmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/nes.css/css/nes.min.css">
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

        .create-quiz-container {
            position: relative;
            width: 100%;
            max-width: 100%;
            height: 91vh;
            background: url('createquizcontainer.jpg') no-repeat center center;
            background-size: contain;
        }

        .quiz-title {
            text-align: center;
            font-size: 20px;
            padding-top: 177px;
            padding-right: 220px;
            color: white;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-top: 20px;
            max-width: 100%;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container select {
            padding: 10px;
            margin: 10px 0;
            font-size: 14px;
            border-radius: 5px;
        }

        .form-container .option-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .form-container .option-container input[type="text"] {
            flex-grow: 1;
            margin-right: 10px;
        }

        .form-container .option-container input[type="radio"] {
            transform: scale(1.5);
        }

        .form-container .time-limit-container {
            position: absolute;
            width: 195px;
            top: 190px;
            left: 1100px;
            margin: 10px 0;
            background-color: #fff;
            border-radius: 5px;
            border: 2px solid #ccc;
            text-align: center;
        }

        .buttons-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .buttons-container button {
            padding: 10px 20px;
            background-color: #191970;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .buttons-container button:hover {
            opacity: 0.8;
        }

        #time_limit {
            width: 190px;
        }

        .question-box2 {
            position: absolute;
            width: 410px;
            height: 50px;
            top: 400px;
            left: 530px;
            border: none;
            outline: none;
            background: transparent;
            font-size: 16px;
            color: black;
            resize: none;
        }

        .option-box {
            position: absolute;
            width: 400px;
            height: 50px;
            top: 340px;
            left: 970px;
            background-color: #0966D6;
            color: black;
        }

        .option-box2 {
            position: absolute;
            width: 400px;
            height: 50px;
            top: 420px;
            left: 970px;
            background-color: #0966D6;
            color: black;
        }

        .option-box3 {
            position: absolute;
            width: 400px;
            height: 50px;
            top: 500px;
            left: 970px;
            background-color: #0966D6;
            color: black;
        }

        .radio-box {
            position: absolute;
            top: 368px;
            left: 1390px;
        }

        .radio-box2 {
            position: absolute;
            top: 448px;
            left: 1390px;
        }

        .radio-box3 {
            position: absolute;
            top: 528px;
            left: 1390px;
        }

        .save-btn {
            position: absolute;
            top: 760px;
            left: 1340px;
        }

        .submit-btn {
            position: absolute;
            top: 760px;
            left: 1340px;
        }
    </style>
</head>

<body>
    <div class="create-quiz-container">
        <div class="quiz-title"><?php echo htmlspecialchars($_SESSION['quiz_title']); ?></div>

        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="form-container">
                <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($questionId); ?>">

                <input type="text" class="question-box2" name="question" value="<?php echo htmlspecialchars($questionData['question']); ?>" placeholder="Write your question here" required>

                <div class="option-container">
                    <input type="text" class="option-box" name="option1" value="<?php echo htmlspecialchars($questionData['option1']); ?>" placeholder="Option 1" required>
                    <input type="radio" class="radio-box" name="correct_answer" value="1" <?php echo $questionData['correct_answer'] == 1 ? 'checked' : ''; ?> required>
                </div>
                <div class="option-container">
                    <input type="text" class="option-box2" name="option2" value="<?php echo htmlspecialchars($questionData['option2']); ?>" placeholder="Option 2" required>
                    <input type="radio" class="radio-box2" name="correct_answer" value="2" <?php echo $questionData['correct_answer'] == 2 ? 'checked' : ''; ?> required>
                </div>
                <div class="option-container">
                    <input type="text" class="option-box3" name="option3" value="<?php echo htmlspecialchars($questionData['option3']); ?>" placeholder="Option 3" required>
                    <input type="radio" class="radio-box3" name="correct_answer" value="3" <?php echo $questionData['correct_answer'] == 3 ? 'checked' : ''; ?> required>
                </div>

                <!-- <div class="time-limit-container">
                    <label for="time_limit">Time Limit: </label>
                    <select name="time_limit" id="time_limit" required>
                        <option value="">Select...</option>
                        <option value="5" <?php echo isset($questionData['time_limit']) && $questionData['time_limit'] == 5 ? 'selected' : ''; ?>>5 seconds</option>
                        <option value="10" <?php echo isset($questionData['time_limit']) && $questionData['time_limit'] == 10 ? 'selected' : ''; ?>>10 seconds</option>
                        <option value="15" <?php echo isset($questionData['time_limit']) && $questionData['time_limit'] == 15 ? 'selected' : ''; ?>>15 seconds</option>
                    </select>
                </div>
            </div> -->

            <div class="buttons-container">
                <button type="submit" class="save-btn">SAVE</button>
            </div>
        </form>
    </div>

    <script>
        function validateForm() {
            const timeLimit = document.getElementById('time_limit').value;
            if (!timeLimit) {
                alert("Please select a time limit.");
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const timeLimitInput = document.getElementById('time_limit');
            const savedTimeLimit = localStorage.getItem('selectedTimeLimit');

            // If there's a saved time limit, set it in the dropdown
            if (savedTimeLimit) {
                timeLimitInput.value = savedTimeLimit;
            }

            // Event listener to save the selected time limit in localStorage
            timeLimitInput.addEventListener('change', function() {
                const selectedTimeLimit = timeLimitInput.value;
                if (selectedTimeLimit) {
                    localStorage.setItem('selectedTimeLimit', selectedTimeLimit);
                }
            });
        });
    </script>
</body>

</html>