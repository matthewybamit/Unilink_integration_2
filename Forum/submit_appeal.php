<?php
session_start();
header('Content-Type: application/json');

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

// Ensure the required data is present
if (isset($data['uid'], $data['appealMessage'])) {
    // Database connection
    $servername = 'localhost'; // e.g. "localhost"
    $dbname = 'unilink_database';
    $username = 'root';
    $password = '';

    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert appeal message into the database (or send to admin)
        $stmt = $pdo->prepare("INSERT INTO appeals (uid, appeal_message, created_at) VALUES (:uid, :appeal_message, NOW())");
        $stmt->execute([
            'uid' => $data['uid'],
            'appeal_message' => $data['appealMessage']
        ]);

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
