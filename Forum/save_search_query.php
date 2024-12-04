<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
    $_SESSION['search_query'] = trim($_POST['query']);
}
?>
