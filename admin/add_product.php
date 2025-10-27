<?php
session_start();
include '../config/database.php';
include '../includes/functions.php';

redirectIfNotAdmin();

$success = '';
$error = '';

if ($_POST) {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock_quantity = $_POST['stock_quantity'] ?? 0;
    $featured = isset($_POST['featured']) ? 1 : 0;
    $image = $_POST['image'] ?? 'assets/images/placeholder.jpg';
    
    if ($name && $description && $price > 0) {
        try {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock_quantity, featured, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $description, $price, $stock_quantity, $featured, $image]);
            $success = 'Product added successfully!';
        } catch (Exception $e) {
            $error = 'Error adding product: ' . $e->getMessage();
        }
    } else {
        $error = 'Please fill in all required fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-content">
        <div class="container">
            <h1>Add New Product</h1>
            
            <?php if ($success): ?>
                <div style="background: #27ae60; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                    <?php echo $success; ?>
                    <a href="products.php" style="color: white; margin-left: 1rem;">← Back to Products</a>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div style="background: #e74c3c; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" style="max-width: 600px;">
                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">Price *</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="stock_quantity">Stock Quantity</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" min="0" value="0">
                </div>
                
                <div class="form-group">
                    <label for="image">Image URL</label>
                    <input type="text" id="image" name="image" class="form-control" placeholder="assets/images/product.jpg">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="featured"> Featured Product
                    </label>
                </div>
                
                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                    <a href="products.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>