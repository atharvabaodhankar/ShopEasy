<?php
session_start();

$success = '';
$error = '';

if ($_POST) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if ($name && $email && $subject && $message) {
        // In a real application, you would send an email or save to database
        $success = 'Thank you for your message! We\'ll get back to you soon.';
    } else {
        $error = 'Please fill in all fields.';
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
    
    <div class="container" style="padding: 3rem 0;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <h1>Contact Us</h1>
            <p style="font-size: 1.2rem; color: #666; margin: 2rem 0;">
                We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; margin: 3rem 0;">
            <!-- Contact Form -->
            <div>
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
                        <label for="name">Your Name *</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address *</label>
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
            
            <!-- Contact Information -->
            <div>
                <h2>Get in Touch</h2>
                
                <div style="background: #f8f9fa; padding: 2rem; border-radius: 10px;">
                    <div style="margin-bottom: 2rem;">
                        <h4><i class="fas fa-map-marker-alt" style="color: #e74c3c; margin-right: 0.5rem;"></i> Address</h4>
                        <p style="margin-left: 1.5rem; color: #666;">
                            123 Shopping Street<br>
                            Commerce City, CC 12345<br>
                            United States
                        </p>
                    </div>
                    
                    <div style="margin-bottom: 2rem;">
                        <h4><i class="fas fa-phone" style="color: #27ae60; margin-right: 0.5rem;"></i> Phone</h4>
                        <p style="margin-left: 1.5rem; color: #666;">
                            <a href="tel:+1234567890" style="color: #666; text-decoration: none;">+1 (234) 567-8900</a>
                        </p>
                    </div>
                    
                    <div style="margin-bottom: 2rem;">
                        <h4><i class="fas fa-envelope" style="color: #3498db; margin-right: 0.5rem;"></i> Email</h4>
                        <p style="margin-left: 1.5rem; color: #666;">
                            <a href="mailto:support@shopeasy.com" style="color: #666; text-decoration: none;">support@shopeasy.com</a>
                        </p>
                    </div>
                    
                    <div>
                        <h4><i class="fas fa-clock" style="color: #f39c12; margin-right: 0.5rem;"></i> Business Hours</h4>
                        <p style="margin-left: 1.5rem; color: #666;">
                            Monday - Friday: 9:00 AM - 6:00 PM<br>
                            Saturday: 10:00 AM - 4:00 PM<br>
                            Sunday: Closed
                        </p>
                    </div>
                </div>
                
                <div style="margin-top: 2rem;">
                    <h3>Follow Us</h3>
                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                        <a href="#" style="color: #3b5998; font-size: 1.5rem;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="color: #1da1f2; font-size: 1.5rem;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: #e4405f; font-size: 1.5rem;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: #0077b5; font-size: 1.5rem;"><i class="fab fa-linkedin"></i></a>
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
                gap: 2rem !important;
            }
        }
    </style>
</body>
</html>