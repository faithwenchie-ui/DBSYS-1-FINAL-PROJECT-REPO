<?php

require_once 'admin_check.php';
require_once 'db_connect.php';

// Load a summary view of class enrollments from a database view or summary table.
$sql = "SELECT * FROM class_enrollment_summary
        ORDER BY class_id ASC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Enrollment Summary</title>
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
                        <a href="class_summary.php" class="active">
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

                <h1>Class Enrollment Summary</h1>

                <p>
                    View enrollment statistics and capacity information for each gym class.
                </p>

            </div>

            <table>

                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Trainer</th>
                    <th>Schedule</th>
                    <th>Enrolled</th>
                    <th>Capacity</th>
                </tr>

                <?php

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {

                        echo "<tr>";

                        echo "<td>" . $row['class_id'] . "</td>";

                        echo "<td>" .
                            htmlspecialchars($row['class_name']) .
                            "</td>";

                        echo "<td>" .
                            htmlspecialchars($row['trainer_name']) .
                            "</td>";

                        echo "<td>" .
                            $row['schedule_at'] .
                            "</td>";

                        echo "<td>" .
                            $row['total_enrolled'] .
                            "</td>";

                        echo "<td>" .
                            $row['max_capacity'] .
                            "</td>";

                        echo "</tr>";

                    }

                } else {

                    echo "<tr>";
                    echo "<td colspan='6'>No records found.</td>";
                    echo "</tr>";

                }

                ?>

            </table>

        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>