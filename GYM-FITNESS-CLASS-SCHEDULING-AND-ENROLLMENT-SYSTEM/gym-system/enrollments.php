<?php

// enrollments.php lists active class enrollments and their current status.
// It joins member and class data so the page can show readable names.
require_once 'admin_check.php';

require_once 'db_connect.php';


// Load enrollment records with joined member and class details.
$sql = "SELECT
            enrollments.enrollment_id,
            members.full_name AS member_name,
            classes.class_name,
            enrollments.status,
            enrollments.enroll_date

        FROM enrollments

        INNER JOIN members
            ON enrollments.member_id = members.member_id

        INNER JOIN classes
            ON enrollments.class_id = classes.class_id

        ORDER BY enrollments.enrollment_id ASC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments</title>
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

                    <div class="dropdown-content" id="memberMenu">
                        <a href="members.php">
                            Members
                        </a>

                        <a href="enrollments.php" class="active">
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

                <div class="page-header">

                    <h1>Enrollment Records</h1>

                    <p>
                        View all member enrollment records.
                    </p>

                </div>

                <table class="enrollments-table">

                    <tr>
                        <th>Enrollment ID</th>
                        <th>Member Name</th>
                        <th>Class Name</th>
                        <th>Status</th>
                        <th>Enrollment Date</th>
                        <th>Action</th>
                    </tr>

                    <?php
                    if ($result->num_rows > 0) {

                            while ($row = $result->fetch_assoc()) {

                                echo "<tr>";

                                echo "<td>" . $row['enrollment_id'] . "</td>";

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
                                    $row['enroll_date'] .
                                    "</td>";

                                echo "<td class='action-cell'>";
                                echo "<form method='POST' action='php/delete_enrollment.php' style='display:inline;' onsubmit='return showConfirmDelete(event, \"Are you sure you want to delete this enrollment?\", this)'>";
                                echo "<input type='hidden' name='id' value='" . (int)$row['enrollment_id'] . "'>";
                                echo "<button type='submit' class='action-btn danger-btn'>Delete</button>";
                                echo "</form>";
                                echo "</td>";

                                echo "</tr>";
                            }

                        } else {

                            echo "<tr>";
                            echo "<td colspan='6'>No enrollment records found.</td>";
                            echo "</tr>";

                        }
                    ?>

                </table>

        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>