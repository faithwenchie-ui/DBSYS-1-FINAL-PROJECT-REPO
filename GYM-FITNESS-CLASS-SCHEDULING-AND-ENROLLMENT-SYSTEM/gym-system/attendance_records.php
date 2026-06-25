<?php

// attendance_records.php displays the attendance log history for all enrollments.
// It uses joins to show member and class names alongside each attendance entry.
require_once 'admin_check.php';

require_once 'db_connect.php';

// Load attendance log records joined with related member and class details.
$sql = "
    SELECT
        a.log_id,
        m.full_name AS member_name,
        c.class_name,
        a.status,
        a.marked_at

    FROM attendance_log a

    INNER JOIN enrollments e
        ON a.enrollment_id = e.enrollment_id

    INNER JOIN members m
        ON e.member_id = m.member_id

    INNER JOIN classes c
        ON e.class_id = c.class_id

    ORDER BY a.log_id DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
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

                    <div class="dropdown-content" id="attendanceMenu">
                        <a href="attendance_records.php" class="active">
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

        <div class="page-header">

            <h1>Attendance Records</h1>

            <p>
                Track attendance history and member participation.
            </p>

        </div>

        <table>

            <tr>
                <th>Log ID</th>
                <th>Member Name</th>
                <th>Class Name</th>
                <th>Status</th>
                <th>Marked At</th>
            </tr>

            <?php

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {

                    echo "<tr>";

                    echo "<td>" .
                        $row['log_id'] .
                        "</td>";

                    echo "<td>" .
                        htmlspecialchars($row['member_name']) .
                        "</td>";

                    echo "<td>" .
                        htmlspecialchars($row['class_name']) .
                        "</td>";

                    echo "<td>" .
                        htmlspecialchars($row['status']) .
                        "</td>";

                    echo "<td>" .
                        htmlspecialchars($row['marked_at']) .
                        "</td>";

                    echo "</tr>";

                }

            } else {

                echo "
                    <tr>
                        <td colspan='5'>
                            No attendance records found.
                        </td>
                    </tr>
                ";

            }

            ?>

        </table>

        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>
