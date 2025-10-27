<?php
session_start();
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
    
    <div class="container" style="padding: 3rem 0;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <h1>About ShopEasy</h1>
            <p style="font-size: 1.2rem; color: #666; margin: 2rem 0;">
                Your trusted online shopping destination for quality products at unbeatable prices.
            </p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem; margin: 4rem 0;">
            <div style="text-align: center;">
                <i class="fas fa-shipping-fast" style="font-size: 3rem; color: #3498db; margin-bottom: 1rem;"></i>
                <h3>Fast Shipping</h3>
                <p>Quick and reliable delivery to your doorstep with tracking information provided.</p>
            </div>
            
            <div style="text-align: center;">
                <i class="fas fa-shield-alt" style="font-size: 3rem; color: #27ae60; margin-bottom: 1rem;"></i>
                <h3>Secure Shopping</h3>
                <p>Your personal and payment information is protected with industry-standard security.</p>
            </div>
            
            <div style="text-align: center;">
                <i class="fas fa-headset" style="font-size: 3rem; color: #e74c3c; margin-bottom: 1rem;"></i>
                <h3>24/7 Support</h3>
                <p>Our customer service team is available around the clock to assist you.</p>
            </div>
        </div>
        
        <div style="background: #f8f9fa; padding: 3rem; border-radius: 10px; margin: 3rem 0;">
            <h2>Our Story</h2>
            <p style="line-height: 1.8; margin-bottom: 1.5rem;">
                Founded in 2024, ShopEasy began with a simple mission: to make online shopping easy, 
                affordable, and enjoyable for everyone. We believe that great products shouldn't come 
                with complicated processes or hidden fees.
            </p>
            <p style="line-height: 1.8; margin-bottom: 1.5rem;">
                Today, we're proud to serve thousands of customers worldwide, offering a carefully 
                curated selection of products from trusted brands and emerging innovators. Our team 
                works tirelessly to ensure every purchase exceeds your expectations.
            </p>
            <p style="line-height: 1.8;">
                Thank you for choosing ShopEasy. We're committed to making your shopping experience 
                as smooth and satisfying as possible.
            </p>
        </div>
        
        <div style="text-align: center; margin: 3rem 0;">
            <h2>Ready to Shop?</h2>
            <p style="margin: 1rem 0;">Discover our amazing collection of products.</p>
            <a href="products.php" class="btn btn-primary">Browse Products</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>