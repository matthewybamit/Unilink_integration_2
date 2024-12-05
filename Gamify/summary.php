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

// Fetching quiz details from session
if (isset($_SESSION['creator_name'], $_SESSION['quiz_title'])) {
    $creatorName = $_SESSION['creator_name'];
    $quizTitle = $_SESSION['quiz_title'];

    $sql = "SELECT id, question, option1, option2, option3, correct_answer 
            FROM creatorquiz 
            WHERE creator_name = ? AND quiz_title = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $creatorName, $quizTitle);
    $stmt->execute();
    $result = $stmt->get_result();

    $questions = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
    }
    $stmt->close();
} else {
    echo "Creator name or quiz title not found in session.";
}

// Handling question deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question_id'])) {
    $questionId = $_POST['question_id'];

    $sql = "DELETE FROM creatorquiz WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $questionId);
        if ($stmt->execute()) {
            echo "<script>alert('Question deleted successfully.'); window.location.href='summary.php';</script>";
        } else {
            echo "Error deleting question: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Handle the publish modal and deadline submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deadline_date'], $_POST['deadline_time'])) {
    $date = $_POST['deadline_date'];
    $time = $_POST['deadline_time'];
    $quizTitle = $_SESSION['quiz_title']; 
    $creatorName = $_SESSION['creator_name']; 
    $timeLimit = isset($_POST['time_limit']) ? $_POST['time_limit'] : null; // Retrieve time_limit

    $deadline = $date . ' ' . $time;
    $randomCode = strtoupper(uniqid());

    $sql = "INSERT INTO quizzes (title, creator_name, time_limit, deadline, access_code) 
            VALUES ('$quizTitle', '$creatorName', '$timeLimit', '$deadline', '$randomCode')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('quizCode').value = '$randomCode';
                    document.getElementById('successModal').style.display = 'block';
                });
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary</title>

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

            .summary-container {
                position: relative;
                width: 100%;
                max-width: 100%;
                height: 91vh;
                background: url('summarycontainer.jpg') no-repeat center center;
                background-size: contain;
            }

        
            .buttons {
                position: absolute;
                top: 180px;
                right: 500px;
                display: flex;
                gap: 10px;
            }

            .button1 {
                width: 170px;
                height: 80px;
                background: url('summarybutton1.jpg') no-repeat center center;
                background-size: contain;
                cursor: pointer;
                transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
            }

            .button2 {
                width: 170px;
                height: 80px;
                background: url('summarybutton2.jpg') no-repeat center center;
                background-size: contain;
                cursor: pointer;
                transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
            }

            .button1:hover, .button2:hover {
                transform: scale(0.95);
            }

            .button1:active, .button2:active {
                transform: scale(0.9);
                filter: grayscale(100%);
            }

            .add-question {
                position: absolute;
                bottom: 60px;
                left: 50%;
                transform: translateX(-50%);
                width: 300px;
                height: 50px;
                background: url('addquestionbutton.jpg') no-repeat center center;
                background-size: contain;
                cursor: pointer;
                transition: transform 0.2s ease, filter 0.2s ease;
            }

            .add-question:hover {
                transform: translateX(-50%) scale(0.95);
            }

            .add-question:active {
                transform: translateX(-50%) scale(0.9);
                filter: grayscale(100%);
            }

            /* Modal Styles */
            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.8);
                z-index: 1000;
                justify-content: center;
                align-items: center;
            }

            .modal.active {
                display: flex;
            }

            /* Calendar Container */
            .calendar-container {
                background: #e8e1f3;
                border-radius: 12px;
                padding: 20px;
                width: 390px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
                text-align: center;
            }

            .calendar-header {
                text-align: center;
                margin-bottom: 20px;
            }

            .calendar-header h3 {
                margin: 0;
                font-size: 14px;
                color: #1b0e60;
            }

            .calendar-header button {
                background: none;
                border: none;
                color: #1b0e60;
                cursor: pointer;
                font-size: 16px;
                transition: transform 0.2s ease;
            }

            .calendar-header button:hover {
                transform: scale(1.1);
            }

            .calendar-grid {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 8px;
            }

            .calendar-grid div {
                text-align: center;
                padding: 10px;
                border-radius: 8px;
                font-size: 12px;
            }

            .calendar-grid .header {
                font-weight: bold;
                background-color: #d7cee9;
                color: #1b0e60;
            }

            .calendar-grid .day {
                background-color: #f8f6ff;
                color: #1b0e60;
                cursor: pointer;
                transition: transform 0.2s ease, background-color 0.2s ease;
            }

            .calendar-grid .day:hover {
                background-color: #d7cee9;
            }

            .calendar-grid .today {
                background-color: white;
                color: black;
            }

            .calendar-grid .selected {
                background-color: #4637a6;
                color: white;
            }

            .time-container {
                margin-top: 15px;
                text-align: center;
                color: #1b0e60;
                font-size: 12px;
            }

            .time-input {
                font-family: 'Press Start 2P', sans-serif;
                background: #f8f6ff;
                border: 1px solid #d7cee9;
                padding: 5px;
                border-radius: 8px;
                width: 80px;
                text-align: center;
                font-size: 12px;
            }

            .edit-icon {
                width: 20px;
                height: 20px;
                display: inline-block;
                background: url('editicon.png') no-repeat center center;
                background-size: contain;
                cursor: pointer;
                vertical-align: middle;
            }

            .close-modal, .apply-modal {
                margin-top: 15px;
                background: #ff8c4c;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
            }

            .apply-modal {
                margin-left: 10px;
                background: #4637a6;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
            }

            /* Publish Modal */
            .publish-content {
                background: rgb(9,102,214);
                border-radius: 12px;
                padding: 45px;
                width: 500px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
                text-align: center;
            }

            .success-message {
                font-size: 14px;
                color: white;
                margin-bottom: 15px;
            }


        

            .code-container {
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 20px 0;
            }

            .code-box {
                width: 150px;
                padding: 5px;
                border: 1px solid #4637a6;
                border-radius: 8px;
                text-align: center;
                background: #fff;
                font-size: 14px;
                font-family: 'Press Start 2P', sans-serif;
                color: #000;
            }

            .copy-icon {
        width: 34px;
        height: 44px;
        background: url('copycodeicon.png') no-repeat center center;
        background-size: contain;
        cursor: pointer;
        margin-left: -10px;
        
        background-color: rgb(9,102,214);
    }


            .close-modal:hover {
                background: gray;
            }


.navbar {
    background-color: #191970;
    padding: 10px;
    color: white;
    font-size: 24px;
    font-weight: bold;
    text-align: left;
    padding-left: 20px;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}


.container {
    width: 70%;
    margin: 150px auto 0 auto;
    padding-bottom: 50px;
    position: relative;
}


h2 {
    color: black;
    text-align: left;
    margin-bottom: 30px;
    transform: translateY(10px);
    font-size: 30px;
}



.question-container {
    background-color: white;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    margin-bottom: 50px;
    position: relative;
}

.question-container:hover {
    transform: scale(1.02);
}


.question-container h3 {
    margin-top: -12px;
    margin-bottom: 35px;
    font-size: 18px;
}


.question-options {
    display: flex;
    gap: 25px;
}

.question-options span {
    background-color: #f0f0f0;
    padding: 5px 25px;
    border-radius: 5px;
}


.edit-delete {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 10px;
}

.edit-delete span {
    cursor: pointer;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 5px;
}


.edit-delete .edit {
    font-family: 'Junge', sans-serif;
    background-color: white;
    color: #333;
    font-size: 18.1px;
    font-weight: bold;
    margin-right: -1px;
    line-height: 29px;
    border: none;
    cursor: pointer;
}



.edit-delete .delete {
    font-family: 'Junge', sans-serif;
    background-color: white;
    color: red;
    font-size: 18.1px;
    font-weight: bold;
    margin-right: -1px;
    line-height: 29px;
    border: none;
    cursor: pointer;
}


.edit-delete span:hover {
    opacity: 0.8;
}


.button {
    background-color: #191970;
    color: white;
    border: none;
    padding: 15px 25px;
    font-size: 19px;
    cursor: pointer;
    border-radius: 5px;
    margin-right: 10px;
}

.button:focus {
    outline: none;
}


.button-container {
    position: absolute;
    top: -60px;
    right: 0;
    display: flex;
    gap: 10px;
}


.modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: white;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 400px;
    text-align: center;
    border-radius: 10px;
}

.modal-content input[type="date"],
.modal-content input[type="time"] {
    padding: 10px;
    width: 80%;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.success-modal {
    display: none;
    position: fixed;
    z-index: 2;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}


.success-modal {
    display: none;
    position: fixed;
    z-index: 2;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}


.success-content {
    background-color: #2a2374;
    color: white;
    margin: 12% auto;
    padding: 30px;
    width: 580px;
    border-radius: 20px;
    text-align: center;
    position: relative;
}


.success-content h2 {
    font-size: 2.5rem;
    font-family: 'Junge', sans-serif;
    letter-spacing: 2px;
    margin-bottom: 20px;
    color: white;
}


.code-container {
    background-color: white;
    padding: 5px;
    border-radius: 10px;
    margin-bottom: 20px;
    width: 570px;
    height: 106px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
}


.code-container input[type="text"] {
    width: calc(100% - 60px);
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 20px;
    text-align: center;
    color: gray;
    background-color: white;
    margin-top: 5px;
}


.code-container label {
    display: block;
    font-weight: bold;
    font-size: 1.5rem;
    color: black;
    margin-bottom: 5px;
    text-align: left;
}


.copy-icon {
    position: absolute;
    right: 65px;
    cursor: pointer;
    font-size: 35px;
    color: #191970;
}


.home-button {
    margin-top: 20px;
    padding: 15px;
    width: 180px;
    border: none;
    border-radius: 10px;
    background-color: #1c73c6;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    display: inline-block;
    align-items: center;
    justify-content: center;
}

.add-question-container {
    position: fixed;
    bottom: 70px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 10px;
    background-color: #ffffff;
    padding: 10px 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}


.add-question-container label {
    font-size: 16px;
    color: #333;
}


.add-question-container input[type="text"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 15px;
    width: 200px;
    font-size: 16px;
}


.add-question-container .add-button {
    background-color: white;
    color: #191970;
    border: 2px solid #191970;
    border-radius: 50%;
    padding: 10px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 25px;
}

.correct {
    color: green;
 
}

.correct::after {
    content: ' ✔';

    color: green;
}

.incorrect {
    color: red;
   
}

.incorrect::after {
    content: ' ✖';

    color: red;
}

#assignButton {
    margin-top: 230px;
    margin-right: 455px;
}

#publishButton{
    margin-top: 25px;
}

.questions-wrapper {
    max-height: 400px; 
    max-width: 995px;
    margin-top: 260px;
    margin-left: 480px;
    overflow-y: auto; 
    padding-right: 20px; 
    width: 100%; 
    box-sizing: border-box; 
}

        </style>
</head>
<body>

<div class="summary-container">

<?php if (!empty($questions)): ?>
    <div class="questions-wrapper"> <!-- Wrapper for the questions -->
        <?php foreach ($questions as $index => $question): ?>
            <div class="question-container">
                <h3><?php echo ($index + 1) . ". " . htmlspecialchars($question['question']); ?></h3>
                <div class="question-options">
                    <span class="<?php echo $question['correct_answer'] == 1 ? 'correct' : 'incorrect'; ?>">
                        <?php echo htmlspecialchars($question['option1']); ?>
                    </span>
                    <span class="<?php echo $question['correct_answer'] == 2 ? 'correct' : 'incorrect'; ?>">
                        <?php echo htmlspecialchars($question['option2']); ?>
                    </span>
                    <span class="<?php echo $question['correct_answer'] == 3 ? 'correct' : 'incorrect'; ?>">
                        <?php echo htmlspecialchars($question['option3']); ?>
                    </span>

                    <div class="edit-delete">
                        <form method="GET" action="editquiz.php" style="display:inline;">
                            <input type="hidden" name="quiz_title" value="<?php echo htmlspecialchars($quizTitle); ?>">
                            <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
                            <input type="hidden" name="question_no" value="<?php echo ($index + 1); ?>"> <!-- Set question number -->
                            <button type="submit" class="edit">EDIT</button>
                        </form>
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
                            <button type="submit" class="delete" onclick="return confirm('Are you sure you want to delete this question?');">DELETE</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No questions found for this creator.</p>
<?php endif; ?>

<div class="add-question" onclick="goToNextPage()"></div>


<div class="button-container">
    <button class="button" id="assignButton">Assign</button>
</div>

<!-- Modal for assigning quiz -->
<div id="assignModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeAssign">&times;</span>
        <h3>Select a Date</h3>
        <form action="" method="post" id="deadlineForm">
            <input type="date" name="deadline_date" required>
            <input type="time" name="deadline_time" required>
            <button class="button" id="publishButton">
                Publish <i class="fas fa-save"></i>
            </button>
        </form>
    </div>
</div>

<div id="successModal" class="success-modal" style="display: none;">
    <div class="success-content">
        <div class="success-header">
            <h2>SUCCESS!!!</h2>
        </div>
        <div class="code-container">
            <label for="quizCode">HERE'S YOUR CODE:</label>
            <input type="text" id="quizCode" readonly>
            <span class="copy-icon" id="copyCode"><i class="fas fa-copy"></i></span>
        </div>
        <a href="landingpage.php">
            <button class="home-button" id="homeButton">
                <i class="fas fa-home"></i> HOME
            </button>
        </a>
    </div>
</div>

<!-- Modal Scripts -->
<script>
    const assignButton = document.getElementById("assignButton");
    const assignModal = document.getElementById("assignModal");
    const closeAssign = document.getElementById("closeAssign");

    assignButton.addEventListener("click", () => {
        assignModal.style.display = "block";
    });

    closeAssign.addEventListener("click", () => {
        assignModal.style.display = "none";
    });

    const publishButton = document.getElementById("publishButton");
    const successModal = document.getElementById("successModal");
    const homeButton = document.getElementById("homeButton");
    const copyCode = document.getElementById("copyCode");
    const quizCode = document.getElementById("quizCode");

    publishButton.addEventListener("click", () => {
        successModal.style.display = "block";
    });

    homeButton.addEventListener("click", () => {
        successModal.style.display = "none";
    });

    copyCode.addEventListener("click", () => {
        quizCode.select();
        document.execCommand("copy");
        alert("Quiz Code copied to clipboard!");
    });

    function goToNextPage() {
                window.location.href = 'createquiz.php';
            }
</script>

</body>
</html>
