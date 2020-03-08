<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include '../php/globals/conn.php';
include '../php/classes/products.php';
include '../php/classes/productCategories.php';
include '../php/classes/favorite.php';
 
$productHandler = new products($conn);
$productCategoryHandler = new productCategories($conn);
$favoriteHandler = new favorite($conn);

$categoryId = $_POST['categoryId'];

$currentCart = $_POST['currentCart'];
$cartItems = preg_split( '/(,|:)/', $currentCart);
array_pop($cartItems);

$favItems = array();
$usernameee = $_SESSION['userid'];
foreach ($favoriteHandler->getFavorites("WHERE customer_id = '$usernameee'") as $favorite) {
    array_push($favItems, $favorite->getProductId());
}

if ($categoryId === "0") {
    $parameterSort = "";
    $headerSort = "";
    $favoriteShow = "0";
} else if($categoryId === "99") {
    $categoryName = "Favorites <i class='fa fa-star'></i>";
    $parameterSort = "";
    $headerSort = "Sort by: Favorites <i class='fa fa-star'></i>";
    $favoriteShow = "1";
} else {
    $categoryName = $productCategoryHandler->getProductCategoryFromId($categoryId);
    $parameterSort = "WHERE category = '$categoryId' ";
    $headerSort = "Sort by: " . $categoryName->getName();
    $favoriteShow = "0";
}
/*
$searchPhrase = $_POST['searchPhrase'];
if ($searchPhrase === "") {
    $parameterSearch = "";
    $headerSearch = "";
} else {
    $parameterSearch = "WHERE name LIKE '%$searchPhrase%' ";
    $headerSearch = "Search for: " . $searchPhrase;
}
*/

if ($favoriteShow === "1") {
    $favoriteArray = array();
    foreach ($favoriteHandler->getFavorites("WHERE customer_id = $usernameee") as $favoriteObj) {
        $pid = $favoriteObj->getProductId();
        array_push($favoriteArray,$pid);
    }
    
    $favArr = "'". implode("', '", $favoriteArray) ."'";
    $favoriteArraySQL = "id IN (" . $favArr . ")";
} else {
    $favoriteArraySQL = "";
}


$header = $headerSort/* . $headerSearch*/;
$productPrint= "<h2>" . $header . "</h2>";
$productPrint.= "<table class='table table-striped'><thead><tr><th>Code </th><th></th><th>Product </th><th>Add </th><!--<th>Fav</th>--></tr></thead><tbody>";
$parameterLimit = "";
if ($parameterSort === ""){$ifwhere = "WHERE ";}else {$ifwhere = "AND ";};
if ($favoriteArraySQL === ""){$ifwhere2 = "";}else {$ifwhere2 = "AND ";};
$parameter = $parameterSort . $ifwhere . $favoriteArraySQL . $ifwhere2 . "active = 1";
foreach ($productHandler->getProducts($parameter) as $product) {
    $productPrint.= "<tr id='" . $product->getId() . "'>";
    $productPrint.= "<td class='clickMoreInfo' data-id='" . $product->getId() . "'>" . $product->getCode() . "</td>";
    $productPrint.= "<td class='clickMoreInfo' data-id='" . $product->getId() . "'><img src='gfx/productimg/" . $product->getCode() . "-sm.png' class='img-responsive'></td>";
    $productPrint.= "<td class='clickMoreInfo' data-id='" . $product->getId() . "'>" . $product->getName() . "</td>";
    $productPrint.= "<td><button class='btn btn-success' type='button' data-productid='" . $product->getId() . "' onclick='addProduct(this)'";
    if(in_array($product->getId(), $cartItems)) {
        $productPrint.=  "disabled='disabled'";
    }
    $productPrint.= ">Add</button></td>";
    /*
    $productPrint.= "<td class=''><i class='fa fa-star fa-2x favoriteBtn ";
    if(in_array($product->getId(), $favItems)){
        $productPrint.=  "favBtnSelected'";
    } else {
        $productPrint.=  "'";        
    }
    $productPrint.= "' data-id='" . $product->getId() . "' aria-hidden='true'></i></td>";
     */
    $productPrint.= "</tr>";
}

$productPrint.= "</tbody></table>";

$productPrint.= "<script>
                
        var cartItemQuantity = [];
        function addProduct(a) {
            var productId = a.getAttribute('data-productid') + ',';
            
            var currentCart = sessionStorage.getItem('cartItemIds');
            if (currentCart === null) { currentCart = '' };
            console.log('oldItemIds: ' + currentCart);
            
            var currentQuan = sessionStorage.getItem('cartItemQuan');
            if (currentQuan === null) { currentQuan = '' };
            console.log('oldItemQuan: ' + currentQuan);
            
            var productIdTest = a.getAttribute('data-productid');
            $(a).attr('disabled','disabled');

            var testId = new RegExp(productIdTest);
            var exists = testId.test(currentCart);
            
            if(exists) {
                console.log('Exists!');
                pushAlreadyAddedProductNotification();
            } else {
                newCartItemIds = currentCart+productId;
                sessionStorage.setItem('cartItemIds', newCartItemIds);
                console.log('newItemIds: ' + sessionStorage.getItem('cartItemIds'));
                                
                newCartItemQuan = currentQuan + '1,';
                sessionStorage.setItem('cartItemQuan', newCartItemQuan);
                console.log('newItemQuan: ' + sessionStorage.getItem('cartItemQuan'));
                
                console.log(' ');

                pushAddProductNotification();
            }
        };
        
        $('.clickMoreInfo').click(function(){
            var productId = $(this).data('id');
            console.log(productId);
            
            var categoryId = $('#productCategory').find('option:selected').val();
            
            $.ajax({
                type: 'POST',
                url: 'php/showProductInfo.php',
                datatype: 'text',
                data: {productId:productId,categoryId:categoryId},
                success: function (result) {
                    $('#containerProductDetails').html('');
                    
                    $(result).appendTo('#containerProductDetails');
                    $('#modalProductInfo').modal();
                }
            });
            
        });
        </script>";

echo $productPrint;