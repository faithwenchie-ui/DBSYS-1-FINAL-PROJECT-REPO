<?php
require_once __DIR__ . '/../member_check.php';
require_once __DIR__ . '/../db_connect.php';

// Read the submitted password fields from the profile form.
$current_password = trim($_POST['current_password'] ?? '');
$new_password = trim($_POST['new_password'] ?? '');
$confirm_new_password = trim($_POST['confirm_new_password'] ?? '');
$member_id = $_SESSION['user_id'];

if ($current_password === '' || $new_password === '' || $confirm_new_password === '') {
    header('Location: ../member_profile.php?error=invalid');
    exit();
}

if ($new_password !== $confirm_new_password || strlen($new_password) < 6) {
    header('Location: ../member_profile.php?error=password_mismatch');
    exit();
}

$sql = 'SELECT password_hash FROM members WHERE member_id = ? LIMIT 1';
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $member_id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();
$stmt->close();

if (!$member || !password_verify($current_password, $member['password_hash'])) {
    header('Location: ../member_profile.php?error=password_incorrect');
    exit();
}

$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

$updateSql = 'UPDATE members SET password_hash = ? WHERE member_id = ?';
$updateStmt = $conn->prepare($updateSql);
$updateStmt->bind_param('si', $new_password_hash, $member_id);

if (!$updateStmt->execute()) {
    header('Location: ../member_profile.php?error=server');
    exit();
}

$updateStmt->close();

header('Location: ../member_profile.php?success=password');
exit();
