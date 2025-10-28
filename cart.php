<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';

$cart_items = getCartItems($conn);
$cart_total = getCartTotal($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <h1>Shopping Cart</h1>
        
        <?php if (empty($cart_items)): ?>
            <div style="text-align: center; padding: 3rem;">
                <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #bdc3c7; margin-bottom: 1rem;"></i>
                <h2>Your cart is empty</h2>
                <p>Add some products to get started!</p>
                <a href="products.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="<?php echo getProductImageUrl($item['image']); ?>" alt="<?php echo $item['name']; ?>">
                    <div class="cart-item-info">
                        <h3><?php echo $item['name']; ?></h3>
                        <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                    <div class="cart-item-actions">
                        <input type="number" class="quantity-input form-control" value="<?php echo $item['cart_quantity']; ?>" min="1">
                        <p><strong>$<?php echo number_format($item['total_price'], 2); ?></strong></p>
                        <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>" class="btn" style="background: #e74c3c; color: white;">Remove</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="text-align: right; margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 5px;">
                <h2>Total: $<?php echo number_format($cart_total, 2); ?></h2>
                <div style="margin-top: 1rem;">
                    <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
                    <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>