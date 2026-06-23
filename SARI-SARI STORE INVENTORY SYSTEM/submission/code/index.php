<?php
session_start();
require_once 'db_connect.php';

// Get dashboard statistics
$sql_products = "SELECT COUNT(*) as total FROM product";
$result_products = $conn->query($sql_products);
$total_products = $result_products->fetch_assoc()['total'];

$sql_stores = "SELECT COUNT(*) as total FROM store";
$result_stores = $conn->query($sql_stores);
$total_stores = $result_stores->fetch_assoc()['total'];

$sql_suppliers = "SELECT COUNT(*) as total FROM supplier";
$result_suppliers = $conn->query($sql_suppliers);
$total_suppliers = $result_suppliers->fetch_assoc()['total'];

$sql_low_stock = "SELECT COUNT(*) as total FROM low_stock_report";
$result_low_stock = $conn->query($sql_low_stock);
$low_stock_count = $result_low_stock ? $result_low_stock->fetch_assoc()['total'] : 0;

// Recent restock activities
$sql_recent = "SELECT r.*, s.store_name, p.product_name 
            FROM restock_log r
            JOIN store s ON r.store_id = s.store_id
            JOIN product p ON r.product_id = p.product_id
            ORDER BY r.restock_date DESC LIMIT 5";
$recent_restocks = $conn->query($sql_recent);

// Top products by stock value
$sql_top_products = "SELECT p.product_name, p.unit_price, 
                            SUM(i.quantity_on_hand) as total_quantity,
                            SUM(i.quantity_on_hand * p.unit_price) as total_value
                    FROM inventory i
                    JOIN product p ON i.product_id = p.product_id
                    GROUP BY p.product_id
                    ORDER BY total_value DESC LIMIT 5";
$top_products = $conn->query($sql_top_products);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sari-Sari Store Inventory System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Sari-Sari Store Inventory System</h1>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <a href="dashboard.php">Admin Dashboard</a>
                <?php else: ?>
                    <a href="store_owner_dashboard.php">My Dashboard</a>
                <?php endif; ?>
                <a href="logout.php" style="background-color: #e74c3c;">Logout (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" style="background-color: #27ae60;">Register Store</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <h2>Welcome to Sari-Sari Store Inventory System</h2>
        <p>Manage your store inventory efficiently</p>

        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Products</h3>
                <div class="number"><?php echo $total_products; ?></div>
            </div>
            <div class="card">
                <h3>Total Stores</h3>
                <div class="number"><?php echo $total_stores; ?></div>
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

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 2rem;">
            <div>
                <h3>Recent Restock Activities</h3>
                <table class="data-table">
                    <thead>
                        <tr><th>Date</th><th>Store</th><th>Product</th><th>Quantity</th></tr>
                    </thead>
                    <tbody>
                        <?php if($recent_restocks && $recent_restocks->num_rows > 0): ?>
                            <?php while($row = $recent_restocks->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($row['restock_date'])); ?></td>
                                <td><?php echo htmlspecialchars($row['store_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo $row['restock_quantity']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4">No restock activities found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div>
                <h3>Top Products by Inventory Value</h3>
                <table class="data-table">
                    <thead>
                        <tr><th>Product</th><th>Unit Price</th><th>Total Qty</th><th>Total Value</th></tr>
                    </thead>
                    <tbody>
                        <?php if($top_products && $top_products->num_rows > 0): ?>
                            <?php while($row = $top_products->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td>₱<?php echo number_format($row['unit_price'], 2); ?></td>
                                <td><?php echo $row['total_quantity']; ?></td>
                                <td>₱<?php echo number_format($row['total_value'], 2); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4">No inventory data found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if(!isset($_SESSION['user_id'])): ?>
            <div style="margin-top: 2rem; text-align: center; padding: 2rem; background: #e8f4f8; border-radius: 10px;">
                <h3>Own a Sari-Sari Store?</h3>
                <p>Register your store to start managing inventory!</p>
                <a href="register.php" class="btn btn-success" style="margin-top: 1rem;">Register Your Store</a>
                <a href="login.php" class="btn btn-primary" style="margin-top: 1rem;">Login to Account</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>&copy; 2026 Sari-Sari Store Inventory System</p>
    </div>
</body>
</html>
<?php $conn->close(); ?>