<?php
// Updates an existing class's schedule and maximum capacity.

require_once __DIR__ . '/../admin_check.php';
require_once __DIR__ . '/../db_connect.php';

$class_id = intval($_POST['class_id'] ?? 0);
$trainer_id = intval($_POST['trainer_id'] ?? 0);
$schedule_at = trim($_POST['schedule_at'] ?? '');
$max_capacity = intval($_POST['max_capacity'] ?? 0);

// Validate inputs
if ($trainer_id <= 0) {
    die('Trainer is required.');
}

if (empty($schedule_at)) {
    die('Schedule is required.');
}

if ($max_capacity <= 0) {
    die('Maximum capacity must be a positive integer.');
}

// Validate datetime format
$dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $schedule_at);
if (!$dateTime || $dateTime->format('Y-m-d\TH:i') !== $schedule_at) {
    die('Invalid schedule format.');
}

// Convert datetime-local format to MySQL format (YYYY-MM-DD HH:MM:SS)
$formattedDateTime = DateTime::createFromFormat('Y-m-d\TH:i', $schedule_at);
$scheduleAtFormatted = $formattedDateTime->format('Y-m-d H:i:s');

// Update the trainer assignment, schedule, and capacity with validated values.
$sql = 'UPDATE classes SET trainer_id = ?, schedule_at = ?, max_capacity = ? WHERE class_id = ?';
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param('isii', $trainer_id, $scheduleAtFormatted, $max_capacity, $class_id);

if (!$stmt->execute()) {
    echo 'Error: ' . $stmt->error;
    exit();
}

$stmt->close();
$conn->close();

header('Location: ../classes.php');
exit();

?>
