<?php
require_once 'member_check.php';
require_once 'db_connect.php';

$member_id = $_SESSION['user_id'];

$stmt = $conn->prepare('SELECT member_id, full_name, email, phone FROM members WHERE member_id = ? LIMIT 1');
$stmt->bind_param('i', $member_id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();
$stmt->close();

if (!$member) {
    header('Location: member_dashboard.php');
    exit();
}

$message = '';
$messageType = '';

if (isset($_GET['success'])) {
    $messageType = 'success-message';
    if ($_GET['success'] === 'password') {
        $message = 'Your password has been updated.';
    } else {
        $message = 'Your profile has been updated.';
    }
} elseif (isset($_GET['error'])) {
    $messageType = 'error-message';

    if ($_GET['error'] === 'invalid') {
        $message = 'Please provide a valid name, email, and 11-digit phone number.';
    } elseif ($_GET['error'] === 'duplicate') {
        $message = 'The email, name, or phone number is already in use by another member.';
    } elseif ($_GET['error'] === 'reserved') {
        $message = 'That name or email is reserved. Please use a different value.';
    } elseif ($_GET['error'] === 'password_incorrect') {
        $message = 'Current password is incorrect.';
    } elseif ($_GET['error'] === 'password_mismatch') {
        $message = 'New passwords do not match or are too short.';
    } else {
        $message = 'Unable to update your profile. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-nav">
                <a href="member_dashboard.php" class="dashboard-link">Dashboard</a>
                <a href="member_classes.php" class="dashboard-link">Available Classes</a>
                <a href="member_enrollments.php" class="dashboard-link">My Enrollments</a>
                <a href="member_profile.php" class="active">Profile</a>
                <a href="logout.php" class="logout" onclick="return showConfirmAction(event, 'Are you sure you want to log out?', 'logout.php');">Logout</a>
            </div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>My Profile</h1>
                <p>Update your name, email address, and phone number.</p>
            </div>

            <?php if ($message !== ''): ?>
                <div class="<?php echo htmlspecialchars($messageType); ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form name="profileForm" action="php/update_profile.php" method="POST" onsubmit="return validateEditProfileForm()">
                    <label>Membership ID</label>
                    <input type="text" value="<?php echo htmlspecialchars($member['member_id']); ?>" disabled>

                    <label>Full Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($member['full_name']); ?>" required>

                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>

                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>" required>

                    <button type="submit">Save Profile</button>
                </form>
            </div>

            <div class="form-container" style="margin-top: 26px;">
                <h2 style="margin-bottom: 18px;">Change Password</h2>
                <form name="changePasswordForm" action="php/change_password.php" method="POST" onsubmit="return validateChangePasswordForm()">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>

                    <label>New Password</label>
                    <input type="password" name="new_password" required>

                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_new_password" required>

                    <button type="submit">Update Password</button>
                </form>
            </div>
        </main>
    </div>
<script src="js/validation.js"></script>
<script src="js/sidebar.js"></script>
</body>
</html>