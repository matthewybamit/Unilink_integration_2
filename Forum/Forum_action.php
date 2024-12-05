<?php
session_start();

// Check if the user is logged in by verifying if 'uid' is set in the session
if (!isset($_SESSION['uid'])) {
    // Return a JSON error message and redirect to sign_up.php
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    
    // Redirect to sign-up page after a short delay (optional)
    header("Location: sign_up.php");
    exit; // Stop further execution
}
$userId = $_SESSION['uid'];  // Get user ID from session

// Generate a unique token for form submission if it doesn’t exist
if (!isset($_SESSION['form_token'])) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}

// Database connection settings
$host = 'localhost';
$dbname = 'unilink_database';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}




//////////////////////////////////////////////////////
//////////////////////////////////////////////////////



// Function to fetch notifications
function fetchNotifications($user_id) {
    global $pdo;

    $notifications = [];

    // Fetch new likes on user's posts
    $stmt = $pdo->prepare("
        SELECT fp.id AS post_id, fp.content AS post_content, fp.image AS post_image, COUNT(pl.id) AS like_count
        FROM forum_posts fp
        LEFT JOIN post_likes pl ON fp.id = pl.post_id
        WHERE fp.user_id = :user_id
          AND (pl.created_at > COALESCE((SELECT last_checked FROM users WHERE id = :user_id), '0000-00-00 00:00:00'))
        GROUP BY fp.id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $notifications['likes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch new comments on user's posts
    $stmt = $pdo->prepare("
        SELECT fp.id AS post_id, fp.content AS post_content, fp.image AS post_image, COUNT(c.id) AS comment_count
        FROM forum_posts fp
        LEFT JOIN comments c ON fp.id = c.post_id
        WHERE fp.user_id = :user_id
          AND (c.created_at > COALESCE((SELECT last_checked FROM users WHERE id = :user_id), '0000-00-00 00:00:00'))
        GROUP BY fp.id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $notifications['comments'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch new replies to user's comments
    $stmt = $pdo->prepare("
        SELECT c.id AS comment_id, c.content AS comment_content, COUNT(r.id) AS reply_count
        FROM comments c
        LEFT JOIN replies r ON c.id = r.comment_id
        WHERE c.user_id = :user_id
          AND (r.reply_created_at > COALESCE((SELECT last_checked FROM users WHERE id = :user_id), '0000-00-00 00:00:00'))
        GROUP BY c.id
    ");
    $stmt->execute(['user_id' => $user_id]);
    $notifications['replies'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total notifications
    $totalNotifications = 0;
    if ($notifications['likes']) {
        $totalNotifications += count($notifications['likes']);
    }
    if ($notifications['comments']) {
        $totalNotifications += count($notifications['comments']);
    }
    if ($notifications['replies']) {
        $totalNotifications += count($notifications['replies']);
    }

    // Add total notification count to the response
    $notifications['totalCount'] = $totalNotifications;

    return $notifications;
}

// Function to update last checked timestamp
function updateLastChecked($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET last_checked = NOW() WHERE id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
}

// Check if notifications are requested
if (isset($_GET['fetch_notifications']) && isset($_SESSION['uid'])) {
    $user_id = $_SESSION['uid'];
    $notifications = fetchNotifications($user_id);
    updateLastChecked($user_id); // Update last checked time after fetching
    echo json_encode($notifications); // Return notifications as JSON
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['uid']) && isset($_POST['mark_seen'])) {
    $user_id = $_SESSION['uid'];

    // Update the last viewed timestamp
    $stmt = $pdo->prepare("UPDATE users SET last_viewed_notifications_at = NOW() WHERE uid = ?");
    $stmt->execute([$user_id]);

    echo json_encode(['status' => 'success', 'message' => 'Notifications marked as viewed.']);
    exit;
}










//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

try {
    // Get the selected time period from the request or default to 'day'
    $timePeriod = isset($_GET['timePeriod']) ? $_GET['timePeriod'] : 'day';

    // Determine the time range based on the selected period
    switch ($timePeriod) {
        case 'week':
            $dateCondition = "DATE(p.created_at) >= CURDATE() - INTERVAL 7 DAY";
            break;
        case 'month':
            $dateCondition = "DATE(p.created_at) >= CURDATE() - INTERVAL 1 MONTH";
            break;
        case 'day':
        default:
            $dateCondition = "DATE(p.created_at) = CURDATE()";
            break;
    }

    // Adjust the query to filter posts based on the selected time period
    $stmt = $pdo->prepare("
        SELECT h.hashtag, COUNT(ph.hashtag_id) AS count 
        FROM hashtags h
        JOIN post_hashtags ph ON h.id = ph.hashtag_id
        JOIN forum_posts p ON ph.post_id = p.id
        WHERE $dateCondition 
        GROUP BY h.hashtag 
        ORDER BY count DESC 
        LIMIT 10
    ");
    
    $stmt->execute();
    $popularHashtags = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $popularHashtags = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_token']) && $_POST['form_token'] === $_SESSION['form_token']) {
        $_SESSION['form_token'] = bin2hex(random_bytes(32)); // Reset token to prevent resubmission

        if (isset($_POST['post_content'])) {
            $username = $_SESSION['username'] ?? 'Guest';
            $userId = $_SESSION['uid'] ?? null;
            $content = htmlspecialchars(trim($_POST['post_content']));
            $imagePaths = []; // Array to hold the paths of uploaded images

            // Check if files are uploaded
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize = 10 * 1024 * 1024;  // 10MB max size

                $uploadDir1 = 'C:/xampp/htdocs/Unilink_integration_2/Forum/images/';
                $uploadDir2 = 'C:/xampp/htdocs/Unilink-admin/Images/';
                $relativePath = 'images/';

                // Loop through each uploaded image
                foreach ($_FILES['images']['name'] as $key => $imageName) {
                    $fileTmpName = $_FILES['images']['tmp_name'][$key];

                    // Ensure file exists and is not empty before using mime_content_type
                    if (!empty($fileTmpName) && file_exists($fileTmpName)) {
                        $fileType = mime_content_type($fileTmpName);
                        $fileSize = $_FILES['images']['size'][$key];

                        // Validate file type and size
                        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxFileSize) {
                            $uniqueName = uniqid() . '_' . basename($imageName);

                            $imagePath1 = $uploadDir1 . $uniqueName;
                            $imagePath2 = $uploadDir2 . $uniqueName;
                            $imageRelativePath = $relativePath . $uniqueName;

                            if (!move_uploaded_file($fileTmpName, $imagePath1)) {
                                echo json_encode(['status' => 'error', 'message' => 'Failed to save the uploaded image in the first directory.']);
                                exit;
                            }

                            if (!is_writable($uploadDir2)) {
                                echo json_encode(['status' => 'error', 'message' => 'Second directory is not writable.']);
                                exit;
                            }

                            if (!copy($imagePath1, $imagePath2)) {
                                echo json_encode(['status' => 'error', 'message' => 'Failed to copy the uploaded image to second directory.']);
                                exit;
                            }

                            // Store image relative path in the database
                            $imagePaths[] = $imageRelativePath;
                        } else {
                            echo json_encode(['status' => 'error', 'message' => 'Invalid file type or file too large for one of the images.']);
                            exit;
                        }
                    } else {
                        // Only show this error if files are uploaded but still empty
                        echo json_encode(['status' => 'error', 'message' => 'Uploaded file is missing or empty.']);
                        exit;
                    }
                }
            }

            try {
                // Insert the post into the database
                $stmt = $pdo->prepare("INSERT INTO forum_posts (user_id, username, content, user_image, created_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$userId, $username, $content, $_SESSION['profilePicture'] ?? 'images/default-avatar.png']);
            
                // Correct usage of lastInsertId()
                $postId = $pdo->lastInsertId(); // Get the ID of the inserted post
            
                // Insert images into the post_images table only if there are images
                if (!empty($imagePaths)) {
                    foreach ($imagePaths as $imagePath) {
                        $stmt = $pdo->prepare("INSERT INTO post_images (post_id, image_path) VALUES (?, ?)");
                        $stmt->execute([$postId, $imagePath]);
                    }
                }
            
                // Extract hashtags from the content and insert them into the database as needed
                preg_match_all('/#(\w+)/', $content, $matches);
                $hashtags = $matches[1];
            
                foreach ($hashtags as $tag) {
                    $stmt = $pdo->prepare("SELECT id FROM hashtags WHERE hashtag = ?");
                    $stmt->execute([$tag]);
                    $hashtagId = $stmt->fetchColumn();
            
                    if (!$hashtagId) {
                        $stmt = $pdo->prepare("INSERT INTO hashtags (hashtag) VALUES (?)");
                        $stmt->execute([$tag]);
                        $hashtagId = $pdo->lastInsertId(); // Correct usage here as well
                    }
            
                    $stmt = $pdo->prepare("INSERT INTO post_hashtags (post_id, hashtag_id) VALUES (?, ?)");
                    $stmt->execute([$postId, $hashtagId]);
                }
            
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error occurred while saving the post.', 'details' => $e->getMessage()]);
                exit;
            }
        }





     // Handle comment submission
if (isset($_POST['comment_content']) && isset($_POST['post_id'])) {
    $postId = (int)$_POST['post_id'];
    $commentUsername = $_SESSION['username'] ?? 'Guest';
    $commentContent = htmlspecialchars(trim($_POST['comment_content']));
    $commentUserImage = $_SESSION['profilePicture'] ?? 'images/default-avatar.png';

    try {
        // Insert the comment into the database
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, username, content, user_image, comment_created_at, parent_comment_id) VALUES (?, ?, ?, ?, ?, NOW(), NULL)");
        $stmt->execute([$postId, $userId, $commentUsername, $commentContent, $commentUserImage]);

      
        // Get the comment's created time from the database
        $commentId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT comment_created_at FROM comments WHERE id = ?");
        $stmt->execute([$commentId]);
        $commentTime = $stmt->fetchColumn();

        // Format the timestamp
        $commentCreatedAt = date('c', strtotime($commentTime));

        // Return the new comment data as JSON
        echo json_encode([
            'status' => 'success',
            'new_comment' => [
                'id' => $commentId,
                'username' => $commentUsername,
                'content' => $commentContent,
                'user_image' => $commentUserImage,
                'created_at' => $commentCreatedAt
            ]
        ]);

        
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error publishing comment: ' . $e->getMessage()]);
        exit;
    }
}


      // Handle reply submission
if (isset($_POST['reply_content']) && isset($_POST['comment_id'])) {
    $commentId = (int)$_POST['comment_id'];
    $replyContent = htmlspecialchars(trim($_POST['reply_content']));
    $replyUsername = $_SESSION['username'] ?? 'Guest';
    $replyUserImage = $_SESSION['profilePicture'] ?? 'images/default-avatar.png';

    // Ensure the user is logged in
    if (!isset($_SESSION['uid'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        exit;
    }

    // Check if the comment exists
    $stmt = $pdo->prepare("SELECT id FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);
    $commentExists = $stmt->fetchColumn();
    if (!$commentExists) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid comment ID.']);
        exit;
    }

    try {
        // Insert the reply into the replies table
        $stmt = $pdo->prepare("INSERT INTO replies (comment_id, user_id, username, content, user_image, reply_created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$commentId, $userId, $replyUsername, $replyContent, $replyUserImage]);

        // Get the reply's created time from the database
        $replyId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT reply_created_at FROM replies WHERE id = ?");
        $stmt->execute([$replyId]);
        $replyTime = $stmt->fetchColumn();

        // Format the timestamp
        $replyCreatedAt = date('c', strtotime($replyTime));

        // Return the new reply as JSON
        echo json_encode([
            'status' => 'success',
            'new_reply' => [
                'id' => $replyId,
                'username' => $replyUsername,
                'content' => $replyContent,
                'user_image' => $replyUserImage,
                'created_at' => $replyCreatedAt
            ]
        ]);

        
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error publishing reply: ' . $e->getMessage()]);
        exit;
    }
}

    }
}





// Handle post deletion
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['post_id'])) {
    $postId = (int)$_POST['post_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM forum_posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
    
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Post deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Post not found or permission denied.']);
        }
    } catch (PDOException $e) {
        error_log("Error deleting post: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error deleting post: ' . $e->getMessage()]);
    }
    exit;
}
// Fetch posts and their comments
try {
    $stmt = $pdo->query("SELECT * FROM forum_posts ORDER BY created_at DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch comments for each post
    $comments = [];
    foreach ($posts as $post) {
        $postId = $post['id'];
        $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY comment_created_at DESC");
        $stmt->execute([$postId]);
        $comments[$postId] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch replies for each comment (oldest first)
            foreach ($comments[$postId] as $key => $comment) {
                $commentId = $comment['id'];
                $stmt = $pdo->prepare("SELECT * FROM replies WHERE comment_id = ? ORDER BY reply_created_at ASC"); // ASC for oldest first
                $stmt->execute([$commentId]);
                $comments[$postId][$key]['replies'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching posts: ' . $e->getMessage()]);
    exit;
}


// Check if sorting preference is set in session, otherwise default to 'new' 
if (isset($_SESSION['sort'])) {
    $sort = $_SESSION['sort'];
} else {
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'new';
    $_SESSION['sort'] = $sort; // Save the sort preference to session
}
if ($sort === 'hot') {
    // Get the current date and time minus 24 hours
    $timeLimit = (new DateTime())->modify('-24 hours')->format('Y-m-d H:i:s');

    // Get posts with the most likes in the last 24 hours, but also include total like count
    $query = 'SELECT forum_posts.*, 
                     COUNT(all_likes.id) AS total_like_count, 
                     COUNT(recent_likes.id) AS recent_like_count 
              FROM forum_posts 
              LEFT JOIN post_likes AS all_likes ON forum_posts.id = all_likes.post_id 
              LEFT JOIN post_likes AS recent_likes ON forum_posts.id = recent_likes.post_id 
                                                      AND recent_likes.created_at >= :time_limit 
              GROUP BY forum_posts.id 
              ORDER BY recent_like_count DESC';

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':time_limit', $timeLimit); // bind the time limit for likes
    $stmt->execute();

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} elseif ($sort === 'popular') {
    // Sort by like_count in descending order (popular)
    $query = 'SELECT forum_posts.*, COUNT(post_likes.id) AS like_count 
              FROM forum_posts 
              LEFT JOIN post_likes ON forum_posts.id = post_likes.post_id 
              GROUP BY forum_posts.id 
              ORDER BY like_count DESC';

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} elseif ($sort === 'commented') {
    // Sort by comment count in descending order (most commented)
    $query = 'SELECT forum_posts.*, COUNT(comments.id) AS comment_count 
              FROM forum_posts 
              LEFT JOIN comments ON forum_posts.id = comments.post_id 
              GROUP BY forum_posts.id 
              ORDER BY comment_count DESC';

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    // Default sorting is by newest posts
    $query = 'SELECT * FROM forum_posts ORDER BY created_at DESC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}




//elseif ($sort === 'hot') {
    // Get the current date and time minus 24 hours
    //$timeLimit = (new DateTime())->modify('-24 hours')->format('Y-m-d H:i:s');
    // Get posts with the most likes in the last 24 hours (even if the post is old)
   // $query = 'SELECT forum_posts.*, COUNT(post_likes.id) AS like_count 
           //   FROM forum_posts 
             // LEFT JOIN post_likes ON forum_posts.id = post_likes.post_id 
             // WHERE post_likes.created_at >= :time_limit 
            //  GROUP BY forum_posts.id 
            //  ORDER BY like_count DESC';
   // $stmt = $pdo->prepare($query);
   // $stmt->bindParam(':time_limit', $timeLimit); // bind the time limit for likes
   // $stmt->execute();

//// Get the sort preference from session or GET request
//if (isset($_SESSION['sort'])) {
    //$sort = $_SESSION['sort'];
//} else {
   // $sort = isset($_GET['sort']) ? $_GET['sort'] : 'new';
    //$_SESSION['sort'] = $sort; // Save the sort preference to session
//}

// Check if a hashtag is selected via GET request
//if (isset($_GET['hashtag'])) {
 //   $_SESSION['selectedHashtag'] = $_GET['hashtag']; // Store the selected hashtag in the session
//} else {
    // If no hashtag is selected, unset it from the session
   // unset($_SESSION['selectedHashtag']);
//}

// Retrieve the hashtag from the session if available
//$selectedHashtag = isset($_SESSION['selectedHashtag']) ? $_SESSION['selectedHashtag'] : null;

// Build the base query for selecting posts
//$query = 'SELECT forum_posts.*, COUNT(post_likes.id) AS like_count 
        //  FROM forum_posts 
      //    LEFT JOIN post_likes ON forum_posts.id = post_likes.post_id';

// Add hashtag filter if a hashtag is selected
//if ($selectedHashtag) {
    //$query .= ' WHERE forum_posts.content LIKE :hashtag';
//}

// Add grouping by post ID
//$query .= ' GROUP BY forum_posts.id';

// Add sorting logic based on the selected sort type
//if ($sort === 'popular') {
 //   $query .= ' ORDER BY like_count DESC'; // Sort by like count (popular)
//} elseif ($sort === 'hot') {
    // Get the current date and time minus 24 hours
    //$timeLimit = (new DateTime())->modify('-24 hours')->format('Y-m-d H:i:s');
 
    // Get posts with the most likes in the last 24 hours (even if the post is old)
    //$query = 'SELECT forum_posts.*, COUNT(post_likes.id) AS like_count 
              //FROM forum_posts 
             // LEFT JOIN post_likes ON forum_posts.id = post_likes.post_id 
          //    WHERE post_likes.created_at >= :time_limit 
          //    GROUP BY forum_posts.id 
            //  ORDER BY like_count DESC';

   // $stmt = $pdo->prepare($query);
    //$stmt->bindParam(':time_limit', $timeLimit); // bind the time limit for likes
   // $stmt->execute();

//} else {
    //$query .= ' ORDER BY created_at DESC'; // Default to sorting by newest posts
//}

// Prepare and execute the query with any necessary parameters
//$stmt = $pdo->prepare($query);

// Bind parameters if a hashtag is selected
//if ($selectedHashtag) {
   // $searchTerm = '%' . $selectedHashtag . '%';
   // $stmt->bindParam(':hashtag', $searchTerm);
//}

// Bind time limit for "hot" sorting
//if ($sort === 'hot') {
   // $stmt->bindParam(':time_limit', $timeLimit);
//}

//$stmt->execute();

// Fetch the results
//$posts = $stmt->fetchAll(PDO::FETCH_ASSOC); 




?>
