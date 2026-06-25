<?php
require_once __DIR__ . '/../member_check.php';
require_once __DIR__ . '/../db_connect.php';

// Read the updated member profile values from the form.
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$member_id = $_SESSION['user_id'];

if ($full_name === '' || $email === '' || $phone === '') {
    header('Location: ../member_profile.php?error=invalid');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../member_profile.php?error=invalid');
    exit();
}

if (!preg_match('/^[0-9]{11}$/', $phone)) {
    header('Location: ../member_profile.php?error=invalid');
    exit();
}

if (strcasecmp($full_name, 'admin') === 0 || strcasecmp($email, 'admin@gmail.com') === 0) {
    header('Location: ../member_profile.php?error=reserved');
    exit();
}

// Check whether another member already uses the same email, name, or phone.
$duplicateFields = [];

$emailCheckSql = 'SELECT member_id FROM members WHERE LOWER(email) = LOWER(?) AND member_id != ? LIMIT 1';
$emailCheckStmt = $conn->prepare($emailCheckSql);
$emailCheckStmt->bind_param('si', $email, $member_id);
$emailCheckStmt->execute();
$emailCheckResult = $emailCheckStmt->get_result();
if ($emailCheckResult->num_rows > 0) {
    $duplicateFields[] = 'email';
}
$emailCheckStmt->close();

$nameCheckSql = 'SELECT member_id FROM members WHERE LOWER(full_name) = LOWER(?) AND member_id != ? LIMIT 1';
$nameCheckStmt = $conn->prepare($nameCheckSql);
$nameCheckStmt->bind_param('si', $full_name, $member_id);
$nameCheckStmt->execute();
$nameCheckResult = $nameCheckStmt->get_result();
if ($nameCheckResult->num_rows > 0) {
    $duplicateFields[] = 'full_name';
}
$nameCheckStmt->close();

$phoneCheckSql = 'SELECT member_id FROM members WHERE phone = ? AND member_id != ? LIMIT 1';
$phoneCheckStmt = $conn->prepare($phoneCheckSql);
$phoneCheckStmt->bind_param('si', $phone, $member_id);
$phoneCheckStmt->execute();
$phoneCheckResult = $phoneCheckStmt->get_result();
if ($phoneCheckResult->num_rows > 0) {
    $duplicateFields[] = 'phone';
}
$phoneCheckStmt->close();

if (!empty($duplicateFields)) {
    header('Location: ../member_profile.php?error=duplicate');
    exit();
}

$sql = 'UPDATE members SET full_name = ?, email = ?, phone = ? WHERE member_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssi', $full_name, $email, $phone, $member_id);

if (!$stmt->execute()) {
    header('Location: ../member_profile.php?error=server');
    exit();
}

$stmt->close();

// Keep the session name in sync after a successful profile change.
$_SESSION['username'] = $full_name;

header('Location: ../member_profile.php?success=1');
exit();
