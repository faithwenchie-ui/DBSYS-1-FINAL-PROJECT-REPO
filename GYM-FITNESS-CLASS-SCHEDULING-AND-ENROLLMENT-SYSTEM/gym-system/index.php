<?php
// Dashboard page showing summary counts for members, trainers, classes, and enrollments.
// This page requires administrator access and uses the shared database connection.

require_once 'admin_check.php';
require_once 'db_connect.php';

// Count total registered members for the dashboard.
$total_members_query = "SELECT COUNT(*) AS total_members FROM members";
$total_members_result = $conn->query($total_members_query);
$total_members = $total_members_result->fetch_assoc()['total_members'];


// Total trainers
$total_trainers_query = "SELECT COUNT(*) AS total_trainers FROM trainers";

$total_trainers_result = $conn->query($total_trainers_query);

$total_trainers = $total_trainers_result->fetch_assoc()['total_trainers'];


// Total classes
$total_classes_query = "SELECT COUNT(*) AS total_classes FROM classes";

$total_classes_result = $conn->query($total_classes_query);

$total_classes = $total_classes_result->fetch_assoc()['total_classes'];


// Total enrollments
$total_enrollments_query = "SELECT COUNT(*) AS total_enrollments FROM enrollments";

$total_enrollments_result = $conn->query($total_enrollments_query);

$total_enrollments = $total_enrollments_result->fetch_assoc()['total_enrollments'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym System Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="layout">

        <aside class="sidebar">
            <div class="sidebar-nav">

                <a href="index.php" class="active">
                    Dashboard
                </a>

                <div class="dropdown">
                    <div class="dropdown-title" onclick="toggleMenu('memberMenu', this)">
                        ▶ MEMBER MANAGEMENT
                    </div>

                    <div class="dropdown-content" id="memberMenu" style="display:none;">
                        <a href="members.php">
                            Members
                        </a>

                        <a href="enrollments.php">
                            Enrollments
                        </a>

                        <a href="enroll_member_form.php">
                            Enroll Member
                        </a>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="dropdown-title" onclick="toggleMenu('classMenu', this)">
                        ▶ CLASS MANAGEMENT
                    </div>

                    <div class="dropdown-content" id="classMenu" style="display:none;">
                        <a href="classes.php">
                            Classes
                        </a>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="dropdown-title" onclick="toggleMenu('attendanceMenu', this)">
                        ▶ ATTENDANCE
                    </div>

                    <div class="dropdown-content" id="attendanceMenu" style="display:none;">
                        <a href="attendance_records.php">
                            Attendance Log
                        </a>

                        <a href="attendance_form.php">
                            Mark Attendance
                        </a>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="dropdown-title" onclick="toggleMenu('reportsMenu', this)">
                        ▶ REPORTS
                    </div>

                    <div class="dropdown-content" id="reportsMenu" style="display:none;">
                        <a href="class_summary.php">
                            Class Summary
                        </a>

                        <a href="most_popular_class.php">
                            Most Popular
                        </a>

                        <a href="popular_classes.php">
                            Popular Classes
                        </a>

                        <a href="member_enrollment_report.php">
                            Member Report
                        </a>
                    </div>
                </div>

                <div class="dropdown">
                    <div class="dropdown-title" onclick="toggleMenu('accountMenu', this)">
                        ▶ ACCOUNT
                    </div>

                    <div class="dropdown-content" id="accountMenu" style="display:none;">
                        <a href="logout.php" onclick="return showConfirmAction(event, 'Are you sure you want to log out?', 'logout.php');">
                            Logout
                        </a>
                    </div>
                </div>

            </div>
        </aside>

        <main class="main-content">
            <div class="hero-card">

                <h2>
                    Welcome back,
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </h2>

                <p>
                    Manage gym members, enrollments,
                    attendance records, and reports
                    from one centralized dashboard.
                </p>

            </div>

            <div class="dashboard-container">

                <a href="members.php" class="card dashboard-link" data-menu="memberMenu">
                    <h2>Total Members</h2>
                    <p><?php echo $total_members; ?></p>
                </a>

                <a href="class_summary.php" class="card dashboard-link" data-menu="reportsMenu">
                    <h2>Total Trainers</h2>
                    <p><?php echo $total_trainers; ?></p>
                </a>

                <a href="classes.php" class="card dashboard-link" data-menu="classMenu">
                    <h2>Total Classes</h2>
                    <p><?php echo $total_classes; ?></p>
                </a>

                <a href="enrollments.php" class="card dashboard-link" data-menu="memberMenu">
                    <h2>Total Enrollments</h2>
                    <p><?php echo $total_enrollments; ?></p>
                </a>

            </div>

        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>