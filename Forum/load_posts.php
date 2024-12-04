<?php
require 'db_connect.php'; // Replace with your DB connection script

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = 5; // Number of posts to load per request

try {
    $query = $pdo->prepare("
        SELECT * 
        FROM forum_posts 
        ORDER BY created_at DESC 
        LIMIT :limit OFFSET :offset
    ");

    // Use bindValue instead of bindParam for constant values
    $query->bindValue(':limit', $limit, PDO::PARAM_INT);
    $query->bindValue(':offset', $offset, PDO::PARAM_INT);

    $query->execute();

    $posts = $query->fetchAll(PDO::FETCH_ASSOC);

    // Set correct header and output JSON
    header('Content-Type: application/json');
    echo json_encode($posts);

} catch (Exception $e) {
    // Handle any exceptions and output an error message
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
