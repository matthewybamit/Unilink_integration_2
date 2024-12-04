<?php
session_start();
session_destroy();

// Redirect to sign_up.php after logout
header("Location: sign_up.php");
exit;
?>
