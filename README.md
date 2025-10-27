# ShopEasy - PHP E-commerce Platform

A complete PHP-based e-commerce platform with admin panel for managing products, orders, and customers.

## Features

### Frontend (Customer Side)
- Modern responsive design
- Product catalog with search and filtering
- Shopping cart functionality
- Product detail pages
- Checkout process
- Mobile-friendly interface

### Admin Panel
- Secure admin login
- Dashboard with statistics
- Product management (Add, Edit, Delete)
- Order management
- Customer management
- Inventory tracking

## Installation

1. **Database Setup**
   ```sql
   -- Import the database.sql file into your MySQL database
   mysql -u username -p database_name < database.sql
   ```

2. **Configuration**
   - Update database credentials in `config/database.php`
   - Ensure your web server has PHP 7.4+ and MySQL 5.7+

3. **File Permissions**
   - Make sure the `assets/images/` directory is writable for file uploads

## Default Admin Credentials
- Username: `admin`
- Password: `admin123`

## Project Structure
```
├── admin/                  # Admin panel files
│   ├── dashboard.php      # Admin dashboard
│   ├── products.php       # Product management
│   ├── add_product.php    # Add new products
│   ├── login.php          # Admin login
│   └── includes/          # Admin includes
├── assets/                # Static assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Product images
├── config/               # Configuration files
├── includes/             # PHP includes and functions
├── index.php             # Homepage
├── products.php          # Product listing
├── cart.php              # Shopping cart
└── database.sql          # Database schema
```

## Technologies Used
- **Backend**: PHP 7.4+, MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Icons**: Font Awesome
- **Database**: MySQL with PDO

## Sample Products
The database includes sample products:
- Wireless Headphones ($99.99)
- Smartphone ($699.99)
- Laptop ($1299.99)
- Smart Watch ($249.99)
- Bluetooth Speaker ($79.99)
- Gaming Mouse ($59.99)

## Security Features
- SQL injection protection using PDO prepared statements
- Session-based admin authentication
- Input validation and sanitization
- CSRF protection ready (can be enhanced)

## Customization
- Modify `assets/css/style.css` for styling changes
- Update `config/database.php` for database settings
- Add new product categories in the database
- Extend admin functionality as needed

## Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- PDO MySQL extension

## Getting Started
1. Set up a local web server (XAMPP, WAMP, or similar)
2. Import the database schema
3. Configure database connection
4. Access the site at your local server URL
5. Login to admin panel with default credentials

## License
This is a demo project for educational purposes.