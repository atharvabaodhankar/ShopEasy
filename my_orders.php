<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';

$orders = [];
$customer_email = '';
$error = '';

if ($_POST && isset($_POST['customer_email'])) {
    $customer_email = trim($_POST['customer_email']);
    
    if (filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_email = ? ORDER BY created_at DESC");
            $stmt->execute([$customer_email]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($orders)) {
                $error = 'No orders found for this email address.';
            }
        } catch (Exception $e) {
            $error = 'Error retrieving orders: ' . $e->getMessage();
        }
    } else {
        $error = 'Please enter a valid email address.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <h1><i class="fas fa-shopping-bag"></i> My Orders</h1>
        
        <!-- Email Input Form -->
        <div style="max-width: 500px; margin: 2rem 0;">
            <form method="POST" style="background: #f8f9fa; padding: 2rem; border-radius: 10px;">
                <h3>Track Your Orders</h3>
                <p style="color: #666; margin-bottom: 1.5rem;">Enter your email address to view your order history</p>
                
                <?php if ($error): ?>
                    <div style="background: #e74c3c; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="customer_email">Email Address</label>
                    <input type="email" id="customer_email" name="customer_email" class="form-control" 
                           value="<?php echo htmlspecialchars($customer_email); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> View My Orders
                </button>
            </form>
        </div>
        
        <!-- Orders Display -->
        <?php if (!empty($orders)): ?>
            <div style="margin-top: 3rem;">
                <h2>Your Orders (<?php echo count($orders); ?>)</h2>
                
                <div style="display: grid; gap: 1.5rem; margin-top: 2rem;">
                    <?php foreach ($orders as $order): ?>
                        <div style="background: white; border: 1px solid #ddd; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                <div>
                                    <h3 style="margin: 0; color: #333;">Order #<?php echo $order['id']; ?></h3>
                                    <p style="margin: 0.5rem 0; color: #666;">
                                        Placed on <?php echo date('F j, Y \a\t g:i A', strtotime($order['created_at'])); ?>
                                    </p>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 1.2rem; font-weight: bold; color: #e74c3c;">
                                        ₹<?php echo number_format($order['total_amount'], 2); ?>
                                    </div>
                                    <div style="margin-top: 0.5rem;">
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
                                        <span style="background: <?php echo $status_color; ?>; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.9rem;">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="border-top: 1px solid #eee; padding-top: 1rem;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <strong>Customer Details:</strong><br>
                                        <span style="color: #666;">
                                            <?php echo htmlspecialchars($order['customer_name']); ?><br>
                                            <?php echo htmlspecialchars($order['customer_email']); ?><br>
                                            <?php if ($order['customer_phone']): ?>
                                                <?php echo htmlspecialchars($order['customer_phone']); ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div>
                                        <strong>Shipping Address:</strong><br>
                                        <span style="color: #666;">
                                            <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="margin-top: 1rem; text-align: right;">
                                <a href="order_details.php?id=<?php echo $order['id']; ?>&email=<?php echo urlencode($order['customer_email']); ?>" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <style>
        @media (max-width: 768px) {
            .container > div:nth-child(3) > div > div {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</body>
</html>