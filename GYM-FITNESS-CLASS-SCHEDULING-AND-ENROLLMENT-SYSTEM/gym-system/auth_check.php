<?php

// auth_check.php verifies the user has an active session before allowing access to protected pages.
// If the user is not authenticated, it redirects them to the login screen.
session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: /gym-system/login.php");
    exit();

}

?>