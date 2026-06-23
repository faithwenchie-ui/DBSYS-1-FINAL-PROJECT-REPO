<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in AND is a store owner
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'store_owner') {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];
$store_id = $_SESSION['store_id'];

// Get this store's information
$store_result = $conn->query("SELECT * FROM store WHERE store_id = $store_id");
$store = $store_result->fetch_assoc();

if (!$store) {
    die("Error: No store found for this user. Please contact admin.");
}

// Get products in THIS store only
$products_sql = "SELECT COUNT(*) as total FROM inventory WHERE store_id = $store_id";
$products_result = $conn->query($products_sql);
$total_products = $products_result->fetch_assoc()['total'];

// Get low stock items for THIS store only
$low_stock_sql = "SELECT COUNT(*) as total FROM low_stock_report WHERE store_id = $store_id";
$low_stock_result = $conn->query($low_stock_sql);
$low_stock_count = $low_stock_result ? $low_stock_result->fetch_assoc()['total'] : 0;

// Get recent restocks for THIS store
$recent_sql = "SELECT r.*, p.product_name 
            FROM restock_log r
            JOIN product p ON r.product_id = p.product_id
            WHERE r.store_id = $store_id
            ORDER BY r.restock_date DESC LIMIT 5";
$recent_restocks = $conn->query($recent_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($store['store_name']); ?> - Dashboard</title>
    <link rel="stylesheet" href="style.css">
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
            <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
        </div>

        <div class="alert alert-success">
            <strong>Your Store:</strong> <?php echo htmlspecialchars($store['store_name']); ?>
            <br><strong>Location:</strong> <?php echo htmlspecialchars($store['location']); ?>
            <br><strong>Phone:</strong> <?php echo htmlspecialchars($store['phone']); ?>
        </div>

        <div class="dashboard-cards">
            <div class="card">
                <h3>My Products</h3>
                <div class="number"><?php echo $total_products; ?></div>
            </div>
            <div class="card">
                <h3>Low Stock Items</h3>
                <div class="number" style="color: #e74c3c;"><?php echo $low_stock_count; ?></div>
            </div>
        </div>

        <div>
            <h3>Recent Restock Activities</h3>
            <table class="data-table">
                <thead>
                    <tr><th>Date</th><th>Product</th><th>Quantity</th><th>Restocked By</th></tr>
                </thead>
                <tbody>
                    <?php if($recent_restocks && $recent_restocks->num_rows > 0): ?>
                        <?php while($row = $recent_restocks->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($row['restock_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo $row['restock_quantity']; ?></td>
                            <td><?php echo htmlspecialchars($row['restocked_by']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No restock activities yet</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <a href="store_restock.php" class="btn btn-primary">➕ Restock Products</a>
            <a href="store_adjust.php" class="btn btn-warning">📝 Adjust Stock</a>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 <?php echo htmlspecialchars($store['store_name']); ?></p>
    </div>
</body>
</html>