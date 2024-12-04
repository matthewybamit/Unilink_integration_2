<?php

/** @var Connection $connection */
$connection = require_once 'notes_pdo.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    die("Unauthorized access. Please log in.");
}

// Prepare note data, including user_id from the session
$noteData = [
    'subject' => $_POST['subject'],
    'content' => $_POST['content'],
    'user_id' => $_SESSION['uid'] // Include user_id in the note data
];

$id = $_POST['id'] ?? '';

if ($id) {
    $connection->updateNote($id, $noteData);
} else {
    $connection->addNote($noteData);
}

header('Location: notes.php');
exit;

