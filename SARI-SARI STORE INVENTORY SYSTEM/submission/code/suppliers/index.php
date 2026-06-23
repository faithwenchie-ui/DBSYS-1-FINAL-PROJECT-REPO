<?php
session_start();
require_once '../db_connect.php';

$result = $conn->query("SELECT * FROM supplier ORDER BY supplier_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Suppliers</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Admin Panel</h1>
        <div class="nav-links">
            <a href="../dashboard.php">Dashboard</a>
            <a href="../products/index.php">Products</a>
            <a href="../inventory/index.php">Inventory</a>
            <a href="index.php">Suppliers</a>
            <a href="../reports/low_stock.php">Low Stock</a>
            <a href="../logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h2>Suppliers</h2>
            <a href="create.php" class="btn btn-primary">+ Add Supplier</a>
        </div>

        <table class="data-table">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Contact</th><th>Phone</th><th>Email</th><th>Address</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['supplier_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact_person']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['supplier_id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $row['supplier_id']; ?>" class="btn btn-danger" onclick="return confirm('Delete?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>