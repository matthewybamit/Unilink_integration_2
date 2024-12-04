<?php

require_once 'notes_pdo.php';  // Include your database connection and functions
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    // If no session, redirect to sign_up.php
        header("Location: sign_up.php");
        exit();
}

// Get the search query from the AJAX request
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Get notes for the logged-in user, passing the search query
$connection = require_once 'notes_pdo.php';  // Reuse the connection instance
$notes = $connection->getNotes($_SESSION['uid'], $searchQuery);

// Generate the HTML for the filtered notes
foreach ($notes as $note) {
    echo '<div class="note">
            <div class="subject" id="subject-' . $note['id'] . '">
                <a href="javascript:void(0);" onclick="openModal(' . $note['id'] . ')">' . htmlspecialchars($note['subject']) . '</a>
            </div>
            <div class="content" id="content-' . $note['id'] . '">' . htmlspecialchars($note['content']) . '</div>
            <small>' . date('d/m/Y H:i', strtotime($note['created_at'])) . '</small>
            <form action="notes_delete.php" method="post" onsubmit="confirmDeletion(event)">
                <input type="hidden" name="id" value="' . $note['id'] . '">
                <button type="submit" class="close">X</button>
            </form>
        </div>';
}
?>
