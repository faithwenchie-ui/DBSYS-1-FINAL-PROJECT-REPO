<?php
// Creates a new class enrollment for a selected member.

require_once __DIR__ . '/../admin_check.php';
require_once __DIR__ . '/../db_connect.php';

$member_id = intval($_POST['member_id'] ?? 0);
$class_id = intval($_POST['class_id'] ?? 0);

if ($member_id <= 0 || $class_id <= 0) {
    die('Please select a member and class.');
}

$check_sql = 'SELECT enrollment_id FROM enrollments WHERE member_id = ? AND class_id = ?';
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param('ii', $member_id, $class_id);
$check_stmt->execute();

if ($check_stmt->get_result()->num_rows > 0) {
    header('Location: ../enroll_member_form.php?error=duplicate');
    exit();
}

$check_stmt->close();

$capacity_sql = 'SELECT c.max_capacity, COUNT(e.enrollment_id) AS enrolled_count
                 FROM classes c
                 LEFT JOIN enrollments e ON c.class_id = e.class_id
                 WHERE c.class_id = ?
                 GROUP BY c.class_id, c.max_capacity';
$capacity_stmt = $conn->prepare($capacity_sql);
$capacity_stmt->bind_param('i', $class_id);
$capacity_stmt->execute();
$capacity_result = $capacity_stmt->get_result();

if ($capacity_result->num_rows === 0) {
    header('Location: ../enroll_member_form.php?error=invalid');
    exit();
}

$capacity_row = $capacity_result->fetch_assoc();
$capacity_stmt->close();

$available_slots = (int)$capacity_row['max_capacity'] - (int)$capacity_row['enrolled_count'];
if ($available_slots <= 0) {
    header('Location: ../enroll_member_form.php?error=full');
    exit();
}

$sql = 'CALL EnrollMember(?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $member_id, $class_id);

if (!$stmt->execute()) {
    echo 'Error: ' . $stmt->error;
    exit();
}

$stmt->close();
$conn->close();

header('Location: ../enrollments.php');
exit();

?>