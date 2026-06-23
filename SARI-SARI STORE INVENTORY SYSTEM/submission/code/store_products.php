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

$sql = "SELECT p.product_id, p.product_name, p.category, p.unit_price, 
            i.quantity_on_hand, i.reorder_level
        FROM inventory i
        JOIN product p ON i.product_id = p.product_id
        WHERE i.store_id = $store_id
        ORDER BY p.product_name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Products</title>
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
            <h2>My Products</h2>
        </div>

        <table class="data-table">
            <thead>
                <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Reorder Level</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): 
                        $status = $row['quantity_on_hand'] <= $row['reorder_level'] ? '⚠️ LOW STOCK' : '✅ Sufficient';
                        $status_color = $row['quantity_on_hand'] <= $row['reorder_level'] ? '#e74c3c' : '#27ae60';
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td>₱<?php echo number_format($row['unit_price'], 2); ?></td>
                        <td><?php echo $row['quantity_on_hand']; ?></td>
                        <td><?php echo $row['reorder_level']; ?></td>
                        <td style="color: <?php echo $status_color; ?>;"><?php echo $status; ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">No products found. Go to Restock to add products!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>