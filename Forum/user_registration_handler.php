<?php
include 'database_connection.php'; // Include your DB connection file

// Get JSON data from the fetch request
$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'];
$profilePicture = $data['profilePicture'];
$email = $data['email'];
$uid = $data['uid'];

// Check if the user already exists in the database
$stmt = $pdo->prepare('SELECT * FROM users WHERE uid = ?');
$stmt->execute([$uid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // User already exists, respond with success
    echo json_encode(['success' => true]);
} else {
    // Insert new user into the database
    $stmt = $pdo->prepare('INSERT INTO users (username, profile_picture, email, uid) VALUES (?, ?, ?, ?)');
    $result = $stmt->execute([$username, $profilePicture, $email, $uid]);

    if ($result) {
        // User inserted successfully
        echo json_encode(['success' => true]);
    } else {
        // Failed to insert user
        echo json_encode(['success' => false]);
    }
}
?>
