<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $query = '%' . htmlspecialchars($_GET['query']) . '%';
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE content LIKE ? OR username LIKE ?");
    $stmt->execute([$query, $query]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($posts);
    exit;
}
?>
