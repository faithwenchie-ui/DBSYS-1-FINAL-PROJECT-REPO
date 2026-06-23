<?php
session_start();
require_once '../db_connect.php';

$sql = "SELECT i.*, s.store_name, p.product_name, p.unit_price, p.category
        FROM inventory i
        JOIN store s ON i.store_id = s.store_id
        JOIN product p ON i.product_id = p.product_id
        ORDER BY i.quantity_on_hand ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Inventory</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Admin Panel</h1>
        <div class="nav-links">
            <a href="../dashboard.php">Dashboard</a>
            <a href="../products/index.php">Products</a>
            <a href="index.php">Inventory</a>
            <a href="../suppliers/index.php">Suppliers</a>
            <a href="../reports/low_stock.php">Low Stock</a>
            <a href="../logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h2>All Inventory</h2>
            <a href="restock.php" class="btn btn-primary">Restock</a>
            <a href="adjust.php" class="btn btn-warning">Adjust Stock</a>
        </div>

        <table class="data-table">
            <thead>
                <tr><th>Store</th><th>Product</th><th>Category</th><th>Quantity</th><th>Price</th><th>Total Value</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): 
                    $total = $row['quantity_on_hand'] * $row['unit_price'];
                    $status = $row['quantity_on_hand'] <= $row['reorder_level'] ? 'Low Stock' : 'OK';
                ?>
                <tr>
                    <td><?php echo $row['store_name']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['quantity_on_hand']; ?></td>
                    <td>₱<?php echo number_format($row['unit_price'], 2); ?></td>
                    <td>₱<?php echo number_format($total, 2); ?></td>
                    <td style="color: <?php echo $status == 'Low Stock' ? '#e74c3c' : '#27ae60'; ?>"><?php echo $status; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>