<?php
session_start();
require_once '../db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: index.php");
    exit();
}

$suppliers = $conn->query("SELECT supplier_id, supplier_name FROM supplier ORDER BY supplier_name");
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $unit_price = $_POST['unit_price'];
    $weight_kg = $_POST['weight_kg'];
    $supplier_id = $_POST['supplier_id'];
    
    if (empty($product_name)) {
        $error = "Product name is required";
    } elseif (empty($unit_price) || $unit_price <= 0) {
        $error = "Valid price is required";
    } else {
        $stmt = $conn->prepare("UPDATE product SET product_name=?, category=?, unit_price=?, weight_kg=?, supplier_id=? WHERE product_id=?");
        $stmt->bind_param("ssddii", $product_name, $category, $unit_price, $weight_kg, $supplier_id, $id);
        
        if ($stmt->execute()) {
            $success = "Product updated!";
            $product['product_name'] = $product_name;
            $product['category'] = $category;
            $product['unit_price'] = $unit_price;
            $product['weight_kg'] = $weight_kg;
            $product['supplier_id'] = $supplier_id;
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <nav class="navbar">
        <h1>🏪 Edit Product</h1>
        <div class="nav-links">
            <a href="../dashboard.php">Dashboard</a>
            <a href="index.php">Products</a>
            <a href="../logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h2>Edit Product</h2>
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="">Select</option>
                        <option <?php echo $product['category'] == 'Beverages' ? 'selected' : ''; ?>>Beverages</option>
                        <option <?php echo $product['category'] == 'Snacks' ? 'selected' : ''; ?>>Snacks</option>
                        <option <?php echo $product['category'] == 'Noodles' ? 'selected' : ''; ?>>Noodles</option>
                        <option <?php echo $product['category'] == 'Canned Goods' ? 'selected' : ''; ?>>Canned Goods</option>
                        <option <?php echo $product['category'] == 'Dairy' ? 'selected' : ''; ?>>Dairy</option>
                        <option <?php echo $product['category'] == 'Coffee' ? 'selected' : ''; ?>>Coffee</option>
                        <option <?php echo $product['category'] == 'Condiments' ? 'selected' : ''; ?>>Condiments</option>
                        <option <?php echo $product['category'] == 'Frozen' ? 'selected' : ''; ?>>Frozen</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Unit Price *</label>
                    <input type="number" step="0.01" name="unit_price" value="<?php echo $product['unit_price']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Weight (kg)</label>
                    <input type="number" step="0.01" name="weight_kg" value="<?php echo $product['weight_kg']; ?>">
                </div>
                <div class="form-group">
                    <label>Supplier</label>
                    <select name="supplier_id">
                        <option value="">Select</option>
                        <?php while($row = $suppliers->fetch_assoc()): ?>
                            <option value="<?php echo $row['supplier_id']; ?>" <?php echo $product['supplier_id'] == $row['supplier_id'] ? 'selected' : ''; ?>>
                                <?php echo $row['supplier_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>