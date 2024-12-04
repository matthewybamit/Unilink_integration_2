<?php
session_start();
header('Content-Type: application/json');

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

// Ensure the required data is present
if (isset($data['username'], $data['profilePicture'], $data['email'], $data['uid'])) {

    // Store user data in session
    $_SESSION['username'] = $data['username'];
    $_SESSION['profilePicture'] = $data['profilePicture'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['uid'] = $data['uid'];

    // Database connection
    $servername = 'localhost'; // e.g. "localhost"
    $dbname = 'unilink_database';
    $username = 'root';
    $password = '';

    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE uid = :uid OR email = :email");
        $stmt->execute(['uid' => $data['uid'], 'email' => $data['email']]);
        $user = $stmt->fetch();

        if ($user) {
            // Check if the user is banned
            if ($user['status'] === 'Banned') {
                echo json_encode(['success' => false, 'message' => 'Your account is banned.']);
                exit(); // Stop further execution
            }
        } else {
            // If the user does not exist, insert a new record
            $stmt = $pdo->prepare("INSERT INTO users (uid, username, email, profile_picture) VALUES (:uid, :username, :email, :profile_picture)");
            $stmt->execute([
                'uid' => $data['uid'],
                'username' => $data['username'],
                'email' => $data['email'],
                'profile_picture' => $data['profilePicture']
            ]);
        }

        // Send success response
        echo json_encode(['success' => true]);

    } catch (PDOException $e) {
        // If there is a database error, send error response
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }

} else {
    // If the required data is not set, send error response
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}
?>
