<?php
session_start();

$uid = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
if (!$uid) {
    // Redirect to sign-up page if not logged in
    header("Location: sign_up.php");
    exit();
}

if (!isset($_GET['event_id'])) {
    die("Event ID not provided.");
}

$event_id = $_GET['event_id'];

$conn = new mysqli('localhost', 'root', '', 'unilink_database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete event only if it belongs to the user
$stmt = $conn->prepare("DELETE FROM events WHERE event_id = ? AND user_id = ?");
$stmt->bind_param("is", $event_id, $uid);
if ($stmt->execute()) {
    header('Location: taskmanager.php');
} else {
    echo "Error deleting event: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
