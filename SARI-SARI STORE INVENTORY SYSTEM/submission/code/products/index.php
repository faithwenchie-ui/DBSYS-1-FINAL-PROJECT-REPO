<?php
session_start();
require_once '../db_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT p.*, s.supplier_name 
        FROM product p
        LEFT JOIN supplier s ON p.supplier_id = s.supplier_id";

if (!empty($search)) {
    $sql .= " WHERE p.product_name LIKE '%$search%' OR p.category LIKE '%$search%'";
}
$sql .= " ORDER BY p.product_name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="../style.css">
    <script>
        function confirmDelete(id, name) {
            return confirm('Delete product: ' + name + '?');
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Admin Panel</h1>
        <div class="nav-links">
            <a href="../dashboard.php">Dashboard</a>
            <a href="index.php">Products</a>
            <a href="../inventory/index.php">Inventory</a>
            <a href="../suppliers/index.php">Suppliers</a>
            <a href="../reports/low_stock.php">Low Stock</a>
            <a href="../logout.php" style="background-color: #e74c3c;">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h2>Products</h2>
            <a href="create.php" class="btn btn-primary">+ Add Product</a>
        </div>

        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search product..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if($search): ?>
                <a href="index.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>

        <table class="data-table">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Supplier</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['product_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td>₱<?php echo number_format($row['unit_price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['supplier_name'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['product_id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $row['product_id']; ?>" onclick="return confirmDelete(<?php echo $row['product_id']; ?>, '<?php echo addslashes($row['product_name']); ?>')" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>