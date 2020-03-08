<?php

include '../php/globals/conn.php';
include '../php/classes/products.php';
 
$productHandler = new products($conn);

$cartItemIdsPost = $_POST['cartItemIds'];
$cartItemQuanPost = $_POST['cartItemQuan'];
$productId = $_POST['itemId'];
$countType = $_POST['countType'];

$cartItemIdsArray = explode(',', $cartItemIdsPost);
$cartItemIdsQuan = explode(',', $cartItemQuanPost);

$arrayNumber = array_search($productId,$cartItemIdsArray);

if ($countType === "minus") {
    if ($cartItemIdsQuan[$arrayNumber] === "1") {
        echo $cartItemQuanPost;
    } else {
        $arrayValue = $cartItemIdsQuan[$arrayNumber] - 1;
        $cartItemQuan = array($arrayNumber=>$arrayValue);
        $cartItemQuanUnArrayt = array_replace($cartItemIdsQuan,$cartItemQuan);
        
        //Get new total
        $maxI = count($cartItemIdsArray);
        $i = 0;
        foreach ($cartItemIdsArray as $cartItem) {
            if ($i === $maxI) {

            } else {
                $product = $productHandler->getProductFromId($cartItem);
                if ($product->getId() != "") {
                    $_SESSION['cartTotalPrice'] = $_SESSION['cartTotalPrice'] + ($product->getPrice() * $cartItemIdsQuan[$i]);

                    $i++;
                }
            }
        }

        $cartItemQuanUnArray = implode (",", $cartItemQuanUnArrayt);
        echo $cartItemQuanUnArray;
    }
} else {
    $arrayValue = $cartItemIdsQuan[$arrayNumber] + 1;
    $cartItemQuan = array($arrayNumber=>$arrayValue);
    $cartItemQuanUnArrayt = array_replace($cartItemIdsQuan,$cartItemQuan);
            
    //Get new total
    $maxI = count($cartItemIdsArray);
    $i = 0;
    foreach ($cartItemIdsArray as $cartItem) {
        if ($i === $maxI) {
        } else {
            $product = $productHandler->getProductFromId($cartItem);
            if ($product->getId() != "") {
                $_SESSION['cartTotalPrice'] = $_SESSION['cartTotalPrice'] + ($product->getPrice() * $cartItemIdsQuan[$i]);

                $i++;
            }
        }
    }
    
    $cartItemQuanUnArray = implode (",", $cartItemQuanUnArrayt);
    echo $cartItemQuanUnArray;
}

