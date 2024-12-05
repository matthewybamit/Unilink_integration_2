<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['fullname'] = $_POST['fullname'] ?? '';
    $_SESSION['strand'] = $_POST['strand'] ?? '';
    $_SESSION['section'] = $_POST['section'] ?? '';

    header("Location: play_question.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Enter Information</title>
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

    .enter-info-container {
      position: relative;
      width: 100%;
      max-width: 100%;
      height: 91vh;
      background: url('bodybackgroundenterinfo.jpg') no-repeat center center;
      background-size: contain;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .form-content {
      width: 100%;
      height: 100%;
      position: relative;
    }

    .textbox-img {
      position: absolute;
      width: 600px; 
      height: 80px; 
      background: url('Textbox.jpg') no-repeat center center;
      background-size: contain;
    }

    .textbox-img.full-name {
      top: 230px; 
      left: 50%;
      transform: translateX(-50%);
    }

    .textbox-img.strand {
      top: 390px; 
      left: 50%;
      transform: translateX(-50%);
    }

    .textbox-img.section {
      top: 560px; 
      left: 50%;
      transform: translateX(-50%);
    }

    input.textbox {
      position: absolute;
      width: 580px;
      height: 70px; 
      left: 50%;
      transform: translateX(-50%);
      background-color: transparent; 
      border: none; 
      color: black; 
      font-size: 26px; 
      text-align: center; 
      z-index: 2; 
      outline: none; 
    }

    input.textbox::placeholder {
      color: rgba(24, 3, 3, 0.7); 
    }

    input.textbox:focus::placeholder {
      color: transparent; 
    }

    input.textbox.full-name {
      top: 240px; 
    }

    input.textbox.strand {
      top: 400px; 
    }

    input.textbox.section {
      top: 570px; 
    }

    .play-button-container {
      position: absolute;
      bottom: 60px; 
      left: 50%; 
      transform: translateX(-50%);
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .play-button-container img {
      width: 190px; 
      height: auto; 
      position: relative; 
      z-index: 1; 
      transition: transform 0.1s ease-in-out; 
    }

    .play-button-container img:hover {
      transform: scale(1.05); 
    }

    .play-button-container .play-text {
      position: absolute;
      top: 50%; 
      left: 50%; 
      transform: translate(-50%, -50%); 
      font-size: 21px; 
      color: white; 
      text-shadow: 2px 2px 0px #000; 
      z-index: 2; 
      pointer-events: none; 
      transition: transform 0.1s ease-in-out;
    }

    .play-button-container:hover .play-text {
      transform: translate(-50%, -50%) scale(1.05); 
    }
  </style>
</head>
<body>
  <div class="enter-info-container">
    <div class="form-content">
      <form action="" method="post">   
        <div class="textbox-img full-name"></div>
        <input class="textbox full-name" type="text" name="fullname" placeholder="Enter your full name" required>

        <div class="textbox-img strand"></div>
        <input class="textbox strand" type="text" name="strand" placeholder="Enter your strand" required>

        <div class="textbox-img section"></div>
        <input class="textbox section" type="text" name="section" placeholder="Enter your section" required>
        
        <div class="play-button-container">
          <button type="submit" style="background: transparent; border: none;">
            <img src="playbutton.jpg" alt="Play Button">
            <div class="play-text">PLAY</div>
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
