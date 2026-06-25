<?php
// Deletes a single enrollment record and its related attendance rows.

require_once __DIR__ . '/../admin_check.php';
require_once __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header('Location: ../enrollments.php');
    exit();
}

$enrollment_id = intval($_POST['id']);

if ($enrollment_id <= 0) {
    header('Location: ../enrollments.php');
    exit();
}

// Begin a transaction to ensure attendance and enrollment deletions stay consistent.
$conn->begin_transaction();

// Remove attendance log entries associated with this enrollment first.
$deleteAttendanceSql = 'DELETE FROM attendance_log WHERE enrollment_id = ?';
$attendanceStmt = $conn->prepare($deleteAttendanceSql);
$attendanceStmt->bind_param('i', $enrollment_id);

if (!$attendanceStmt->execute()) {
    // Roll back if attendance removal fails.
    $conn->rollback();
    echo 'Error deleting attendance records: ' . $attendanceStmt->error;
    exit();
}

$attendanceStmt->close();

// Then delete the enrollment record itself.
$deleteEnrollmentSql = 'DELETE FROM enrollments WHERE enrollment_id = ?';
$enrollmentStmt = $conn->prepare($deleteEnrollmentSql);
$enrollmentStmt->bind_param('i', $enrollment_id);

if (!$enrollmentStmt->execute()) {
    // Roll back if enrollment deletion fails.
    $conn->rollback();
    echo 'Error deleting enrollment: ' . $enrollmentStmt->error;
    exit();
}

$enrollmentStmt->close();

$conn->commit();
$conn->close();

header('Location: ../enrollments.php');
exit();

?>