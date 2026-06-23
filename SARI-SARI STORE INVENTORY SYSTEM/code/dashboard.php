<?php
session_start();
require_once 'db_connect.php';

// Check if user is logged in AND is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['user_name'];

// Get all statistics for admin
$total_stores = $conn->query("SELECT COUNT(*) as total FROM store")->fetch_assoc()['total'];
$total_products = $conn->query("SELECT COUNT(*) as total FROM product")->fetch_assoc()['total'];
$total_suppliers = $conn->query("SELECT COUNT(*) as total FROM supplier")->fetch_assoc()['total'];
$low_stock_count = $conn->query("SELECT COUNT(*) as total FROM low_stock_report")->fetch_assoc()['total'];

// Get recent restocks
$recent_restocks = $conn->query("SELECT r.*, s.store_name, p.product_name 
                                FROM restock_log r
                                JOIN store s ON r.store_id = s.store_id
                                JOIN product p ON r.product_id = p.product_id
                                ORDER BY r.restock_date DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Admin Dashboard</h1>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="products/index.php">Products</a>
            <a href="inventory/index.php">Inventory</a>
            <a href="suppliers/index.php">Suppliers</a>
            <a href="reports/low_stock.php">Low Stock Report</a>
            <a href="register.php">Register Store</a>
            <a href="logout.php" style="background-color: #e74c3c;">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h2>Admin Dashboard</h2>
            <p>Welcome, <?php echo htmlspecialchars($user_name); ?>!</p>
        </div>

        <div class="alert alert-info">
            <strong>Admin Mode:</strong> You have full access to ALL stores and system settings.
        </div>

        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Stores</h3>
                <div class="number"><?php echo $total_stores; ?></div>
            </div>
            <div class="card">
                <h3>Total Products</h3>
                <div class="number"><?php echo $total_products; ?></div>
            </div>
            <div class="card">
                <h3>Total Suppliers</h3>
                <div class="number"><?php echo $total_suppliers; ?></div>
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
                    <tr><th>Date</th><th>Store</th><th>Product</th><th>Quantity</th></tr>
                </thead>
                <tbody>
                    <?php while($row = $recent_restocks->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($row['restock_date'])); ?></td>
                        <td><?php echo htmlspecialchars($row['store_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo $row['restock_quantity']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Sari-Sari Store Inventory System - Admin Panel</p>
    </div>
</body>
</html>