<?php
session_start();

// Check authentication first
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: login.php");
    exit;
}

// Hard security checkpoint rule: If they are NOT an admin, trigger styled browser alert and redirect
if ($_SESSION['role'] !== 'admin') {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Access Denied</title>
        <script type="text/javascript">
            alert("Access Denied: This page is restricted, for administrators only.");
            window.location.href = "index.php";
        </script>
        <style>
            body {
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                background-color: #F8FAFC;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .alert-box {
                background: white;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
                border-top: 5px solid #EF4444;
                text-align: center;
                max-width: 450px;
            }
            h2 { color: #991B1B; margin-top: 0; }
            p { color: #374151; font-size: 0.95rem; line-height: 1.5; }
        </style>
    </head>
    <body>
        <div class="alert-box">
            <h2>Security Exception</h2>
            <p>You do not possess structural permissions to view this administrative asset. Redirecting to home terminal...</p>
        </div>
    </body>
    </html>';
    exit;
}

// delete_event.php - CRUD Delete Safe Record Isolation Route Controller Handlers
require_once 'db_connect.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    // Perform parameterized operational removal preventing raw injection risk vectors
    $delete_stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
    $delete_stmt->bind_param("i", $id);
    
    if ($delete_stmt->execute()) {
        header("Location: events_list.php");
        exit();
    } else {
        echo "Structural execution processing exception transaction fault caught unlinking entry reference variables.";
    }
    $delete_stmt->close();
} else {
    header("Location: events_list.php");
    exit();
}
?>

