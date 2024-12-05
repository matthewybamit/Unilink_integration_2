<?php
$servername = "localhost";
$username = "root";
$password = '';
$dbname = 'schoolinfo_db'; // You forgot to define this variable

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
