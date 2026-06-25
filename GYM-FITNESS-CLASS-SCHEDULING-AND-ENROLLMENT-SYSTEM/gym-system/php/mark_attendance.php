<?php
// Records a member's attendance status into the attendance log.

require_once __DIR__ . '/../admin_check.php';
require_once __DIR__ . '/../db_connect.php';

$enrollment_id = intval($_POST['enrollment_id'] ?? 0);
$status = trim($_POST['status'] ?? '');

if ($enrollment_id <= 0 || $status === '') {
    // Validate the required form fields on the server.
    die('Please select an enrollment and status.');
}

if (!in_array($status, ['present', 'absent'], true)) {
    // Ensure only the expected attendance statuses are accepted.
    die('Invalid attendance status.');
}

// Prevent marking attendance twice for the same enrollment.
$check_sql = 'SELECT log_id FROM attendance_log WHERE enrollment_id = ?';
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param('i', $enrollment_id);
$check_stmt->execute();

if ($check_stmt->get_result()->num_rows > 0) {
    header('Location: ../attendance_form.php?error=duplicate');
    exit();
}

$sql = 'INSERT INTO attendance_log (enrollment_id, status) VALUES (?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $enrollment_id, $status);

if (!$stmt->execute()) {
    echo 'Error: ' . $stmt->error;
    exit();
}

header('Location: ../attendance_records.php');
exit();