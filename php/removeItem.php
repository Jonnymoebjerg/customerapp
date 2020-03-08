<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$cartItemIdsPost = $_POST['cartItemIds'];
$cartItemQuanPost = $_POST['cartItemQuan'];
$productId = $_POST['productId'];

$cartItemIdsArray = explode(',', $cartItemIdsPost);
$cartItemIdsQuan = explode(',', $cartItemQuanPost);

$arrayNumber = array_search($productId,$cartItemIdsArray);

unset($cartItemIdsQuan[$arrayNumber]);

$cartItemQuanUnArray = implode (",", $cartItemIdsQuan);

echo $cartItemQuanUnArray;