<?php
// db_connect.php - Centralized Database Connection Instance
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uact_event_tracker";

// Establish native MySQLi object-oriented link state channel
$conn = new mysqli($servername, $username, $password, $dbname);

// Verify live infrastructure operational integrity bounds
if ($conn->connect_error) {
    die("Critical Failure: Unable to bind database service connection instance: " . $conn->connect_error);
}
?>