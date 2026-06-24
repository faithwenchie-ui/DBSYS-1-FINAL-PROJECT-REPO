<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'store_owner') {
    header("Location: login.php");
    exit();
}

$store_id = $_SESSION['store_id'];

$sql = "SELECT p.*, i.quantity_on_hand 
        FROM product p
        JOIN inventory i ON p.product_id = i.product_id
        WHERE i.store_id = $store_id
        ORDER BY p.product_name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Products</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 My Store</h1>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="my_products.php">My Products</a>
            <a href="my_inventory.php">My Inventory</a>
            <a href="reports/low_stock.php">Low Stock</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2>My Products</h2>
        <table class="data-table">
            <thead>
                <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td>₱<?php echo number_format($row['unit_price'], 2); ?></td>
                    <td><?php echo $row['quantity_on_hand']; ?></td>
                    <td><?php echo $row['quantity_on_hand'] <= 10 ? '⚠️ Low' : '✅ OK'; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>