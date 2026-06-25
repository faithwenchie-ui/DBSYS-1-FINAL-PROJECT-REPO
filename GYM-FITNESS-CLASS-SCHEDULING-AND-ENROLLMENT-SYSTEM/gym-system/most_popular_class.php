<?php
// most_popular_class.php displays the class with the greatest enrollment count.
// It selects the top-enrolled class and shows the trainer and total enrollments.

require_once 'admin_check.php';

require_once 'db_connect.php';


// Load the single class with the highest number of enrollments.
$sql = "
    SELECT
        c.class_name,
        t.full_name AS trainer_name,

        (
            SELECT COUNT(*)
            FROM enrollments e
            WHERE e.class_id = c.class_id
        ) AS total_enrollments

    FROM classes c

    INNER JOIN trainers t
        ON c.trainer_id = t.trainer_id

    WHERE c.class_id = (

        SELECT class_id
        FROM enrollments
        GROUP BY class_id
        ORDER BY COUNT(*) DESC
        LIMIT 1

    )
";

$result = $conn->query($sql);

$class = $result ? $result->fetch_assoc() : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Most Popular Class</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="layout">

        <aside class="sidebar">
            <div class="sidebar-nav">

                <a href="index.php" class="dashboard-link">
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

                    <div class="dropdown-content" id="reportsMenu">
                        <a href="class_summary.php">
                            Class Summary
                        </a>

                        <a href="most_popular_class.php" class="active">
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

            <div class="page-header">

                <h1>Most Popular Class</h1>

                <p>
                    View the class with the highest number of enrollments.
                </p>

            </div>

            <div class="report-card">

                <?php if ($class): ?>

                    <h2>
                        <?php echo htmlspecialchars($class['class_name']); ?>
                    </h2>

                    <p>
                        <strong>Trainer:</strong>
                        <?php echo htmlspecialchars($class['trainer_name']); ?>
                    </p>

                    <p class="stat-pill">
                        Total Enrollments: <?php echo $class['total_enrollments']; ?>
                    </p>

                <?php else: ?>

                    <p>
                        No enrollments found yet.
                    </p>

                <?php endif; ?>

            </div>

        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>