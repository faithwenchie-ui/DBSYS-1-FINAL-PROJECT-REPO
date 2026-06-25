<?php
require_once 'member_check.php';
require_once 'db_connect.php';

$member_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$classCountResult = $conn->query('SELECT COUNT(*) AS total_classes FROM classes');
$total_classes = $classCountResult->fetch_assoc()['total_classes'] ?? 0;

$enrollmentCountStmt = $conn->prepare('SELECT COUNT(*) AS total_enrollments FROM enrollments WHERE member_id = ?');
$enrollmentCountStmt->bind_param('i', $member_id);
$enrollmentCountStmt->execute();
$enrollmentCountResult = $enrollmentCountStmt->get_result();
$total_enrollments = $enrollmentCountResult->fetch_assoc()['total_enrollments'] ?? 0;
$enrollmentCountStmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-nav">
                <a href="member_dashboard.php" class="active">Dashboard</a>
                <a href="member_classes.php">Available Classes</a>
                <a href="member_enrollments.php">My Enrollments</a>
                <a href="member_profile.php" class="dashboard-link">Profile</a>
                <a href="logout.php" class="logout" onclick="return showConfirmAction(event, 'Are you sure you want to log out?', 'logout.php');">Logout</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="hero-card">
                <h2>Welcome back, <?php echo htmlspecialchars($username); ?></h2>
                <p>Browse available fitness classes, view schedules and trainers, and manage your class enrollments.</p>
            </div>

            <div class="dashboard-container">
                <a href="member_classes.php" class="card dashboard-link">
                    <h2>Available Classes</h2>
                    <p><?php echo $total_classes; ?></p>
                </a>

                <a href="member_enrollments.php" class="card dashboard-link">
                    <h2>My Enrollments</h2>
                    <p><?php echo $total_enrollments; ?></p>
                </a>
            </div>
        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>