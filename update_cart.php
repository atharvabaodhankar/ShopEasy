<?php
session_start();
include 'config/database.php';
include 'includes/functions.php';

header('Content-Type: application/json');

if ($_POST && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    
    if ($product_id > 0 && $quantity > 0) {
        // Update quantity in session
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = $quantity;
            
            // Get updated cart information
            $cart_items = getCartItems($conn);
            $cart_total = getCartTotal($conn);
            
            // Find the updated item
            $updated_item = null;
            foreach ($cart_items as $item) {
                if ($item['id'] == $product_id) {
                    $updated_item = $item;
                    break;
                }
            }
            
            if ($updated_item) {
                echo json_encode([
                    'success' => true,
                    'item_total' => number_format($updated_item['total_price'], 2),
                    'cart_total' => number_format($cart_total, 2)
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Item not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Item not in cart']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
}
?>