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

// Get products NOT yet in this store's inventory
$available_products = $conn->query("
    SELECT p.product_id, p.product_name, p.category, p.unit_price
    FROM product p
    WHERE p.product_id NOT IN (
        SELECT product_id FROM inventory WHERE store_id = $store_id
    )
    ORDER BY p.product_name
");

// Get products already in inventory
$existing_products = $conn->query("
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
    $quantity = $_POST['quantity'];
    $restocked_by = trim($_POST['restocked_by']);
    $unit_cost = $_POST['unit_cost'];
    
    if ($product_id == 0) {
        $error = "Please select a product";
    } elseif (empty($quantity) || $quantity <= 0) {
        $error = "Valid quantity is required";
    } elseif (empty($restocked_by)) {
        $error = "Restocked by is required";
    } else {
        $stmt = $conn->prepare("CALL RestockProduct(?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisd", $store_id, $product_id, $quantity, $restocked_by, $unit_cost);
        
        if ($stmt->execute()) {
            $success = "Product restocked successfully!";
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
    <title>Restock</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function validateForm() {
            var product = document.getElementById('product_id').value;
            var quantity = document.getElementById('quantity').value;
            if (product == '0') {
                alert('Please select a product');
                return false;
            }
            if (quantity === '' || parseInt(quantity) <= 0) {
                alert('Valid quantity is required');
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
            <h2>Restock Products</h2>
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
                        
                        <?php if($existing_products && $existing_products->num_rows > 0): ?>
                            <optgroup label="Products already in your store">
                                <?php while($row = $existing_products->fetch_assoc()): ?>
                                    <option value="<?php echo $row['product_id']; ?>">
                                        <?php echo htmlspecialchars($row['product_name']); ?> 
                                        (Current: <?php echo $row['quantity_on_hand']; ?>)
                                    </option>
                                <?php endwhile; ?>
                            </optgroup>
                        <?php endif; ?>
                        
                        <?php if($available_products && $available_products->num_rows > 0): ?>
                            <optgroup label="New products you can add">
                                <?php while($row = $available_products->fetch_assoc()): ?>
                                    <option value="<?php echo $row['product_id']; ?>">
                                        <?php echo htmlspecialchars($row['product_name']); ?> 
                                        (₱<?php echo number_format($row['unit_price'], 2); ?>)
                                    </option>
                                <?php endwhile; ?>
                            </optgroup>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Quantity to Restock *</label>
                    <input type="number" name="quantity" id="quantity" required min="1">
                </div>
                
                <div class="form-group">
                    <label>Unit Cost (₱) - Optional</label>
                    <input type="number" step="0.01" name="unit_cost">
                </div>
                
                <div class="form-group">
                    <label>Restocked By *</label>
                    <input type="text" name="restocked_by" required placeholder="Your name">
                </div>
                
                <button type="submit" class="btn btn-success">Process Restock</button>
            </form>
        </div>
    </div>
</body>
</html>