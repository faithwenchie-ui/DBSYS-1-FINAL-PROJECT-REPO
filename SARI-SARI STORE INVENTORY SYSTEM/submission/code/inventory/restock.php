<?php
session_start();
require_once '../db_connect.php';

$stores = $conn->query("SELECT store_id, store_name FROM store");
$products = $conn->query("SELECT product_id, product_name FROM product");
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $store_id = $_POST['store_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $restocked_by = trim($_POST['restocked_by']);
    $unit_cost = $_POST['unit_cost'];
    
    if ($store_id == 0 || $product_id == 0) {
        $error = "Please select store and product";
    } elseif (empty($quantity) || $quantity <= 0) {
        $error = "Valid quantity required";
    } elseif (empty($restocked_by)) {
        $error = "Restocked by required";
    } else {
        $stmt = $conn->prepare("CALL RestockProduct(?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisd", $store_id, $product_id, $quantity, $restocked_by, $unit_cost);
        if ($stmt->execute()) {
            $success = "Restocked successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Restock</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Restock</h1>
        <div class="nav-links">
            <a href="../dashboard.php">Dashboard</a>
            <a href="index.php">Inventory</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2>Restock Product</h2>
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
                    <label>Quantity</label>
                    <input type="number" name="quantity" required min="1">
                </div>
                <div class="form-group">
                    <label>Unit Cost</label>
                    <input type="number" step="0.01" name="unit_cost">
                </div>
                <div class="form-group">
                    <label>Restocked By</label>
                    <input type="text" name="restocked_by" required>
                </div>
                <button type="submit" class="btn btn-success">Process Restock</button>
            </form>
        </div>
    </div>
</body>
</html>