<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: sign_up.php');
    exit;
}

$connection = require_once 'notes_pdo.php';
$note = $connection->getNoteById($_POST['id']);

if ($note && $note['user_id'] === $_SESSION['uid']) {
    $connection->removeNote($_POST['id']);
}

header('Location: notes.php');
