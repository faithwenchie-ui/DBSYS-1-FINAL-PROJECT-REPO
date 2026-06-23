<?php
// Database configuration
$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "sari-sari_store_inventory_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");
?>