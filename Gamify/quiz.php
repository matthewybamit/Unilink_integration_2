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
      width: 100%;
      max-width: 100%;
      height: 91vh;
      background: url('quizcontainerbox.jpg') no-repeat center center;
      background-size: contain;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .question-text {
      position: absolute;
      top: 100px;
      left: 520px;
      width: calc(100% - 40px);
      color: black;
      text-align: left;
      line-height: 25.5;
    }

    .question-text .title {
      font-size: 16px;
      margin-bottom: -200px;
    }

    .question-text .question {
      font-size: 14px;
    }

    .answers {
      position: absolute;
      bottom: 290px;
      right: 480px;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .answers img {
      width: 420px;
      padding: 10px;
      cursor: pointer;
      border-radius: 5px;
    }

    .answers img:hover {
      transform: scale(1.1);
      transition: 0.3s ease-in-out;
    }
  </style>
</head>
<body>
  <div class="quiz-container">
    <div class="question-text">
      <div class="title">QUESTION 1</div>
      <div class="question">WHAT IS THE TASTE OF VINEGAR?</div>
    </div>
    <div class="answers">
      <img src="sweetbox.jpg" alt="Sweet">
      <img src="sourbox.jpg" alt="Sour" onclick="goToNextPage()">
      <img src="bitterbox.jpg" alt="Bitter">
    </div>
  </div>
  <script>
    function goToNextPage() {
      window.location.href = 'quizscore.html';
    }
  </script>
</body>
</html>