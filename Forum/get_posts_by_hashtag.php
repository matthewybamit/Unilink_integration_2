<?php
// Check if a hashtag is passed as a query parameter
$hashtagFilter = isset($_GET['hashtag']) ? $_GET['hashtag'] : '';

// Build the SQL query
$sql = "
    SELECT p.id AS post_id, p.content AS post_content, p.username AS post_username, p.created_at AS post_created_at,
           p.user_image AS post_user_image, p.user_id AS post_user_id, p.image AS post_image, COUNT(l.id) AS like_count,
           c.id AS comment_id, c.content AS comment_content, c.username AS comment_username, c.created_at AS comment_created_at,
           c.user_image AS comment_user_image, c.user_id AS comment_user_id, 
           r.id AS reply_id, r.content AS reply_content, r.username AS reply_username, r.reply_created_at AS reply_created_at,
           r.user_image AS reply_user_image, r.user_id AS reply_user_id,
           GROUP_CONCAT(h.hashtag ORDER BY h.hashtag) AS post_hashtags
    FROM forum_posts p
    LEFT JOIN post_hashtags ph ON p.id = ph.post_id
    LEFT JOIN hashtags h ON ph.hashtag_id = h.id
    LEFT JOIN post_likes l ON p.id = l.post_id
    LEFT JOIN comments c ON p.id = c.post_id
    LEFT JOIN replies r ON c.id = r.comment_id
";

// If a hashtag is selected, add a WHERE clause to filter the posts by hashtag
if ($hashtagFilter) {
    $sql .= " WHERE h.hashtag LIKE :hashtag"; // Filter posts based on the hashtag itself
}

// Group by the post ID to aggregate the results
$sql .= " GROUP BY p.id ORDER BY p.created_at DESC";

// Prepare and execute the query
$stmt = $pdo->prepare($sql);

// Bind the hashtag parameter if applicable
if ($hashtagFilter) {
    $stmt->bindValue(':hashtag', '%' . $hashtagFilter . '%');  // Use LIKE with wildcard for matching
}

$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
