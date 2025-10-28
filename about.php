<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <h1>About ShopEasy</h1>
        
        <div style="max-width: 800px; margin: 2rem 0;">
            <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <h2>Welcome to ShopEasy</h2>
                <p style="line-height: 1.8; color: #666; margin-bottom: 2rem;">
                    ShopEasy is your trusted online shopping destination, offering a wide range of high-quality products 
                    at competitive prices. We are committed to providing an exceptional shopping experience with 
                    fast delivery, secure payments, and excellent customer service.
                </p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin: 2rem 0;">
                    <div style="text-align: center; padding: 1.5rem;">
                        <i class="fas fa-shipping-fast" style="font-size: 3rem; color: #3498db; margin-bottom: 1rem;"></i>
                        <h3>Fast Delivery</h3>
                        <p style="color: #666;">Quick and reliable delivery to your doorstep</p>
                    </div>
                    
                    <div style="text-align: center; padding: 1.5rem;">
                        <i class="fas fa-shield-alt" style="font-size: 3rem; color: #27ae60; margin-bottom: 1rem;"></i>
                        <h3>Secure Shopping</h3>
                        <p style="color: #666;">Your data and payments are always protected</p>
                    </div>
                    
                    <div style="text-align: center; padding: 1.5rem;">
                        <i class="fas fa-headset" style="font-size: 3rem; color: #e74c3c; margin-bottom: 1rem;"></i>
                        <h3>24/7 Support</h3>
                        <p style="color: #666;">We're here to help whenever you need us</p>
                    </div>
                </div>
                
                <h3>Our Mission</h3>
                <p style="line-height: 1.8; color: #666;">
                    To make online shopping simple, secure, and enjoyable for everyone. We believe in providing 
                    quality products, transparent pricing, and exceptional customer service that exceeds expectations.
                </p>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>