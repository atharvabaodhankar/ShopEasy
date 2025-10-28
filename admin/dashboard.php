<?php
session_start();
include '../config/database.php';
include '../includes/functions.php';

redirectIfNotAdmin();

// Get statistics
try {
    $total_products = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $total_orders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $total_revenue = $conn->query("SELECT SUM(total_amount) FROM orders")->fetchColumn() ?: 0;
    $recent_orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $total_products = 0;
    $total_orders = 0;
    $total_revenue = 0;
    $recent_orders = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ShopEasy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-content">
        <div class="container">
            <h1>Dashboard</h1>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-box" style="font-size: 2rem; color: #3498db; margin-bottom: 1rem;"></i>
                    <div class="stat-number"><?php echo $total_products; ?></div>
                    <div>Total Products</div>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-shopping-cart" style="font-size: 2rem; color: #e74c3c; margin-bottom: 1rem;"></i>
                    <div class="stat-number"><?php echo $total_orders; ?></div>
                    <div>Total Orders</div>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-rupee-sign" style="font-size: 2rem; color: #27ae60; margin-bottom: 1rem;"></i>
                    <div class="stat-number">₹<?php echo number_format($total_revenue, 2); ?></div>
                    <div>Total Revenue</div>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-users" style="font-size: 2rem; color: #f39c12; margin-bottom: 1rem;"></i>
                    <div class="stat-number">0</div>
                    <div>Total Users</div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div style="margin-top: 3rem;">
                <h2>Recent Orders</h2>
                <?php if (empty($recent_orders)): ?>
                    <p>No orders yet.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo $order['customer_name']; ?></td>
                                <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo ucfirst($order['status']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>