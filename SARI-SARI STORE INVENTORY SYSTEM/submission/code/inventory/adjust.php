<?php
session_start();
require_once '../db_connect.php';

$stores = $conn->query("SELECT store_id, store_name FROM store");
$products = $conn->query("SELECT product_id, product_name FROM product");
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $store_id = $_POST['store_id'];
    $product_id = $_POST['product_id'];
    $adjustment_type = $_POST['adjustment_type'];
    $quantity_changed = $_POST['quantity_changed'];
    $reason = trim($_POST['reason']);
    $authorized_by = trim($_POST['authorized_by']);
    
    if ($store_id == 0 || $product_id == 0) {
        $error = "Please select store and product";
    } elseif ($quantity_changed === '' || $quantity_changed == 0) {
        $error = "Valid quantity change required";
    } elseif (empty($authorized_by)) {
        $error = "Authorized by required";
    } else {
        $stmt = $conn->prepare("INSERT INTO stock_adjustment_log (store_id, product_id, adjustment_type, quantity_changed, reason, authorized_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisiss", $store_id, $product_id, $adjustment_type, $quantity_changed, $reason, $authorized_by);
        if ($stmt->execute()) {
            $update = $conn->prepare("UPDATE inventory SET quantity_on_hand = quantity_on_hand + ? WHERE store_id = ? AND product_id = ?");
            $update->bind_param("iii", $quantity_changed, $store_id, $product_id);
            $update->execute();
            $success = "Stock adjusted!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Adjust Stock</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Adjust Stock</h1>
        <div class="nav-links">
            <a href="../dashboard.php">Dashboard</a>
            <a href="index.php">Inventory</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2>Adjust Stock</h2>
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Store</label>
                    <select name="store_id" required>
                        <option value="0">Select</option>
                        <?php while($row = $stores->fetch_assoc()): ?>
                            <option value="<?php echo $row['store_id']; ?>"><?php echo $row['store_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Product</label>
                    <select name="product_id" required>
                        <option value="0">Select</option>
                        <?php while($row = $products->fetch_assoc()): ?>
                            <option value="<?php echo $row['product_id']; ?>"><?php echo $row['product_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Adjustment Type</label>
                    <select name="adjustment_type" required>
                        <option>Damaged</option><option>Expired</option>
                        <option>Return</option><option>Theft</option>
                        <option>Correction</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity Change</label>
                    <input type="number" name="quantity_changed" required>
                    <small>Negative for loss (-5), Positive for return (+2)</small>
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <textarea name="reason" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Authorized By</label>
                    <input type="text" name="authorized_by" required>
                </div>
                <button type="submit" class="btn btn-warning">Apply</button>
            </form>
        </div>
    </div>
</body>
</html>