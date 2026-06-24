<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'store_owner') {
    header("Location: login.php");
    exit();
}

$store_id = $_SESSION['store_id'];

$store_result = $conn->query("SELECT store_name FROM store WHERE store_id = $store_id");
$store = $store_result->fetch_assoc();

$products = $conn->query("
    SELECT i.product_id, p.product_name, i.quantity_on_hand
    FROM inventory i
    JOIN product p ON i.product_id = p.product_id
    WHERE i.store_id = $store_id
    ORDER BY p.product_name
");

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $adjustment_type = $_POST['adjustment_type'];
    $quantity_changed = $_POST['quantity_changed'];
    $reason = trim($_POST['reason']);
    $authorized_by = trim($_POST['authorized_by']);
    
    if ($product_id == 0) {
        $error = "Please select a product";
    } elseif ($quantity_changed === '' || $quantity_changed == 0) {
        $error = "Valid quantity change is required";
    } elseif (empty($authorized_by)) {
        $error = "Authorized by is required";
    } else {
        $stmt = $conn->prepare("INSERT INTO stock_adjustment_log (store_id, product_id, adjustment_type, quantity_changed, reason, authorized_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisiss", $store_id, $product_id, $adjustment_type, $quantity_changed, $reason, $authorized_by);
        
        if ($stmt->execute()) {
            $update = $conn->prepare("UPDATE inventory SET quantity_on_hand = quantity_on_hand + ? WHERE store_id = ? AND product_id = ?");
            $update->bind_param("iii", $quantity_changed, $store_id, $product_id);
            $update->execute();
            $success = "Stock adjusted successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adjust Stock</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function validateForm() {
            var product = document.getElementById('product_id').value;
            var quantity = document.getElementById('quantity_changed').value;
            if (product == '0') {
                alert('Please select a product');
                return false;
            }
            if (quantity === '' || parseInt(quantity) == 0) {
                alert('Valid quantity change is required');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <h1>🏪 <?php echo htmlspecialchars($store['store_name']); ?></h1>
        <div class="nav-links">
            <a href="store_owner_dashboard.php">Dashboard</a>
            <a href="store_products.php">My Products</a>
            <a href="store_inventory.php">My Inventory</a>
            <a href="store_restock.php">Restock</a>
            <a href="store_adjust.php">Adjust Stock</a>
            <a href="reports/low_stock.php">Low Stock Report</a>
            <a href="logout.php" style="background-color: #e74c3c;">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h2>Adjust Stock</h2>
            <a href="store_owner_dashboard.php" class="btn btn-secondary">← Back</a>
        </div>

        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label>Select Product *</label>
                    <select name="product_id" id="product_id" required>
                        <option value="0">-- Select a product --</option>
                        <?php while($row = $products->fetch_assoc()): ?>
                            <option value="<?php echo $row['product_id']; ?>">
                                <?php echo htmlspecialchars($row['product_name']); ?> 
                                (Current: <?php echo $row['quantity_on_hand']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Adjustment Type *</label>
                    <select name="adjustment_type" required>
                        <option value="">Select Type</option>
                        <option value="Damaged">Damaged</option>
                        <option value="Expired">Expired</option>
                        <option value="Return">Return</option>
                        <option value="Theft">Theft / Loss</option>
                        <option value="Correction">Correction</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Quantity Change *</label>
                    <input type="number" name="quantity_changed" id="quantity_changed" required>
                    <small>Negative for loss (-5), Positive for return (+2)</small>
                </div>
                
                <div class="form-group">
                    <label>Reason</label>
                    <textarea name="reason" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Authorized By *</label>
                    <input type="text" name="authorized_by" required>
                </div>
                
                <button type="submit" class="btn btn-warning">Apply Adjustment</button>
            </form>
        </div>
    </div>
</body>
</html>