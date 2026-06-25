<?php
// Handles member self-unenrollment and removes any related attendance records.

require_once __DIR__ . '/../member_check.php';
require_once __DIR__ . '/../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    header('Location: ../member_enrollments.php?error=invalid');
    exit();
}

$member_id = intval($_SESSION['user_id']);
$enrollment_id = intval($_POST['id']);

if ($member_id <= 0 || $enrollment_id <= 0) {
    header('Location: ../member_enrollments.php?error=invalid');
    exit();
}

$selectSql = 'SELECT member_id FROM enrollments WHERE enrollment_id = ?';
$selectStmt = $conn->prepare($selectSql);
$selectStmt->bind_param('i', $enrollment_id);
$selectStmt->execute();
$selectResult = $selectStmt->get_result();

if ($selectResult->num_rows === 0) {
    $selectStmt->close();
    header('Location: ../member_enrollments.php?error=notfound');
    exit();
}

$enrollment = $selectResult->fetch_assoc();
$selectStmt->close();

if (intval($enrollment['member_id']) !== $member_id) {
    header('Location: ../member_enrollments.php?error=unauthorized');
    exit();
}

$conn->begin_transaction();

$deleteAttendanceSql = 'DELETE FROM attendance_log WHERE enrollment_id = ?';
$attendanceStmt = $conn->prepare($deleteAttendanceSql);
$attendanceStmt->bind_param('i', $enrollment_id);

if (!$attendanceStmt->execute()) {
    $conn->rollback();
    $attendanceStmt->close();
    header('Location: ../member_enrollments.php?error=server');
    exit();
}

$attendanceStmt->close();

$deleteEnrollmentSql = 'DELETE FROM enrollments WHERE enrollment_id = ? AND member_id = ?';
$enrollmentStmt = $conn->prepare($deleteEnrollmentSql);
$enrollmentStmt->bind_param('ii', $enrollment_id, $member_id);

if (!$enrollmentStmt->execute()) {
    $conn->rollback();
    $enrollmentStmt->close();
    header('Location: ../member_enrollments.php?error=server');
    exit();
}

$enrollmentStmt->close();
$conn->commit();
$conn->close();

header('Location: ../member_enrollments.php?success=unenrolled');
exit();
?>