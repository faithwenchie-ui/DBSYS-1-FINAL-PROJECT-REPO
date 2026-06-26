<?php
require_once 'member_check.php';
require_once 'db_connect.php';

$member_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$sql = "SELECT e.enrollment_id, c.class_name, t.full_name AS trainer_name, c.schedule_at, c.max_capacity
        FROM enrollments e
        INNER JOIN classes c ON e.class_id = c.class_id
        LEFT JOIN trainers t ON c.trainer_id = t.trainer_id
        WHERE e.member_id = ?
        ORDER BY c.schedule_at ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $member_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Enrollments</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-nav">
                <a href="member_dashboard.php" class="dashboard-link">Dashboard</a>
                <a href="member_classes.php" class="dashboard-link">Available Classes</a>
                <a href="member_enrollments.php" class="active">My Enrollments</a>
                <a href="member_profile.php" class="dashboard-link">Profile</a>
                <a href="logout.php" class="logout" onclick="return showConfirmAction(event, 'Are you sure you want to log out?', 'logout.php');">Logout</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>My Enrollments</h1>
                <p>View the classes you are enrolled in. Use the Unenroll button to remove a class, and confirm the action when prompted.</p>
            </div>

            <?php
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                $message = '';
                if ($error === 'invalid') {
                    $message = 'Invalid request. Please try again.';
                } elseif ($error === 'notfound') {
                    $message = 'Enrollment not found.';
                } elseif ($error === 'unauthorized') {
                    $message = 'You are not authorized to remove that enrollment.';
                } else {
                    $message = 'An error occurred while unenrolling.';
                }
                echo '<div class="error-message">' . htmlspecialchars($message) . '</div>';
            } elseif (isset($_GET['success'])) {
                if ($_GET['success'] === 'unenrolled') {
                    echo '<div class="success-message">You have been unenrolled successfully.</div>';
                }
            }
            ?>

            <table>
                <tr>
                    <th>Class Name</th>
                    <th>Trainer</th>
                    <th>Schedule</th>
                    <th>Max Capacity</th>
                    <th>Action</th>
                </tr>

                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['class_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['trainer_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['schedule_at']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['max_capacity']) . '</td>';
                        echo '<td>';
                        echo '<form method="POST" action="php/member_unenroll.php" onsubmit="return showConfirmDelete(event, &quot;Are you sure you want to unenroll from this class?&quot;, this)">';
                        echo '<input type="hidden" name="id" value="' . (int)$row['enrollment_id'] . '">';
                        echo '<button type="submit" class="danger-btn">Unenroll</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">You are not enrolled in any classes yet.</td></tr>';
                }
                ?>
            </table>

        </main>
    </div>
<script src="js/sidebar.js"></script>
</body>
</html>