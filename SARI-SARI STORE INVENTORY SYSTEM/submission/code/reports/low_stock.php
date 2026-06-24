<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_role = $_SESSION['role'];
$user_store_id = $_SESSION['store_id'];
$user_name = $_SESSION['user_name'];

if ($user_role == 'admin') {
    $sql = "SELECT * FROM low_stock_report ORDER BY units_to_order DESC";
} else {
    $sql = "SELECT * FROM low_stock_report WHERE store_id = $user_store_id ORDER BY units_to_order DESC";
}

$result = $conn->query($sql);

$store_name = '';
if ($user_role == 'store_owner' && $user_store_id) {
    $store_result = $conn->query("SELECT store_name FROM store WHERE store_id = $user_store_id");
    if ($store_result && $store_result->num_rows > 0) {
        $store = $store_result->fetch_assoc();
        $store_name = $store['store_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Low Stock Report</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Sari-Sari Store Inventory System</h1>
        <div class="nav-links">
            <?php if($user_role == 'admin'): ?>
                <a href="../dashboard.php">Dashboard</a>
                <a href="../products/index.php">Products</a>
                <a href="../inventory/index.php">Inventory</a>
                <a href="../suppliers/index.php">Suppliers</a>
                <a href="low_stock.php">Low Stock Report</a>
                <a href="../logout.php" style="background-color: #e74c3c;">Logout</a>
            <?php else: ?>
                <a href="../store_owner_dashboard.php">Dashboard</a>
                <a href="../store_products.php">My Products</a>
                <a href="../store_inventory.php">My Inventory</a>
                <a href="../store_restock.php">Restock</a>
                <a href="../store_adjust.php">Adjust Stock</a>
                <a href="low_stock.php">Low Stock Report</a>
                <a href="../logout.php" style="background-color: #e74c3c;">Logout</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h2>Low Stock Report</h2>
            <?php if($store_name): ?>
                <p>Store: <strong><?php echo htmlspecialchars($store_name); ?></strong></p>
            <?php endif; ?>
        </div>

        <?php if($result && $result->num_rows > 0): ?>
            <table class="data-table">
                <thead>
                    <tr><th>Store</th><th>Location</th><th>Product</th><th>Category</th><th>Current Stock</th><th>Reorder Level</th><th>Units to Order</th></tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['store_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo $row['quantity_on_hand']; ?></td>
                        <td><?php echo $row['reorder_level']; ?></td>
                        <td style="color: #e74c3c; font-weight: bold;"><?php echo $row['units_to_order']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-success">✅ No low stock items found!</div>
        <?php endif; ?>
    </div>
</body>
</html>