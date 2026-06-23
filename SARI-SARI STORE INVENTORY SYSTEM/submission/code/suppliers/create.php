<?php
session_start();
require_once '../db_connect.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['supplier_name']);
    $contact = trim($_POST['contact_person']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    
    if (empty($name)) {
        $error = "Supplier name required";
    } else {
        $stmt = $conn->prepare("INSERT INTO supplier (supplier_name, contact_person, phone, email, address) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $contact, $phone, $email, $address);
        if ($stmt->execute()) {
            $success = "Supplier added!";
            $_POST = array();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Supplier</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Add Supplier</h1>
        <div class="nav-links">
            <a href="../dashboard.php">Dashboard</a>
            <a href="index.php">Suppliers</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2>Add New Supplier</h2>
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Supplier Name *</label>
                    <input type="text" name="supplier_name" required>
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>