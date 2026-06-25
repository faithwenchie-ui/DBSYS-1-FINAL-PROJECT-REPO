<?php
// Updates an existing member's profile information.

require_once __DIR__ . '/../admin_check.php';
require_once __DIR__ . '/../db_connect.php';

// Normalize form inputs and protect against invalid IDs.
$member_id = intval($_POST['member_id'] ?? 0);
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

$emailCheckSql = 'SELECT member_id FROM members WHERE email = ? AND member_id != ? LIMIT 1';
$emailCheckStmt = $conn->prepare($emailCheckSql);
$emailCheckStmt->bind_param('si', $email, $member_id);
$emailCheckStmt->execute();
$emailCheckResult = $emailCheckStmt->get_result();
if ($emailCheckResult->num_rows > 0) {
    $duplicateFields[] = 'email';
}
$emailCheckStmt->close();

$nameCheckSql = 'SELECT member_id FROM members WHERE full_name = ? AND member_id != ? LIMIT 1';
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
    // Preserve submitted form values and duplicate field warnings via session.
    $_SESSION['edit_member_error'] = [
        'type' => 'duplicate_member',
        'fields' => $duplicateFields,
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
    ];

    header('Location: ../edit_member_form.php?id=' . $member_id);
    exit();
}

$sql = 'UPDATE members SET full_name = ?, email = ?, phone = ? WHERE member_id = ?';
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssi', $full_name, $email, $phone, $member_id);

if (!$stmt->execute()) {
    echo 'Error: ' . $stmt->error;
    exit();
}

$stmt->close();
$conn->close();

header('Location: ../members.php');
exit();

?>