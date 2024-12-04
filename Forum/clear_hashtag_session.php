<?php


// Check if a sort type is set and update the session accordingly
if (isset($_GET['sort'])) {
    // Set the sort type in the session
    $_SESSION['sort'] = $_GET['sort'];
    
    // Clear the selected hashtag from the session (since we are changing the sort)
    unset($_SESSION['selectedHashtag']);
}

// Return a JSON response indicating success
echo json_encode(['status' => 'success']);
?>
