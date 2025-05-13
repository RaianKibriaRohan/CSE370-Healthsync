<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // update with your DB password if any
$dbname = 'healthsync';

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
