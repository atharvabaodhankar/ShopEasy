<?php
session_start();
include '../config/database.php';
include '../includes/functions.php';

redirectIfNotAdmin();

$success = '';
$error = '';
$product = null;

// Get product ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: products.php');
    exit();
}

// Get product data
try {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header('Location: products.php');
        exit();
    }
} catch (Exception $e) {
    $error = 'Error loading product: ' . $e->getMessage();
}

// Handle form submission
if ($_POST && $product) {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $stock_quantity = $_POST['stock_quantity'] ?? 0;
    $featured = isset($_POST['featured']) ? 1 : 0;
    $image = $product['image']; // Keep current image by default
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_result = uploadProductImage($_FILES['image']);
        if ($upload_result['success']) {
            // Delete old image if it's not the placeholder
            if ($product['image'] !== 'placeholder.jpg') {
                deleteProductImage($product['image']);
            }
            $image = $upload_result['filename'];
        } else {
            $error = $upload_result['message'];
        }
    }
    
    if ($name && $description && $price > 0 && !$error) {
        try {
            $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, featured = ?, image = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$name, $description, $price, $stock_quantity, $featured, $image, $id]);
            $success = 'Product updated successfully!';
            
            // Refresh product data
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $error = 'Error updating product: ' . $e->getMessage();
        }
    } else if (!$error) {
        $error = 'Please fill in all required fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-content">
        <div class="container">
            <h1>Edit Product</h1>
            
            <?php if ($success): ?>
                <div style="background: #27ae60; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div style="background: #e74c3c; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($product): ?>
            <form method="POST" enctype="multipart/form-data" style="max-width: 600px;">
                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">Price *</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="stock_quantity">Stock Quantity</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" min="0" value="<?php echo $product['stock_quantity']; ?>">
                </div>
                
                <div class="form-group">
                    <label>Current Image</label>
                    <div style="margin-bottom: 1rem;">
                        <?php 
                        $image_src = ($product['image'] === 'placeholder.jpg') 
                            ? '../assets/images/placeholder.jpg' 
                            : '../uploads/' . $product['image'];
                        ?>
                        <img src="<?php echo $image_src; ?>" alt="Current product image" style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <label for="image">Upload New Image (optional)</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <small style="color: #666; font-size: 0.9em;">Supported formats: JPG, PNG, GIF, WebP. Maximum size: 5MB. Leave empty to keep current image.</small>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="featured" <?php echo $product['featured'] ? 'checked' : ''; ?>> Featured Product
                    </label>
                </div>
                
                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="products.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>