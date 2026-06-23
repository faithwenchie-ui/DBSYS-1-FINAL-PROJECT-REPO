<?php
session_start();
require_once 'db_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $store_name = trim($_POST['store_name']);
    $store_location = trim($_POST['store_location']);
    $store_phone = trim($_POST['store_phone']);
    
    // Validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        // Check if email exists
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "Email already registered";
        } else {
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // 1. Create the store
                $store_stmt = $conn->prepare("INSERT INTO store (store_name, location, phone, owner) VALUES (?, ?, ?, ?)");
                $store_stmt->bind_param("ssss", $store_name, $store_location, $store_phone, $full_name);
                $store_stmt->execute();
                $new_store_id = $conn->insert_id;
                
                // 2. Create the user as STORE OWNER
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $user_stmt = $conn->prepare("INSERT INTO users (full_name, email, password, store_id, role) VALUES (?, ?, ?, ?, 'store_owner')");
                $user_stmt->bind_param("sssi", $full_name, $email, $hashed_password, $new_store_id);
                $user_stmt->execute();
                
                $conn->commit();
                $success = "Registration successful! You can now login.";
                
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register New Store</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function validateForm() {
            var pass = document.getElementById('password').value;
            var confirm = document.getElementById('confirm_password').value;
            
            if (pass.length < 6) {
                alert('Password must be at least 6 characters');
                return false;
            }
            if (pass !== confirm) {
                alert('Passwords do not match');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Sari-Sari Store Inventory System</h1>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register Store</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2>Open a New Sari-Sari Store</h2>
            
            <?php if($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?> 
                    <br><a href="login.php">Click here to login</a>
                </div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" onsubmit="return validateForm()">
                <h3>Your Information</h3>
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password * (min 6 characters)</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                
                <h3>Store Information</h3>
                <div class="form-group">
                    <label>Store Name *</label>
                    <input type="text" name="store_name" required>
                </div>
                <div class="form-group">
                    <label>Store Location *</label>
                    <input type="text" name="store_location" required>
                </div>
                <div class="form-group">
                    <label>Store Phone *</label>
                    <input type="text" name="store_phone" required>
                </div>
                
                <button type="submit" class="btn btn-success">Register Store</button>
            </form>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2024 Sari-Sari Store Inventory System</p>
    </div>
</body>
</html>