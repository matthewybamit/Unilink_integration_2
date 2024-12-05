<?php
session_start();

unset($_SESSION['strand']);
unset($_SESSION['difficulty']);
unset($_SESSION['user_answers']);

header("Location: strand.php");
exit();
?>