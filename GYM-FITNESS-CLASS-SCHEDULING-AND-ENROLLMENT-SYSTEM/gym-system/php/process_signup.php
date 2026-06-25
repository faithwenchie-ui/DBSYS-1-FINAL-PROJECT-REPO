<?php
// Handles member sign-up and inserts a new member record.

require_once __DIR__ . '/../db_connect.php';

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm_password = trim($_POST['confirm_password'] ?? '');

if ($full_name === '' || $email === '' || $phone === '' || $password === '' || $confirm_password === '') {
    header('Location: ../signup.php?error=invalid');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../signup.php?error=invalid');
    exit();
}

if (!preg_match('/^[0-9]{11}$/', $phone)) {
    header('Location: ../signup.php?error=invalid');
    exit();
}

if ($password !== $confirm_password || strlen($password) < 6) {
    header('Location: ../signup.php?error=invalid');
    exit();
}

// Prevent sign-up with the admin reserved credentials.
if (strcasecmp($full_name, 'admin') === 0 || strcasecmp($email, 'admin@gmail.com') === 0) {
    header('Location: ../signup.php?error=reserved');
    exit();
}

$duplicateFields = [];

$emailCheckSql = 'SELECT member_id FROM members WHERE LOWER(email) = LOWER(?) LIMIT 1';
$emailCheckStmt = $conn->prepare($emailCheckSql);
$emailCheckStmt->bind_param('s', $email);
$emailCheckStmt->execute();
$emailCheckResult = $emailCheckStmt->get_result();
if ($emailCheckResult->num_rows > 0) {
    $duplicateFields[] = 'email';
}
$emailCheckStmt->close();

$nameCheckSql = 'SELECT member_id FROM members WHERE LOWER(full_name) = LOWER(?) LIMIT 1';
$nameCheckStmt = $conn->prepare($nameCheckSql);
$nameCheckStmt->bind_param('s', $full_name);
$nameCheckStmt->execute();
$nameCheckResult = $nameCheckStmt->get_result();
if ($nameCheckResult->num_rows > 0) {
    $duplicateFields[] = 'full_name';
}
$nameCheckStmt->close();

$phoneCheckSql = 'SELECT member_id FROM members WHERE phone = ? LIMIT 1';
$phoneCheckStmt = $conn->prepare($phoneCheckSql);
$phoneCheckStmt->bind_param('s', $phone);
$phoneCheckStmt->execute();
$phoneCheckResult = $phoneCheckStmt->get_result();
if ($phoneCheckResult->num_rows > 0) {
    $duplicateFields[] = 'phone';
}
$phoneCheckStmt->close();

if (!empty($duplicateFields)) {
    header('Location: ../signup.php?error=duplicate');
    exit();
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = 'INSERT INTO members (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssss', $full_name, $email, $phone, $password_hash);

if (!$stmt->execute()) {
    echo 'Error: ' . $stmt->error;
    exit();
}

$stmt->close();
$conn->close();

// After successful signup, redirect to login so member can sign in.
header('Location: ../login.php');
exit();
?>