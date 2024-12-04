<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $color = $_POST['color'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'unilink_database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update event in the database
    $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, start_time = ?, color = ? WHERE event_id = ?");
    $stmt->bind_param("ssssi", $title, $description, $start_time, $color, $event_id);

    // Execute and check if successful
    if ($stmt->execute()) {
        header('Location: taskmanager.php?status=success');
    } else {
        echo "Error updating event: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
