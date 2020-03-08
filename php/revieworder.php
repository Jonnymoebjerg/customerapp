<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../php/globals/conn.php';
include '../php/classes/orders.php';
include '../php/classes/products.php';

$id = $_POST['orderId'];
 
$orderHandler = new orders($conn);
$order = $orderHandler->getOrderFromId($id);

$productHandler = new products($conn);

$viewOrderModal = "<div class='modal-header'>
    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
    <h4 class='modal-title' id='myModalLabel'>Order now!</h4>
</div>
<div class='modal-body' id='modalBody'>
    <table class='table'>
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Product</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>";
            $orderItems = preg_split( '/(,|:)/', $order->getItems());
            array_pop($orderItems);
            $i = 0;
            $orderItemsCode = array();
            $orderItemsQuantity = array();
            foreach ($orderItems as $orderItem) {
                if ($i % 2 == 0) {
                    array_push($orderItemsCode,$orderItem);
                    $i++;
                } else {
                    array_push($orderItemsQuantity,$orderItem);
                    $i++;
                }
            }
            
            $orderItems = preg_split( '/(,|:)/', $order->getItems());
            array_pop($orderItems);
            $i = 0;
            foreach ($orderItemsCode as $orderItem) {
                    $viewOrderModal.="<tr>";
                    $viewOrderModal.="<td>" . $i . "</td>";
                    $viewOrderModal.="<td><img src='gfx/productimg/" . $orderItem . "-sm.png'></td>";
                    $viewOrderModal.="<td>" . $orderItem . "</td>";
                    $viewOrderModal.="<td>" . $orderItemsQuantity[$i] . "</td>";
                    $viewOrderModal.="</tr>";
                    $i++;
                
            }
    $viewOrderModal.= "</tbody></table>
    <p>Price: " . number_format($order->getPrice(), 2, '.', ',') . ",-</p>
</div>
<div class='modal-footer'>
    <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
</div>";

echo $viewOrderModal;



