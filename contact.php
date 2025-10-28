<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';

$success = '';
$error = '';

if ($_POST) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if ($name && $email && $subject && $message) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // In a real application, you would send an email or save to database
            $success = 'Thank you for your message! We\'ll get back to you soon.';
        } else {
            $error = 'Please enter a valid email address.';
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
    <title>Contact Us - ShopEasy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <h1>Contact Us</h1>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin: 2rem 0;">
            <!-- Contact Form -->
            <div>
                <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h2>Send us a Message</h2>
                    
                    <?php if ($success): ?>
                        <div style="background: #27ae60; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div style="background: #e74c3c; color: white; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="name">Name *</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div>
                <div style="background: white; padding: 2rem; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h2>Get in Touch</h2>
                    <p style="color: #666; margin-bottom: 2rem;">
                        We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                    </p>
                    
                    <div style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <i class="fas fa-map-marker-alt" style="color: #e74c3c; margin-right: 1rem; width: 20px;"></i>
                            <div>
                                <strong>Address</strong><br>
                                <span style="color: #666;">123 Shopping Street, Commerce City, CC 12345</span>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <i class="fas fa-phone" style="color: #27ae60; margin-right: 1rem; width: 20px;"></i>
                            <div>
                                <strong>Phone</strong><br>
                                <span style="color: #666;">+91 98765 43210</span>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <i class="fas fa-envelope" style="color: #3498db; margin-right: 1rem; width: 20px;"></i>
                            <div>
                                <strong>Email</strong><br>
                                <span style="color: #666;">support@shopeasy.com</span>
                            </div>
                        </div>
                        
                        <div style="display: flex; align-items: center;">
                            <i class="fas fa-clock" style="color: #f39c12; margin-right: 1rem; width: 20px;"></i>
                            <div>
                                <strong>Business Hours</strong><br>
                                <span style="color: #666;">Mon - Fri: 9:00 AM - 6:00 PM</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3>Follow Us</h3>
                        <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                            <a href="#" style="color: #3498db; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                            <a href="#" style="color: #1da1f2; font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                            <a href="#" style="color: #e4405f; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                            <a href="#" style="color: #0077b5; font-size: 1.5rem;"><i class="fab fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <style>
        @media (max-width: 768px) {
            .container > div:nth-child(2) {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</body>
</html>