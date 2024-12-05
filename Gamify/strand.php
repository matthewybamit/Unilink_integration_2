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
      width: 90%;
      max-width: 1200px;
      height: 90vh;
      background: url('strandcontainer.jpg') no-repeat center center;
      background-size: contain;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .strand-buttons {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      grid-gap: 40px;
      justify-content: center;
      align-items: center;
    }

    .strand-button {
      width: 450px;
      height: 200px;
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
      cursor: pointer;
      transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .strand-button.abm {
      background-image: url('strandabm.jpg');
    }
    .strand-button.stem {
      background-image: url('strandstem.jpg');
    }
    .strand-button.humss {
      background-image: url('strandhumss.jpg');
    }
    .strand-button.gas {
      background-image: url('strandgas.jpg');
    }

    .strand-button:hover {
      transform: scale(1.1);
      opacity: 0.9;
    }

    .strand-button a {
      display: block;
      width: 100%;
      height: 100%;
    }
  </style>
</head>
<body>
  <div class="enter-info-container">
    <div class="strand-buttons">
      <div class="strand-button abm">
      <a href="difficulties.php?strand=abm"></a>
      </div>
      <div class="strand-button stem">
      <a href="difficulties.php?strand=stem"></a>
      </div>
      <div class="strand-button humss">
      <a href="difficulties.php?strand=humss"></a>
      </div>
      <div class="strand-button gas">
      <a href="difficulties.php?strand=gas"></a>
      </div>
    </div>
  </div>
</body>
</html>
