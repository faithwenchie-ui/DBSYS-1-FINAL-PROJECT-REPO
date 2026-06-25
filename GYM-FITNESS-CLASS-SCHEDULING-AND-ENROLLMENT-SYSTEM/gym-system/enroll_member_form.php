<?php
// Enrollment form page for assigning members to classes.

require_once 'admin_check.php';

require_once 'db_connect.php';

/*
|--------------------------------------------------------------------------
| Load members for the enrollment dropdown
|--------------------------------------------------------------------------
*/

$members_sql = "
    SELECT member_id, full_name
    FROM members
    ORDER BY full_name ASC
";

$members_result = $conn->query($members_sql);


/*
|--------------------------------------------------------------------------
| Load classes for the enrollment dropdown
|--------------------------------------------------------------------------
*/

$classes_sql = "
    SELECT c.class_id, c.class_name, c.max_capacity,
           COUNT(e.enrollment_id) AS enrolled_count,
           GREATEST(c.max_capacity - COUNT(e.enrollment_id), 0) AS available_slots
    FROM classes c
    LEFT JOIN enrollments e ON c.class_id = e.class_id
    GROUP BY c.class_id, c.class_name, c.max_capacity
    ORDER BY c.class_name ASC
";

$classes_result = $conn->query($classes_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Member</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/validation.js"></script>
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

                        <a href="enrollments.php">
                            Enrollments
                        </a>

                        <a href="enroll_member_form.php" class="active">
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

            <h1>Enroll Member</h1>

            <p>
                Register a member into a gym fitness class.
            </p>

        </div>

        <div class="form-container enroll-form-container">

            <?php

            if (isset($_GET['error'])) {
                if ($_GET['error'] === 'duplicate') {
                    echo "
                        <div class='error-message'>
                            This member is already enrolled in the selected class.
                        </div>
                    ";
                } elseif ($_GET['error'] === 'full') {
                    echo "
                        <div class='error-message'>
                            The selected class is full and cannot accept more members.
                        </div>
                    ";
                }
            }

            ?>

            <form
                name="enrollmentForm"
                action="php/enroll_member.php"
                method="POST"
                onsubmit="return validateEnrollmentForm()">

                <label>Select Member</label>

                <select name="member_id" required>

                    <option value="">
                        -- Choose Member --
                    </option>

                    <?php

                    while ($member = $members_result->fetch_assoc()) {

                        echo "<option value='" .
                            $member['member_id'] .
                            "'>" .
                            htmlspecialchars($member['full_name']) .
                            "</option>";

                    }

                    ?>

                </select>

                <label>Select Class</label>

                <select name="class_id" required>

                    <option value="">
                        -- Choose Class --
                    </option>

                    <?php

                    while ($class = $classes_result->fetch_assoc()) {
                        $disabled = $class['available_slots'] <= 0 ? ' disabled' : '';
                        $label = htmlspecialchars($class['class_name']);
                        if ($class['available_slots'] <= 0) {
                            $label .= ' (Full)';
                        } else {
                            $label .= ' (' . (int)$class['available_slots'] . ' slots left)';
                        }

                        echo "<option value='" .
                            $class['class_id'] .
                            "' data-available-slots='" .
                            (int)$class['available_slots'] .
                            "'" .
                            $disabled .
                            ">" .
                            $label .
                            "</option>";
                    }

                    ?>

                </select>

                <button type="submit">
                    Enroll Member
                </button>

            </form>

        </div>

        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>