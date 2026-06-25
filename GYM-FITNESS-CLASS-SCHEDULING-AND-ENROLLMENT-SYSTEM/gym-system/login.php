<?php
// Login page. Redirects logged-in users to the dashboard.

session_start();

// If already logged in, go to the appropriate dashboard.
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {

    if ($_SESSION['user_role'] === 'member') {
        header("Location: member_dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            <h1>Gym System</h1>
            <p class="login-subtitle">Sign in to continue</p>

            <?php
            if (isset($_GET['error'])) {
                echo "<div class='error-message'>Invalid username or password.</div>";
            }
            ?>

            <form
                name="loginForm"
                action="php/process_login.php"
                method="POST"
                onsubmit="return validateLoginForm()">

                <label>Username</label>
                <input type="text" name="username" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button type="submit">Login</button>
            </form>

            <p class="login-subtitle" style="margin-top: 12px; font-size: 0.95rem;">
                Don't have an account? <a href="signup.php">Sign Up</a>
            </p>
        </div>
    </div>
<script src="js/validation.js"></script>
</body>
</html>