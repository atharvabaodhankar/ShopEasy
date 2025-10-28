<?php
function getFeaturedProducts($conn) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE featured = 1 LIMIT 6");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProducts($conn) {
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

function getCartItems($conn) {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return [];
    }
    
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($product_ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($products as &$product) {
        $product['cart_quantity'] = $_SESSION['cart'][$product['id']];
        $product['total_price'] = $product['price'] * $product['cart_quantity'];
    }
    
    return $products;
}

function getCartTotal($conn) {
    $items = getCartItems($conn);
    $total = 0;
    foreach ($items as $item) {
        $total += $item['total_price'];
    }
    return $total;
}

function isAdmin() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header('Location: login.php');
        exit();
    }
}

function uploadProductImage($file) {
    $upload_dir = '../uploads/';
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    // Check if file was uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'No file uploaded or upload error occurred.'];
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'File size too large. Maximum 5MB allowed.'];
    }
    
    // Check file type
    if (!in_array($file['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.'];
    }
    
    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = 'product_' . uniqid() . '_' . time() . '.' . $file_extension;
    $upload_path = $upload_dir . $new_filename;
    
    // Create upload directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        return ['success' => true, 'filename' => $new_filename];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file.'];
    }
}

function deleteProductImage($filename) {
    if (empty($filename) || $filename === 'placeholder.jpg') {
        return true; // Don't delete placeholder or empty filenames
    }
    
    $file_path = '../uploads/' . $filename;
    if (file_exists($file_path)) {
        return unlink($file_path);
    }
    return true; // File doesn't exist, consider it deleted
}

function getProductImageUrl($filename) {
    if (empty($filename) || $filename === 'placeholder.jpg') {
        return 'assets/images/placeholder.jpg';
    }
    
    // Check if it's an old URL-based image (for backward compatibility)
    if (strpos($filename, 'http') === 0 || strpos($filename, 'assets/') === 0) {
        return $filename;
    }
    
    // Return path to uploaded image
    return 'uploads/' . $filename;
}
?>