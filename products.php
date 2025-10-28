<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';

$products = getAllProducts($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <h1>Our Products</h1>
        
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?php echo getProductImageUrl($product['image']); ?>" alt="<?php echo $product['name']; ?>">
                <h3><?php echo $product['name']; ?></h3>
                <p><?php echo substr($product['description'], 0, 100) . '...'; ?></p>
                <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary">View Details</a>
                    <a href="add_to_cart.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Add to Cart</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>