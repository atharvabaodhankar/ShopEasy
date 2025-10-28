<?php
session_start();
include '../config/database.php';
include '../includes/functions.php';

redirectIfNotAdmin();

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get product image before deletion to remove file
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        // Delete the product image file
        deleteProductImage($product['image']);
        
        // Delete the product from database
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }
    
    header('Location: products.php');
    exit();
}

// Get all products
try {
    $products = $conn->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-content">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1>Manage Products</h1>
                <a href="add_product.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>
            
            <?php if (empty($products)): ?>
                <div style="text-align: center; padding: 3rem;">
                    <i class="fas fa-box-open" style="font-size: 4rem; color: #bdc3c7; margin-bottom: 1rem;"></i>
                    <h2>No products found</h2>
                    <p>Start by adding your first product!</p>
                    <a href="add_product.php" class="btn btn-primary">Add Product</a>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <?php 
                                $image_src = ($product['image'] === 'placeholder.jpg') 
                                    ? '../assets/images/placeholder.jpg' 
                                    : '../uploads/' . $product['image'];
                                ?>
                                <img src="<?php echo $image_src; ?>" alt="<?php echo $product['name']; ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>₹<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['stock_quantity']; ?></td>
                            <td>
                                <?php if ($product['featured']): ?>
                                    <span style="color: #27ae60;"><i class="fas fa-star"></i> Yes</span>
                                <?php else: ?>
                                    <span style="color: #95a5a6;">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary" style="padding: 0.3rem 0.8rem; margin-right: 0.5rem;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="products.php?delete=<?php echo $product['id']; ?>" 
                                   class="btn" style="background: #e74c3c; color: white; padding: 0.3rem 0.8rem;"
                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>