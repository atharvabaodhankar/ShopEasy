<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    header('Location: products.php');
    exit();
}

$product = getProductById($conn, $product_id);

if (!$product) {
    header('Location: products.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
            <!-- Product Image -->
            <div>
                <img src="<?php echo getProductImageUrl($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     style="width: 100%; max-width: 500px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            </div>
            
            <!-- Product Details -->
            <div>
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="price" style="font-size: 2rem; margin: 1rem 0;">₹<?php echo number_format($product['price'], 2); ?></p>
                
                <div style="margin: 2rem 0;">
                    <h3>Description</h3>
                    <p style="line-height: 1.6; color: #666;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                
                <div style="margin: 2rem 0;">
                    <p><strong>Stock:</strong> <?php echo $product['stock_quantity']; ?> available</p>
                    <?php if ($product['featured']): ?>
                        <p><i class="fas fa-star" style="color: #f39c12;"></i> Featured Product</p>
                    <?php endif; ?>
                </div>
                
                <div style="margin: 2rem 0;">
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <a href="add_to_cart.php?id=<?php echo $product['id']; ?>" class="btn btn-primary" style="margin-right: 1rem;">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </a>
                    <?php else: ?>
                        <button class="btn" style="background: #95a5a6; color: white;" disabled>
                            <i class="fas fa-times"></i> Out of Stock
                        </button>
                    <?php endif; ?>
                    
                    <a href="products.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Related Products Section -->
        <div style="margin-top: 4rem;">
            <h2>You might also like</h2>
            <div class="products-grid">
                <?php
                // Get related products (excluding current product)
                $stmt = $conn->prepare("SELECT * FROM products WHERE id != ? ORDER BY RAND() LIMIT 3");
                $stmt->execute([$product_id]);
                $related_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($related_products as $related): ?>
                <div class="product-card">
                    <img src="<?php echo getProductImageUrl($related['image']); ?>" alt="<?php echo htmlspecialchars($related['name']); ?>">
                    <h3><?php echo htmlspecialchars($related['name']); ?></h3>
                    <p class="price">₹<?php echo number_format($related['price'], 2); ?></p>
                    <a href="product.php?id=<?php echo $related['id']; ?>" class="btn btn-secondary">View Details</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <style>
        @media (max-width: 768px) {
            .container > div:first-child {
                grid-template-columns: 1fr !important;
                gap: 2rem !important;
            }
        }
    </style>
</body>
</html>