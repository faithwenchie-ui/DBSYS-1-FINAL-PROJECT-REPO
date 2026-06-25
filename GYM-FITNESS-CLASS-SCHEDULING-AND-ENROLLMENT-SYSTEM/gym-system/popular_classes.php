<?php
// popular_classes.php displays classes ranked by enrollment volume.
// It shows classes with two or more enrollments to highlight popular offerings.

require_once 'admin_check.php';

require_once 'db_connect.php';


// Load classes ordered by popularity, returning only classes with at least two enrollments.
$sql = "
    SELECT
        c.class_name,
        t.full_name AS trainer_name,
        COUNT(e.enrollment_id) AS total_enrollments

    FROM classes c

    INNER JOIN trainers t
        ON c.trainer_id = t.trainer_id

    LEFT JOIN enrollments e
        ON c.class_id = e.class_id

    GROUP BY
        c.class_id,
        c.class_name,
        t.full_name

    HAVING COUNT(e.enrollment_id) >= 2

    ORDER BY total_enrollments DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Classes</title>
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

                        <a href="most_popular_class.php">
                            Most Popular
                        </a>

                        <a href="popular_classes.php" class="active">
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

                <h1>Popular Classes</h1>

                <p>
                    View classes ranked by enrollment popularity.
                </p>

            </div>

            <table>

                <tr>
                    <th>Class Name</th>
                    <th>Trainer</th>
                    <th>Enrollments</th>
                </tr>

                <?php

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {

                        echo "<tr>";

                        echo "<td>" .
                            htmlspecialchars($row['class_name']) .
                            "</td>";

                        echo "<td>" .
                            htmlspecialchars($row['trainer_name']) .
                            "</td>";

                        echo "<td>" .
                            $row['total_enrollments'] .
                            "</td>";

                        echo "</tr>";

                    }

                } else {

                    echo "
                        <tr>
                            <td colspan='3'>
                                No popular classes found.
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