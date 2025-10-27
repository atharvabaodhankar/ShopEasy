<?php
session_start();
include 'includes/functions.php';

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    addToCart($product_id);
    
    header('Location: cart.php');
    exit();
} else {
    header('Location: products.php');
    exit();
}
?>