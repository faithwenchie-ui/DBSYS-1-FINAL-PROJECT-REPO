<?php

// attendance_form.php shows a form for selecting an enrollment and recording attendance.
// It is protected and loads enrollment options from the database.
require_once 'admin_check.php';

require_once 'db_connect.php';

// Load all enrollments with related member and class names for attendance selection.
$sql = "
    SELECT
        e.enrollment_id,
        m.full_name,
        c.class_name

    FROM enrollments e

    INNER JOIN members m
        ON e.member_id = m.member_id

    INNER JOIN classes c
        ON e.class_id = c.class_id

    ORDER BY e.enrollment_id ASC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
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
                        <a href="attendance_records.php">
                            Attendance Log
                        </a>

                        <a href="attendance_form.php" class="active">
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

                <h1>Mark Attendance</h1>

                <p>
                    Record attendance for enrolled gym members.
                </p>

            </div>

            <?php

            if (
                isset($_GET['error']) &&
                $_GET['error'] === 'duplicate'
            ) {

                echo "
                    <div class='error-message'>
                        Attendance has already been marked for this enrollment.
                    </div>
                ";

            }

            ?>

            <div class="form-container attendance-form-container">

                <form name="attendanceForm" action="php/mark_attendance.php"
                    method="POST"
                    onsubmit="return validateAttendanceForm()">

                    <label>Select Enrollment</label>

                    <select name="enrollment_id" required>

                        <option value="">
                            -- Choose Enrollment --
                        </option>

                        <?php

                        while ($row = $result->fetch_assoc()) {

                            echo "<option value='" .
                                $row['enrollment_id'] .
                                "'>";

                            echo "Enrollment #" .
                                $row['enrollment_id'] .
                                " - " .
                                htmlspecialchars($row['full_name']) .
                                " (" .
                                htmlspecialchars($row['class_name']) .
                                ")";

                            echo "</option>";

                        }

                        ?>

                    </select>

                    <label>Status</label>

                    <select name="status" required>

                        <option value="">
                            -- Choose Status --
                        </option>

                        <option value="present">
                            Present
                        </option>

                        <option value="absent">
                            Absent
                        </option>

                    </select>

                    <button type="submit">
                        Mark Attendance
                    </button>

                </form>

            </div>

        </main>
    </div>
<script src="js/validation.js"></script>
<script src="js/sidebar.js"></script>
</body>
</html>