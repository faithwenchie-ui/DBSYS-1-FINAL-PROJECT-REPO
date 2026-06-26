<?php

// classes.php shows a list of gym classes and their trainer assignments.
// It loads classes from the database along with trainer names.
require_once 'admin_check.php';
require_once 'db_connect.php';

// Load class records with current enrollment counts and availability.
$sql = "SELECT c.class_id, c.class_name, t.full_name AS trainer_name, c.schedule_at, c.max_capacity,
        COUNT(e.enrollment_id) AS enrolled_count,
        GREATEST(c.max_capacity - COUNT(e.enrollment_id), 0) AS available_slots
        FROM classes c
        LEFT JOIN trainers t ON c.trainer_id = t.trainer_id
        LEFT JOIN enrollments e ON c.class_id = e.class_id
        GROUP BY c.class_id, c.class_name, t.full_name, c.schedule_at, c.max_capacity
        ORDER BY c.class_id ASC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Classes</title>
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
                        <a href="classes.php" class="active">
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

                <h1>Manage Classes</h1>

                <p>
                    Update class schedules and capacity information.
                </p>

            </div>

            <table>
                <tr>
                    <th>Class ID</th>
                    <th>Class Name</th>
                    <th>Trainer Name</th>
                    <th>Schedule</th>
                    <th>Enrolled</th>
                    <th>Capacity</th>
                    <th>Available Slots</th>
                    <th>Actions</th>
                </tr>

                <?php

                if ($result->num_rows > 0) {

                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['class_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['class_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['trainer_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['schedule_at']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['enrolled_count']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['max_capacity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['available_slots']) . "</td>";
                        echo "<td class='action-cell'>";

                        echo "<a class='action-btn' href='edit_class_form.php?id=" . (int)$row['class_id'] . "'>Edit</a>";

                        echo "</td>";
                        echo "</tr>";
                    }

                } else {
                    echo "<tr>";
                    echo "<td colspan='8'>No classes found.</td>";
                    echo "</tr>";
                }

                ?>
            </table>

        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>