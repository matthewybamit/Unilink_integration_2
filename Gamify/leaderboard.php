<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leaderboard</title>
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

    .leaderboard-container {
      position: relative;
      width: 100%;
      max-width: 100%;
      height: 91vh;
      background: url('leaderboardcontainer.jpg') no-repeat center center;
      background-size: contain;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .row {
      width: 95%;
      max-width: 950px;
      height: 96px;
      margin-bottom: 35px;
      display: flex;
      align-items: center;
      padding: 0;
      position: relative;
      justify-content: space-between;
      background-image: url('leaderbordtextboxwithtextboxscore.jpg');
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
    }

    .row img {
      width: 35px;
      height: 40px;
      margin-left: 100px;
    }

    .name {
      font-size: 15px;
      color: gray;
      text-align: left;
      flex: 2;
      padding-left: 40px;
    }

    .score {
      font-size: 17px;
      color: white;
      text-align: left;
      flex: 1;
      margin-left: 200px;
    }

    #home-button {
      display: block;
      width: 200px;
      height: auto;
      cursor: pointer;
      transition: transform 0.3s ease;
      position: absolute;
      bottom: 50px;
      left: 50%;
      transform: translateX(-50%);
    }

    #home-button:hover {
      transform: scale(1.05) translateX(-50%);
      transition: transform 0.3s ease-in-out;
    }

  </style>
</head>
<body>
  <div class="leaderboard-container">
    <div class="row">
      <img src="gold.png" alt="Gold Medal">
      <p class="name">NAME, LASTNAME, M.I</p>
      <p class="score">10/10</p>
    </div>
    <div class="row">
      <img src="silver.png" alt="Silver Medal">
      <p class="name">NAME, LASTNAME, M.I</p>
      <p class="score">9/10</p>
    </div>
    <div class="row">
      <img src="bronze.png" alt="Bronze Medal">
      <p class="name">NAME, LASTNAME, M.I</p>
      <p class="score">8/10</p>
    </div>
    <div class="row">
      <img src="four.png" alt="Rank 4">
      <p class="name">NAME, LASTNAME, M.I</p>
      <p class="score">7/10</p>
    </div>
    <a href="home.html">
      <img id="home-button" src="leaderboardbutton.png" alt="Home Button">
    </a>
  </div>
</body>
</html>
