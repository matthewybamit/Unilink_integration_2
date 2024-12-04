<?php
session_start();  // Start the session
if (isset($_POST['sort'])) {
    $_SESSION['sort'] = $_POST['sort'];  // Update the session sort value
}
?>