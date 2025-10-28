<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';

$cart_items = getCartItems($conn);
$cart_total = getCartTotal($conn);

if (empty($cart_items)) {
    header('Location: cart.php');
    exit();
}

$success = '';
$error = '';

if ($_POST) {
    $customer_name = $_POST['customer_name'] ?? '';
    $customer_email = $_POST['customer_email'] ?? '';
    $customer_phone = $_POST['customer_phone'] ?? '';
    $shipping_address = $_POST['shipping_address'] ?? '';
    
    if ($customer_name && $customer_email && $shipping_address) {
        try {
            $conn->beginTransaction();
            
            // Create order
            $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, shipping_address, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
            $stmt->execute([$customer_name, $customer_email, $customer_phone, $shipping_address, $cart_total]);
            $order_id = $conn->lastInsertId();
            
            // Add order items
            foreach ($cart_items as $item) {
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$order_id, $item['id'], $item['cart_quantity'], $item['price']]);
                
                // Update product stock
                $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $stmt->execute([$item['cart_quantity'], $item['id']]);
            }
            
            $conn->commit();
            
            // Clear cart
            unset($_SESSION['cart']);
            
            $success = "Order placed successfully! Order ID: #$order_id";
        } catch (Exception $e) {
            $conn->rollBack();
            $error = 'Error placing order: ' . $e->getMessage();
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
    <title>Checkout - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <h1>Checkout</h1>
        
        <?php if ($success): ?>
            <div style="background: #27ae60; color: white; padding: 2rem; border-radius: 10px; text-align: center; margin-bottom: 2rem;">
                <i class="fas fa-check-circle" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <h2><?php echo $success; ?></h2>
                <p>Thank you for your order! We'll process it shortly.</p>
                <a href="products.php" class="btn" style="background: white; color: #27ae60; margin-top: 1rem;">Continue Shopping</a>
            </div>
        <?php else: ?>
            <?php if ($error): ?>
                <div style="background: #e74c3c; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
                <!-- Order Summary -->
                <div>
                    <h2>Order Summary</h2>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px;">
                        <?php foreach ($cart_items as $item): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                            <div>
                                <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                <small>Qty: <?php echo $item['cart_quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?></small>
                            </div>
                            <div>
                                <strong>₹<?php echo number_format($item['total_price'], 2); ?></strong>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; font-size: 1.2rem; font-weight: bold;">
                            <div>Total:</div>
                            <div>₹<?php echo number_format($cart_total, 2); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Checkout Form -->
                <div>
                    <h2>Shipping Information</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label for="customer_name">Full Name *</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_email">Email Address *</label>
                            <input type="email" id="customer_email" name="customer_email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_phone">Phone Number</label>
                            <input type="tel" id="customer_phone" name="customer_phone" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_address">Shipping Address *</label>
                            <textarea id="shipping_address" name="shipping_address" class="form-control" rows="4" required></textarea>
                        </div>
                        
                        <div style="margin-top: 2rem;">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-credit-card"></i> Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <style>
        @media (max-width: 768px) {
            .container > div:nth-child(3) {
                grid-template-columns: 1fr !important;
                gap: 2rem !important;
            }
        }
    </style>
</body>
</html>