<?php
$host = 'localhost';
$dbname = 'gym_fitness_class_scheduling_and_enrollment_system';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>