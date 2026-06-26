<?php
// Deletes a member record from the database.

require_once __DIR__ . '/../admin_check.php';
require_once __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header('Location: ../members.php');
    exit();
}

$member_id = intval($_POST['id']);

// Start a transaction so all related deletions succeed or fail together.
$conn->begin_transaction();

// Remove attendance records tied to this member through enrollments.
$deleteAttendanceSql = "DELETE al FROM attendance_log al
    INNER JOIN enrollments e ON al.enrollment_id = e.enrollment_id
    WHERE e.member_id = ?";
$attendanceStmt = $conn->prepare($deleteAttendanceSql);
$attendanceStmt->bind_param('i', $member_id);

if (!$attendanceStmt->execute()) {
    // Roll back if attendance deletion fails to avoid partial cleanup.
    $conn->rollback();
    echo 'Error deleting attendance records: ' . $attendanceStmt->error;
    exit();
}
$attendanceStmt->close();

// Delete any enrollments for the member next.
$deleteEnrollmentsSql = 'DELETE FROM enrollments WHERE member_id = ?';
$enrollmentStmt = $conn->prepare($deleteEnrollmentsSql);
$enrollmentStmt->bind_param('i', $member_id);

if (!$enrollmentStmt->execute()) {
    // Roll back if enrollment deletion fails.
    $conn->rollback();
    echo 'Error deleting enrollments: ' . $enrollmentStmt->error;
    exit();
}
$enrollmentStmt->close();

// Finally remove the member record itself.
$sql = 'DELETE FROM members WHERE member_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $member_id);

if (!$stmt->execute()) {
    $conn->rollback();
    echo 'Error deleting member: ' . $stmt->error;
    exit();
}

$conn->commit();
$stmt->close();
$conn->close();

header('Location: ../members.php');
exit();

?>