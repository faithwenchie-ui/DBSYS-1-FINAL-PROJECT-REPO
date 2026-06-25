<?php
// login.php - Modified to support Role-Based Access Control (RBAC)
session_start();
require_once 'db_connect.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($username === 'UAct_admin' && $password === 'UAT.admin@123') {
        $_SESSION['authenticated'] = true;
        $_SESSION['user'] = $username;
        $_SESSION['role'] = 'admin'; // Admin Role Assigned
        
        header("Location: index.php");
        exit;
    } elseif ($username === 'juan@delacruz' && $password === 'J_delacruz@123') {
        $_SESSION['authenticated'] = true;
        $_SESSION['user'] = $username;
        $_SESSION['role'] = 'user';  // Standard User Role Assigned
        
        header("Location: index.php");
        exit;
    } else {
        $error_message = "Incorrect username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #F8FAFC;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #1F2937;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(15, 118, 110, 0.1), 0 8px 10px -6px rgba(15, 118, 110, 0.05);
            border-top: 6px solid #0F766E; /* Dark Teal Accent Line */
            box-sizing: border-box;
        }

        .login-card h2 {
            margin: 0 0 5px 0;
            font-size: 1.8rem;
            color: #0F766E;
            text-align: center;
        }

        .login-card .subtitle {
            margin: 0 0 30px 0;
            font-size: 0.9rem;
            color: #6B7280;
            text-align: center;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.95rem;
            background-color: #F8FAFC;
            color: #1F2937;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #14B8A6; /* Primary Teal Accent */
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: #0F766E; /* Dark Teal */
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(15, 118, 110, 0.2);
            transition: all 0.2s ease;
        }

        .login-btn:hover {
            background: #14B8A6; /* Hover shift to Primary Teal */
            box-shadow: 0 10px 15px -3px rgba(20, 184, 166, 0.2);
            transform: translateY(-1px);
        }

        .alert-error {
            background-color: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Log In</h2>
        <p class="subtitle" style="margin: 0 0 25px 0; font-size: 0.9rem; color: #6B7280; text-align: center;">UAct Event Tracker Gateway</p>

        <?php if (!empty($error_message)): ?>
            <div class="alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="log_in.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <button type="submit" class="login-btn">Log In</button>
        </form>
    </div>

</body>
</html>