<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';

header('Content-Type: application/json');

if ($_POST && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
    
    $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
    $cart_total = getCartTotal($conn);
    
    echo json_encode([
        'success' => true,
        'cart_count' => $cart_count,
        'cart_total' => number_format($cart_total, 2)
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>