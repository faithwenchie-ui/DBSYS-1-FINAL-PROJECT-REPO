<?php

// Add Member page displays a form for creating a new gym member.
// The page is protected so only administrators can access it.
require_once 'admin_check.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Member</title>
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
                        <a href="members.php" class="active">
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

            <div class="page-header">

                <h1>Add Member</h1>

                <p>
                    Register a new gym member into the system.
                </p>

            </div>

            <div class="form-container add-member-form-container">

                <?php
                if (isset($_GET['error']) && $_GET['error'] === 'duplicate_member') {
                    $fields = [];
                    if (!empty($_GET['fields'])) {
                        $fields = explode(',', htmlspecialchars($_GET['fields']));
                    }

                    $fieldLabels = [
                        'email' => 'email',
                        'full_name' => 'full name',
                        'phone' => 'phone number'
                    ];

                    $duplicateLabels = [];
                    foreach ($fields as $field) {
                        if (isset($fieldLabels[$field])) {
                            $duplicateLabels[] = $fieldLabels[$field];
                        }
                    }

                    if (!empty($duplicateLabels)) {
                        $last = array_pop($duplicateLabels);
                        $message = count($duplicateLabels) > 0
                            ? implode(', ', $duplicateLabels) . ' and ' . $last
                            : $last;
                        echo "<div class='error-message'>A member with the same {$message} already exists. Please use different details.</div>";
                    } else {
                        echo "<div class='error-message'>A member with matching details already exists. Please use different details.</div>";
                    }
                }
                ?>

                <form
                    name="memberForm"
                    action="php/add_member.php"
                    method="POST"
                    onsubmit="return validateMemberForm()">

                    <label>Full Name</label>

                    <input
                        type="text"
                        name="full_name"
                        required>

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        required>

                    <label>Phone Number</label>

                    <input
                        type="text"
                        name="phone"
                        maxlength="11"
                        placeholder="09XXXXXXXXX"
                        required>

                    <button type="submit">
                        Save Member
                    </button>

                    <a href="members.php"
                    class="action-btn danger-btn">
                        Cancel
                    </a>

                </form>

            </div>

        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>