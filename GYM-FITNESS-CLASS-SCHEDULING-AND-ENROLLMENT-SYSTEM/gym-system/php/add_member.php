<?php
// Inserts a new member record into the database.

require_once __DIR__ . '/../admin_check.php';
require_once __DIR__ . '/../db_connect.php';

// Get form inputs and trim whitespace.
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

// Basic required-field validation.
if (empty($full_name) || empty($email) || empty($phone)) {
    die('Full name, email, and phone number are required.');
}

// Ensure phone uses an 11-digit numeric format.
if (!preg_match('/^[0-9]{11}$/', $phone)) {
    die('Phone number must contain exactly 11 digits.');
}

$duplicateFields = [];

$emailCheckSql = 'SELECT member_id FROM members WHERE email = ? LIMIT 1';
$emailCheckStmt = $conn->prepare($emailCheckSql);
$emailCheckStmt->bind_param('s', $email);
$emailCheckStmt->execute();
$emailCheckResult = $emailCheckStmt->get_result();
if ($emailCheckResult->num_rows > 0) {
    $duplicateFields[] = 'email';
}
$emailCheckStmt->close();

$nameCheckSql = 'SELECT member_id FROM members WHERE full_name = ? LIMIT 1';
$nameCheckStmt = $conn->prepare($nameCheckSql);
$nameCheckStmt->bind_param('s', $full_name);
$nameCheckStmt->execute();
$nameCheckResult = $nameCheckStmt->get_result();
if ($nameCheckResult->num_rows > 0) {
    $duplicateFields[] = 'full_name';
}
$nameCheckStmt->close();

if ($phone !== '') {
    // Check whether the phone number already exists so duplicate member entries are not created.
    $phoneCheckSql = 'SELECT member_id FROM members WHERE phone = ? LIMIT 1';
    $phoneCheckStmt = $conn->prepare($phoneCheckSql);
    $phoneCheckStmt->bind_param('s', $phone);
    $phoneCheckStmt->execute();
    $phoneCheckResult = $phoneCheckStmt->get_result();
    if ($phoneCheckResult->num_rows > 0) {
        $duplicateFields[] = 'phone';
    }
    $phoneCheckStmt->close();
}

if (!empty($duplicateFields)) {
    $conn->close();
    header('Location: ../add_member_form.php?error=duplicate_member&fields=' . urlencode(implode(',', $duplicateFields)));
    exit();
}

$defaultPassword = strtolower(preg_replace('/\s+/', '', $full_name)) . '12345';
$passwordHash = password_hash($defaultPassword, PASSWORD_DEFAULT);

$sql = 'INSERT INTO members (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssss', $full_name, $email, $phone, $passwordHash);

if (!$stmt->execute()) {
    // Display an error if the insert fails for any reason.
    echo 'Error: ' . $stmt->error;
    exit();
}

// Clean up and return the user to the members list.
$stmt->close();
$conn->close();

header('Location: ../members.php');
exit();

?>