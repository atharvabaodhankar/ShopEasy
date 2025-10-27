<?php
session_start();
include '../config/database.php';
include '../includes/functions.php';

redirectIfNotAdmin();

// Get customer data from orders (since we don't have a separate customers table)
try {
    $customers = $conn->query("
        SELECT 
            customer_name,
            customer_email,
            customer_phone,
            COUNT(*) as total_orders,
            SUM(total_amount) as total_spent,
            MAX(created_at) as last_order
        FROM orders 
        GROUP BY customer_email 
        ORDER BY total_spent DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $customers = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="admin-content">
        <div class="container">
            <h1>Customers</h1>
            
            <?php if (empty($customers)): ?>
                <div style="text-align: center; padding: 3rem;">
                    <i class="fas fa-users" style="font-size: 4rem; color: #bdc3c7; margin-bottom: 1rem;"></i>
                    <h2>No customers found</h2>
                    <p>Customer data will appear here after orders are placed.</p>
                </div>
            <?php else: ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Total Orders</th>
                            <th>Total Spent</th>
                            <th>Last Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['customer_email']); ?></td>
                            <td><?php echo htmlspecialchars($customer['customer_phone'] ?? 'N/A'); ?></td>
                            <td><?php echo $customer['total_orders']; ?></td>
                            <td>$<?php echo number_format($customer['total_spent'], 2); ?></td>
                            <td><?php echo date('M j, Y', strtotime($customer['last_order'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>