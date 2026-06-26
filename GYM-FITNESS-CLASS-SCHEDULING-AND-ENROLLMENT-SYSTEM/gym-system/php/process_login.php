<?php
// Handles user login and creates the session on success.

session_start();

require_once '../db_connect.php';

// Normalize user-provided credentials from the login form.
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    header("Location: ../login.php?error=1");
    exit();
}

// Try admin login first.
$sql = "SELECT admin_id, username, password_hash FROM admins WHERE LOWER(username) = LOWER(?) LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $admin = $result->fetch_assoc();
    if (password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $admin['admin_id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['user_role'] = 'admin';

        header("Location: ../index.php");
        exit();
    }
}

// Member login uses the members table.
$sql = "SELECT member_id, full_name, password_hash FROM members WHERE LOWER(full_name) = LOWER(?) LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);

$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $member = $result->fetch_assoc();
    if (password_verify($password, $member['password_hash'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $member['member_id'];
        $_SESSION['username'] = $member['full_name'];
        $_SESSION['user_role'] = 'member';

        header("Location: ../member_dashboard.php");
        exit();
    }
}

header("Location: ../login.php?error=1");
exit();