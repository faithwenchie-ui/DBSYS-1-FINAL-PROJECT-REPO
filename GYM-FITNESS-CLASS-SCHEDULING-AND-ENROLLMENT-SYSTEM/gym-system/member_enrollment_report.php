<?php

// member_enrollment_report.php shows each member alongside their total enrollments.
// It summarizes member participation in gym classes.
require_once 'admin_check.php';

require_once 'db_connect.php';


// Load a report of members with the total count of classes they are enrolled in.
$sql = "
    SELECT
        m.member_id,
        m.full_name,
        COUNT(e.enrollment_id) AS total_enrollments

    FROM members m

    LEFT JOIN enrollments e
        ON m.member_id = e.member_id

    GROUP BY
        m.member_id,
        m.full_name

    ORDER BY
        m.member_id ASC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Enrollment Report</title>
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

                        <a href="popular_classes.php">
                            Popular Classes
                        </a>

                        <a href="member_enrollment_report.php" class="active">
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

                <h1>Member Enrollment Report</h1>

                <p>
                    View the total number of class enrollments for each gym member.
                </p>

            </div>

            <table>

                <tr>
                    <th>Member ID</th>
                    <th>Member Name</th>
                    <th>Enrollments</th>
                </tr>

                <?php

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {

                        echo "<tr>";

                        echo "<td>" .
                            $row['member_id'] .
                            "</td>";

                        echo "<td>" .
                            htmlspecialchars($row['full_name']) .
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
                                No members found.
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