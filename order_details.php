<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';

$order = null;
$order_items = [];
$error = '';

// Get order ID and email from URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$customer_email = isset($_GET['email']) ? trim($_GET['email']) : '';

if ($order_id <= 0 || empty($customer_email)) {
    header('Location: my_orders.php');
    exit();
}

try {
    // Get order details - verify email matches for security
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND customer_email = ?");
    $stmt->execute([$order_id, $customer_email]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        $error = 'Order not found or access denied.';
    } else {
        // Get order items with product details
        $stmt = $conn->prepare("
            SELECT oi.*, p.name as product_name, p.image as product_image 
            FROM order_items oi 
            LEFT JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order_id]);
        $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    $error = 'Error loading order details: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo $order_id; ?> Details - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <div style="margin-bottom: 2rem;">
            <a href="my_orders.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to My Orders
            </a>
        </div>
        
        <?php if ($error): ?>
            <div style="background: #e74c3c; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
                <?php echo $error; ?>
            </div>
        <?php elseif ($order): ?>
            <div style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden;">
                <!-- Order Header -->
                <div style="background: #f8f9fa; padding: 2rem; border-bottom: 1px solid #eee;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h1 style="margin: 0;">Order #<?php echo $order['id']; ?></h1>
                            <p style="margin: 0.5rem 0; color: #666;">
                                Placed on <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?>
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <?php
                            $status_colors = [
                                'pending' => '#f39c12',
                                'processing' => '#3498db',
                                'shipped' => '#9b59b6',
                                'delivered' => '#27ae60',
                                'cancelled' => '#e74c3c'
                            ];
                            $status_color = $status_colors[$order['status']] ?? '#95a5a6';
                            ?>
                            <span style="background: <?php echo $status_color; ?>; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 1rem; font-weight: bold;">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div style="padding: 2rem;">
                    <h3 style="margin-bottom: 1.5rem;">Order Items</h3>
                    
                    <?php if (!empty($order_items)): ?>
                        <div style="border: 1px solid #eee; border-radius: 5px; overflow: hidden;">
                            <?php foreach ($order_items as $index => $item): ?>
                                <div style="display: flex; align-items: center; padding: 1rem; <?php echo $index > 0 ? 'border-top: 1px solid #eee;' : ''; ?>">
                                    <div style="margin-right: 1rem;">
                                        <?php if ($item['product_image']): ?>
                                            <img src="<?php echo getProductImageUrl($item['product_image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['product_name'] ?: 'Product'); ?>" 
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                        <?php else: ?>
                                            <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image" style="color: #ccc;"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div style="flex: 1;">
                                        <h4 style="margin: 0;"><?php echo htmlspecialchars($item['product_name'] ?: 'Product #' . $item['product_id']); ?></h4>
                                        <p style="color: #666; margin: 0.5rem 0;">
                                            Quantity: <?php echo $item['quantity']; ?> × ₹<?php echo number_format($item['price'], 2); ?>
                                        </p>
                                    </div>
                                    
                                    <div style="text-align: right;">
                                        <strong style="font-size: 1.1rem;">₹<?php echo number_format($item['quantity'] * $item['price'], 2); ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <!-- Order Total -->
                            <div style="padding: 1rem; background: #f8f9fa; text-align: right; border-top: 2px solid #e74c3c;">
                                <h3 style="margin: 0; color: #e74c3c;">Total: ₹<?php echo number_format($order['total_amount'], 2); ?></h3>
                            </div>
                        </div>
                    <?php else: ?>
                        <p style="color: #666;">No items found for this order.</p>
                    <?php endif; ?>
                </div>
                
                <!-- Customer & Shipping Info -->
                <div style="padding: 2rem; background: #f8f9fa; border-top: 1px solid #eee;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                        <div>
                            <h4 style="margin-bottom: 1rem;">Customer Information</h4>
                            <div style="background: white; padding: 1rem; border-radius: 5px;">
                                <p style="margin: 0.5rem 0;"><strong>Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
                                <p style="margin: 0.5rem 0;"><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                                <?php if ($order['customer_phone']): ?>
                                    <p style="margin: 0.5rem 0;"><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div>
                            <h4 style="margin-bottom: 1rem;">Shipping Address</h4>
                            <div style="background: white; padding: 1rem; border-radius: 5px;">
                                <p style="margin: 0; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Status Timeline -->
                <div style="padding: 2rem; border-top: 1px solid #eee;">
                    <h4 style="margin-bottom: 1.5rem;">Order Status</h4>
                    <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                        <?php
                        $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                        $current_status_index = array_search($order['status'], $statuses);
                        
                        foreach ($statuses as $index => $status):
                            $is_current = ($status === $order['status']);
                            $is_completed = ($index <= $current_status_index && $order['status'] !== 'cancelled');
                            $color = $is_completed ? '#27ae60' : '#bdc3c7';
                        ?>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 20px; height: 20px; border-radius: 50%; background: <?php echo $color; ?>; display: flex; align-items: center; justify-content: center;">
                                    <?php if ($is_completed): ?>
                                        <i class="fas fa-check" style="color: white; font-size: 0.8rem;"></i>
                                    <?php endif; ?>
                                </div>
                                <span style="color: <?php echo $color; ?>; font-weight: <?php echo $is_current ? 'bold' : 'normal'; ?>;">
                                    <?php echo ucfirst($status); ?>
                                </span>
                                <?php if ($index < count($statuses) - 1): ?>
                                    <div style="width: 30px; height: 2px; background: <?php echo $is_completed && $index < $current_status_index ? '#27ae60' : '#bdc3c7'; ?>; margin: 0 0.5rem;"></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if ($order['status'] === 'cancelled'): ?>
                            <div style="margin-left: 1rem;">
                                <span style="background: #e74c3c; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.9rem;">
                                    Cancelled
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <style>
        @media (max-width: 768px) {
            .container > div:nth-child(2) > div:nth-child(4) > div > div {
                grid-template-columns: 1fr !important;
            }
            
            .container > div:nth-child(2) > div:nth-child(1) > div {
                flex-direction: column !important;
                text-align: center !important;
            }
        }
    </style>
</body>
</html>