<?php
session_start();

// Unset any existing session data related to the quiz
unset($_SESSION['score']);
unset($_SESSION['current_question']);
unset($_SESSION['user_answers']);
unset($_SESSION['strand']);
unset($_SESSION['difficulty']);

// Retrieve and set the selected strand
$selectedStrand = $_GET['strand'] ?? 'Default Strand';
$_SESSION['strand'] = $selectedStrand;

// Redirect to the question page if difficulty is set
if (isset($_GET['difficulty'])) {
    $_SESSION['difficulty'] = $_GET['difficulty'];
    header("Location: question.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Difficulties</title>
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

        .container {
            position: relative;
            width: 100%;
            max-width: 100%;
            height: 91vh;
            background: url('selectdifficulties.jpg') no-repeat center center;
            background-size: contain;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
        }

        .button {
            display: block;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            transition: transform 0.2s;
            width: 570px;
            height: 130px;
            color: white;
        }

        .easy {
            background: url('easybutton.jpg') no-repeat center center;
            background-size: cover;
            margin-top: -10px;
        }

        .medium {
            background: url('mediumbutton.jpg') no-repeat center center;
            background-size: cover;
            margin-top: 15px;
        }

        .hard {
            background: url('hardbutton.jpg') no-repeat center center;
            background-size: cover;
            margin-top: 15px;
        }

        .button:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="buttons">
            <!-- Easy Difficulty -->
            <a href="question.php?strand=<?php echo urlencode($selectedStrand); ?>&difficulty=easy&quiz_title=<?php echo urlencode($selectedStrand . ' QUIZ'); ?>" 
               class="button easy"></a>
            
            <!-- Medium Difficulty -->
            <a href="question.php?strand=<?php echo urlencode($selectedStrand); ?>&difficulty=medium&quiz_title=<?php echo urlencode($selectedStrand . ' QUIZ'); ?>" 
               class="button medium"></a>
            
            <!-- Hard Difficulty -->
            <a href="question.php?strand=<?php echo urlencode($selectedStrand); ?>&difficulty=hard&quiz_title=<?php echo urlencode($selectedStrand . ' QUIZ'); ?>" 
               class="button hard"></a>
        </div>
    </div>
</body>
</html>
