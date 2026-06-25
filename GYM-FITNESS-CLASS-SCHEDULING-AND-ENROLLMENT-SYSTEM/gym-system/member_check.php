<?php
// member_check.php verifies that the user is authenticated and is a gym member.
require_once __DIR__ . '/auth_check.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'member') {
    header('Location: /gym-system/login.php');
    exit();
}
?>