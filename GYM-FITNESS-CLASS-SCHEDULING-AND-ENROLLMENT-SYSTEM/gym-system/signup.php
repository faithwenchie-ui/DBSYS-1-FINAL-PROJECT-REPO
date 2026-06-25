<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === 'member') {
        header('Location: member_dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <h1>Create Member Account</h1>
            <p class="login-subtitle">Sign up to browse classes and enroll in sessions.</p>

            <?php
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                if ($error === 'duplicate') {
                    $message = 'A member with the same name, email, or phone already exists.';
                } elseif ($error === 'reserved') {
                    $message = 'The chosen username or email is reserved. Please use different details.';
                } else {
                    $message = 'Please check your information and try again.';
                }
                echo "<div class='error-message'>" . htmlspecialchars($message) . "</div>";
            }
            ?>

            <form
                name="signupForm"
                action="php/process_signup.php"
                method="POST"
                onsubmit="return validateSignupForm()">

                <label>Full Name</label>
                <input type="text" name="full_name" required>

                <label>Email Address</label>
                <input type="email" name="email" required>

                <label>Phone Number</label>
                <input type="text" name="phone" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>

                <button type="submit">Sign Up</button>
            </form>

            <p class="login-subtitle" style="margin-top: 12px; font-size: 0.95rem;">
                Already have an account? <a href="login.php">Log In</a>
            </p>
        </div>
    </div>
<script src="js/validation.js"></script>
</body>
</html>