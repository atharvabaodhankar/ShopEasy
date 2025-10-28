<header class="main-header">
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php">
                    <i class="fas fa-shopping-bag"></i>
                    ShopEasy
                </a>
            </div>
            
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="my_orders.php">My Orders</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            
            <div class="nav-actions">
                <a href="cart.php" class="cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count"><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>
                </a>
                <a href="admin/login.php" class="admin-link">
                    <i class="fas fa-user-shield"></i>
                    Admin
                </a>
            </div>
        </div>
    </nav>
</header>