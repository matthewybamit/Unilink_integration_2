<?php
header('Content-Type: application/json');
require 'db_connect.php'; // Include your DB connection

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['uid'], $data['username'], $data['post_content'])) {
    $uid = $data['uid'];
    $username = $data['username'];
    $post_content = $data['post_content'];

    $sql = "INSERT INTO posts (user_id, username, post_content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $uid, $username, $post_content);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'DB error']);
    }
}
?>
