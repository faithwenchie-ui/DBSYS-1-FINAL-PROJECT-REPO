<?php
require_once 'member_check.php';
require_once 'db_connect.php';

$member_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$sql = "SELECT c.class_id, c.class_name, t.full_name AS trainer_name, c.schedule_at, c.max_capacity,
        COUNT(e.enrollment_id) AS enrolled_count,
        GREATEST(c.max_capacity - COUNT(e.enrollment_id), 0) AS available_slots
        FROM classes c
        LEFT JOIN trainers t ON c.trainer_id = t.trainer_id
        LEFT JOIN enrollments e ON c.class_id = e.class_id
        GROUP BY c.class_id, c.class_name, t.full_name, c.schedule_at, c.max_capacity
        ORDER BY c.class_id ASC";

$classes = $conn->query($sql);

$enrolledSql = 'SELECT class_id FROM enrollments WHERE member_id = ?';
$enrolledStmt = $conn->prepare($enrolledSql);
$enrolledStmt->bind_param('i', $member_id);
$enrolledStmt->execute();
$enrolledResult = $enrolledStmt->get_result();
$enrolledClasses = [];
while ($row = $enrolledResult->fetch_assoc()) {
    $enrolledClasses[$row['class_id']] = true;
}
$enrolledStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Classes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-nav">
                <a href="member_dashboard.php" class="dashboard-link">Dashboard</a>
                <a href="member_classes.php" class="active">Available Classes</a>
                <a href="member_enrollments.php">My Enrollments</a>
                <a href="member_profile.php" class="dashboard-link">Profile</a>
                <a href="logout.php" class="logout" onclick="return showConfirmAction(event, 'Are you sure you want to log out?', 'logout.php');">Logout</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Available Fitness Classes</h1>
                <p>View class schedules, trainer details, and enroll in sessions.</p>
            </div>

            <?php
            if (isset($_GET['error'])) {
                $message = '';
                if ($_GET['error'] === 'duplicate') {
                    $message = 'You are already enrolled in that class.';
                } elseif ($_GET['error'] === 'invalid') {
                    $message = 'Unable to enroll. Please try again.';
                } elseif ($_GET['error'] === 'full') {
                    $message = 'This class is full and cannot accept more enrollments.';
                } else {
                    $message = 'An error occurred while enrolling.';
                }
                echo '<div class="error-message">' . htmlspecialchars($message) . '</div>';
            } elseif (isset($_GET['success'])) {
                echo '<div class="success-message">Enrollment successful.</div>';
            }
            ?>

            <table>
                <tr>
                    <th>Class Name</th>
                    <th>Trainer</th>
                    <th>Schedule</th>
                    <th>Enrolled</th>
                    <th>Capacity</th>
                    <th>Available Slots</th>
                    <th>Enrollment</th>
                </tr>

                <?php
                if ($classes->num_rows > 0) {
                    while ($row = $classes->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['class_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['trainer_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['schedule_at']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['enrolled_count']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['max_capacity']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['available_slots']) . '</td>';
                        echo '<td>';
                        if (isset($enrolledClasses[$row['class_id']])) {
                            echo '<span class="status-label">Enrolled</span>';
                        } elseif ($row['available_slots'] <= 0) {
                            echo '<span class="status-label full">Full</span>';
                        } else {
                            echo '<form method="POST" action="php/member_enroll.php" onsubmit="return showConfirmDelete(event, &quot;Are you sure you want to enroll in this class?&quot;, this)">';
                            echo '<input type="hidden" name="class_id" value="' . (int)$row['class_id'] . '">';
                            echo '<button type="submit" class="action-btn">Enroll</button>';
                            echo '</form>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7">No classes found.</td></tr>';
                }
                ?>
            </table>
        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>