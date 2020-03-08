<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../php/globals/conn.php';
include '../php/classes/orders.php';
include '../php/classes/products.php';

$cartItemIdsPost = $_POST['currentCart'];
$cartItemQuanPost = $_POST['currentQuan'];

if ($cartItemIdsPost === "") {
    $productId = $_POST['orderId'];

    $orderHandler = new orders($conn);
    $order = $orderHandler->getOrderFromId($productId);

    $productHandler = new products($conn);

    $orderItems = preg_split( '/(,|:)/', $order->getItems());
    array_pop($orderItems);
    $i = 0;
    $orderItemsCode = array();
    $orderItemsQuantity = array();
    foreach ($orderItems as $orderItem) {
        if ($i % 2 == 0) {
            array_push($orderItemsCode,$productHandler->getProductFromCode($orderItem)->getId());
            $i++;
        } else {
            array_push($orderItemsQuantity,$orderItem);
            $i++;
        }
    }
    
    $cartItemIdsUnArray = implode (",", $orderItemsCode);

    $cartItemQuanUnArray = implode (",", $orderItemsQuantity);

    echo json_encode( array("data1" => $cartItemIdsUnArray, "data2" => $cartItemQuanUnArray));
} else {
    $cartItemIdsArray = explode(',', $cartItemIdsPost);
    $cartItemIdsQuan = explode(',', $cartItemQuanPost);
    
    $cartItemIdsArray = array();
    $cartItemIdsQuan = array();
    $productId = $_POST['orderId'];

    $orderHandler = new orders($conn);
    $order = $orderHandler->getOrderFromId($productId);

    $productHandler = new products($conn);

    $orderItems = preg_split( '/(,|:)/', $order->getItems());
    array_pop($orderItems);
    $i = 0;
    $orderItemsCode = array();
    $orderItemsQuantity = array();
    foreach ($orderItems as $orderItem) {
        if ($i % 2 == 0) {
            array_push($orderItemsCode,$productHandler->getProductFromCode($orderItem)->getId());
            $i++;
        } else {
            array_push($orderItemsQuantity,$orderItem);
            $i++;
        }
    }

    $arrayDiff = array_diff($orderItemsCode,$cartItemIdsArray);

    $arrayCountt = count($arrayDiff);
    $arrayCount = array();

    for ($i = 1; $i <= $arrayCountt; $i++) {
        array_push($arrayCount,"1");
    }

    $finalItemIds = array_merge($cartItemIdsArray,$arrayDiff);

    $finalitemQuan = array_merge($cartItemIdsQuan,$arrayCount);

    $cartItemIdsUnArray = implode ($finalItemIds, ",");

    $cartItemQuanUnArray = implode ($finalitemQuan, ",");

    echo json_encode( array("data1" => $cartItemIdsUnArray.",", "data2" => $cartItemQuanUnArray.","));
}





