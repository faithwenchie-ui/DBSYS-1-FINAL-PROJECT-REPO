<?php
// edit_class_form.php loads class details for editing schedule and capacity.
// It verifies the requested class ID exists and retrieves the class with its trainer information.

require_once 'admin_check.php';

require_once 'db_connect.php';


// Check if ID exists in URL
if (!isset($_GET['id'])) {
    die("Class ID not found.");
}


// Convert the class ID from the query string into an integer.
$class_id = intval($_GET['id']);


// Prepare SQL query to fetch class and trainer information for the selected class.
$sql = "SELECT c.class_id, c.class_name, c.trainer_id, c.schedule_at, c.max_capacity,
           t.full_name AS trainer_name, t.specialty
        FROM classes c
        LEFT JOIN trainers t ON c.trainer_id = t.trainer_id
        WHERE c.class_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $class_id);

$stmt->execute();

$result = $stmt->get_result();


// Check if class exists
if ($result->num_rows === 0) {
    die("Class not found.");
}


// Fetch class data
$classData = $result->fetch_assoc();

$formValues = $classData;

$selectedTrainerId = (int)($classData['trainer_id'] ?? 0);
$availableTrainers = [];
$trainerSql = "SELECT trainer_id, full_name, specialty FROM trainers ORDER BY full_name";
$trainerStmt = $conn->prepare($trainerSql);
$trainerStmt->execute();
$trainerResult = $trainerStmt->get_result();

while ($row = $trainerResult->fetch_assoc()) {
    $availableTrainers[] = [
        'trainer_id' => (int)$row['trainer_id'],
        'full_name' => trim($row['full_name'] ?? ''),
        'specialty' => trim($row['specialty'] ?? '')
    ];
}

$trainerStmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class</title>
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

                    <div class="dropdown-content" id="classMenu">
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

                <h1>Edit Class</h1>

                <p>
                    Update the assigned trainer, schedule, and maximum capacity.
                </p>

            </div>

            <div class="form-container">

                <form
                    name="classForm"
                    action="php/update_class.php"
                    method="POST"
                    onsubmit="return validateClassForm()">

                    <input
                        type="hidden"
                        name="class_id"
                        value="<?php echo $classData['class_id']; ?>">

                    <label>Class Name</label>

                    <input
                        type="text"
                        value="<?php echo htmlspecialchars($formValues['class_name'] ?? 'N/A'); ?>"
                        readonly>

                    <label>Trainer Name</label>

                    <select id="trainerSelect" name="trainer_id" required>
                        <option value="">Select a trainer</option>
                        <?php foreach ($availableTrainers as $trainer): ?>
                            <option value="<?php echo (int)$trainer['trainer_id']; ?>"
                                data-specialty="<?php echo htmlspecialchars($trainer['specialty']); ?>"
                                <?php echo ((int)$trainer['trainer_id'] === $selectedTrainerId) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($trainer['full_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Trainer Specialty</label>

                    <input
                        id="trainerSpecialty"
                        type="text"
                        value="<?php echo htmlspecialchars($formValues['specialty'] ?? 'N/A'); ?>"
                        readonly>

                    <label>Schedule</label>

                    <input
                        type="datetime-local"
                        name="schedule_at"
                        value="<?php echo str_replace(' ', 'T', substr($formValues['schedule_at'], 0, 16)); ?>"
                        required>

                    <label>Maximum Capacity</label>

                    <input
                        type="number"
                        name="max_capacity"
                        value="<?php echo (int)$formValues['max_capacity']; ?>"
                        min="1"
                        required>

                    <button type="submit">
                        Update Class
                    </button>

                    <a href="classes.php"
                    class="action-btn danger-btn">
                        Cancel
                    </a>

                </form>

            </div>

        </main>
    </div>
<script src="js/sidebar.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const trainerSelect = document.getElementById('trainerSelect');
    const specialtyInput = document.getElementById('trainerSpecialty');

    if (!trainerSelect || !specialtyInput) {
        return;
    }

    const updateSpecialty = function () {
        const selectedOption = trainerSelect.options[trainerSelect.selectedIndex];
        specialtyInput.value = selectedOption && selectedOption.dataset.specialty
            ? selectedOption.dataset.specialty
            : 'N/A';
    };

    trainerSelect.addEventListener('change', updateSpecialty);
    updateSpecialty();
});
</script>
</body>
</html>