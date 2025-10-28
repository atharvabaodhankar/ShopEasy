<?php
session_start();
include '../config/database.php';
include '../includes/functions.php';

redirectIfNotAdmin();

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    header('Location: orders.php');
    exit();
}

// Get order details
try {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        header('Location: orders.php');
        exit();
    }
    
    // Get order items
    $stmt = $conn->prepare("
        SELECT oi.*, p.name as product_name, p.image as product_image 
        FROM order_items oi 
        LEFT JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $error = 'Error loading order: ' . $e->getMessage();
    $order = null;
    $order_items = [];
}

// Handle status update
if ($_POST && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    
    // Refresh order data
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $success = 'Order status updated successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details #<?php echo $order_id; ?> - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-content">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1>Order Details #<?php echo $order_id; ?></h1>
                <a href="orders.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>
            
            <?php if (isset($success)): ?>
                <div style="background: #27ae60; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div style="background: #e74c3c; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($order): ?>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                    <!-- Order Items -->
                    <div>
                        <h2>Order Items</h2>
                        <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <?php if (empty($order_items)): ?>
                                <div style="padding: 2rem; text-align: center; color: #666;">
                                    No items found for this order.
                                </div>
                            <?php else: ?>
                                <?php foreach ($order_items as $item): ?>
                                <div style="display: flex; align-items: center; padding: 1rem; border-bottom: 1px solid #eee;">
                                    <img src="<?php echo $item['product_image'] ?: '../assets/images/placeholder.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($item['product_name'] ?: 'Product'); ?>" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px; margin-right: 1rem;">
                                    
                                    <div style="flex: 1;">
                                        <h4><?php echo htmlspecialchars($item['product_name'] ?: 'Product #' . $item['product_id']); ?></h4>
                                        <p style="color: #666; margin: 0;">
                                            Quantity: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?>
                                        </p>
                                    </div>
                                    
                                    <div style="text-align: right;">
                                        <strong>₹<?php echo number_format($item['quantity'] * $item['price'], 2); ?></strong>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <div style="padding: 1rem; background: #f8f9fa; text-align: right;">
                                    <h3>Total: ₹<?php echo number_format($order['total_amount'], 2); ?></h3>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Order Information -->
                    <div>
                        <h2>Order Information</h2>
                        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <div style="margin-bottom: 1rem;">
                                <strong>Order ID:</strong><br>
                                #<?php echo $order['id']; ?>
                            </div>
                            
                            <div style="margin-bottom: 1rem;">
                                <strong>Date:</strong><br>
                                <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?>
                            </div>
                            
                            <div style="margin-bottom: 1rem;">
                                <strong>Status:</strong><br>
                                <form method="POST" style="margin-top: 0.5rem;">
                                    <select name="status" onchange="this.form.submit()" style="padding: 0.5rem; border-radius: 5px; border: 1px solid #ddd; width: 100%;">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </div>
                            
                            <div style="margin-bottom: 1rem;">
                                <strong>Total Amount:</strong><br>
                                ₹<?php echo number_format($order['total_amount'], 2); ?>
                            </div>
                        </div>
                        
                        <h2 style="margin-top: 2rem;">Customer Information</h2>
                        <div style="background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <div style="margin-bottom: 1rem;">
                                <strong>Name:</strong><br>
                                <?php echo htmlspecialchars($order['customer_name']); ?>
                            </div>
                            
                            <div style="margin-bottom: 1rem;">
                                <strong>Email:</strong><br>
                                <a href="mailto:<?php echo htmlspecialchars($order['customer_email']); ?>">
                                    <?php echo htmlspecialchars($order['customer_email']); ?>
                                </a>
                            </div>
                            
                            <?php if ($order['customer_phone']): ?>
                            <div style="margin-bottom: 1rem;">
                                <strong>Phone:</strong><br>
                                <a href="tel:<?php echo htmlspecialchars($order['customer_phone']); ?>">
                                    <?php echo htmlspecialchars($order['customer_phone']); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <div style="margin-bottom: 1rem;">
                                <strong>Shipping Address:</strong><br>
                                <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <style>
        @media (max-width: 768px) {
            .admin-content .container > div:nth-child(4) {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
        }
    </style>
</body>
</html>