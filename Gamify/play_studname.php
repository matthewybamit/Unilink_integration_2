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
    <title>UNILINK</title>
    <link href="https://fonts.googleapis.com/css?family=Junge" rel="stylesheet">
    <link rel="stylesheet" href="css/play_studname.css">
</head>

<body class="index2">
    <nav>
        <h1>UNILINK</h1>
    </nav>

        <div class="container">
  <form action="" method="post">   
    <label for="fullname">Full Name:</label>
    <input type="text" placeholder="Enter your name here" name="fullname" required><br><br>

    <label for="strand">Strand:</label>
    <input type="text" placeholder="Enter your strand here" id="strand" name="strand" required><br><br>

    <label for="section">Section:</label>
    <input type="text" placeholder="Enter your section here" id="section" name="section" required><br><br>
    
    <button type="submit">►</button>
  </form>
</div>

</body>
</html>