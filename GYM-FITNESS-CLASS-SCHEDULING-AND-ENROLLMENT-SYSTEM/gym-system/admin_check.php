<?php
// admin_check.php verifies that the user is authenticated and is an administrator.
require_once __DIR__ . '/auth_check.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /gym-system/login.php');
    exit();
}
?>