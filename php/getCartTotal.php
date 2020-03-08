<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../php/globals/conn.php';
include '../php/classes/products.php';
 
$productHandler = new products($conn);

$cartItemIds = $_POST['cartItemIds'];
$cartItemQuantity = $_POST['cartItemQuan'];

$cartItemQuantityArray = explode(',', $cartItemQuantity);

$cartItems = preg_split( '/(,|:)/', $cartItemIds);
$maxI = count($cartItems) -1;
$i = 0;
$_SESSION['cartTotalPrice'] = "";
foreach ($cartItems as $cartItem) {
    if ($i === $maxI) {
        echo $_SESSION['cartTotalPrice'];
    } else {
        $product = $productHandler->getProductFromId($cartItem);
        $_SESSION['cartTotalPrice'] = $_SESSION['cartTotalPrice'] + ($product->getPrice() * $cartItemQuantityArray[$i]);

        $i++;
    }
}