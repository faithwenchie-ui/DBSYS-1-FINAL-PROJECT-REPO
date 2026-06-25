<?php
// edit_member_form.php loads an existing member record for editing.
// It checks the URL for a member ID and pre-fills the edit form with that member's details.

require_once 'admin_check.php';

require_once 'db_connect.php';


// Check if ID exists in URL
if (!isset($_GET['id'])) {
    die("Member ID not found.");
}


// Convert the member ID from the query string into an integer.
$member_id = intval($_GET['id']);


// Prepare SQL query
$sql = "SELECT * FROM members WHERE member_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $member_id);

$stmt->execute();

$result = $stmt->get_result();


// Check if member exists
if ($result->num_rows === 0) {
    die("Member not found.");
}


// Fetch member data for the form fields.
$member = $result->fetch_assoc();

$formValues = $member;
$editMemberError = $_SESSION['edit_member_error'] ?? null;

if (isset($_SESSION['edit_member_error'])) {
    unset($_SESSION['edit_member_error']);
}

if (!empty($editMemberError['full_name'])) {
    $formValues['full_name'] = $editMemberError['full_name'];
}

if (!empty($editMemberError['email'])) {
    $formValues['email'] = $editMemberError['email'];
}

if (!empty($editMemberError['phone'])) {
    $formValues['phone'] = $editMemberError['phone'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>
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

                <h1>Edit Member</h1>

                <p>
                    Update member information and account details.
                </p>

            </div>

            <div class="form-container">

                <?php
                // Display duplicate field warnings if the update form was previously submitted with conflicts.
if (!empty($editMemberError) && ($editMemberError['type'] ?? '') === 'duplicate_member') {
                    $fieldLabels = [
                        'email' => 'email',
                        'full_name' => 'full name',
                        'phone' => 'phone number'
                    ];

                    $duplicateLabels = [];
                    foreach (($editMemberError['fields'] ?? []) as $field) {
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
                    action="php/update_member.php"
                    method="POST"
                    onsubmit="return validateMemberForm()">

                    <input
                        type="hidden"
                        name="member_id"
                        value="<?php echo $member['member_id']; ?>">

                    <label>Full Name</label>

                    <input
                        type="text"
                        name="full_name"
                        value="<?php echo htmlspecialchars($formValues['full_name']); ?>"
                        required>

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        value="<?php echo htmlspecialchars($formValues['email']); ?>"
                        required>

                    <label>Phone Number</label>

                    <input
                        type="text"
                        name="phone"
                        maxlength="11"
                        placeholder="09XXXXXXXXX"
                        required
                        value="<?php echo htmlspecialchars($formValues['phone']); ?>">

                    <button type="submit">
                        Update Member
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