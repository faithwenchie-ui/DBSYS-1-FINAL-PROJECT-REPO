<?php
session_start();
require_once '../db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM supplier WHERE supplier_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$supplier = $stmt->get_result()->fetch_assoc();

if (!$supplier) {
    header("Location: index.php");
    exit();
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['supplier_name']);
    $contact = trim($_POST['contact_person']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    
    $stmt = $conn->prepare("UPDATE supplier SET supplier_name=?, contact_person=?, phone=?, email=?, address=? WHERE supplier_id=?");
    $stmt->bind_param("sssssi", $name, $contact, $phone, $email, $address, $id);
    if ($stmt->execute()) {
        $success = "Supplier updated!";
        $supplier = ['supplier_id'=>$id, 'supplier_name'=>$name, 'contact_person'=>$contact, 'phone'=>$phone, 'email'=>$email, 'address'=>$address];
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Edit Supplier</h1>
        <div class="nav-links">
            <a href="../dashboard.php">Dashboard</a>
            <a href="index.php">Suppliers</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2>Edit Supplier</h2>
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Supplier Name *</label>
                    <input type="text" name="supplier_name" value="<?php echo htmlspecialchars($supplier['supplier_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" value="<?php echo htmlspecialchars($supplier['contact_person']); ?>">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($supplier['phone']); ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($supplier['email']); ?>">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3"><?php echo htmlspecialchars($supplier['address']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>