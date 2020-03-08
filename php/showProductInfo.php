<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include 'globals/conn.php';
include 'classes/products.php';
include 'classes/favorite.php';

$productId = $_POST['productId'];
$productCategory = $_POST['categoryId'];

$productHandler = new products($conn);
$product = $productHandler->getProductFromId($productId);
$favoriteHandler = new favorite($conn);

$favItems = array();
$usernameee = $_SESSION['userid'];
foreach ($favoriteHandler->getFavorites("WHERE customer_id = '$usernameee'") as $favorite) {
    array_push($favItems, $favorite->getProductId());
}

$modal = "<div class='modal fade' id='modalProductInfo' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title' id='myModalLabel'>" . $product->getName() . "</h4>
            </div>
            <div class='modal-body'>
                <div class='thumbnail center-block'>
                    <img src='gfx/productimg/" . $product->getCode() . ".png'>
                </div>
                <p>Pcs/kolli: " . $product->getKolli() . "</p>
                <p>Price/kolli: " . $product->getPrice() . ",-</p>
            </div>
            <div class='modal-footer' id='btnFavContainer'>";
                $modal.= "<button type='button' id='favoriteBtn' class='btn ";
                if(in_array($product->getId(), $favItems)){
                    $modal.=  "favBtnSelected btn-primary' data-id='" . $productId . "'>Remove Favorite";
                } else {
                    $modal.=  "btn-success' data-id='" . $productId . "'>Add Favorite";
                }
                $modal.= "</button>
                <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>"; 

$modal.="<script>
        $(document).on('click', '#favoriteBtn', function () {
            var productId = $(this).data('id');
            if($(this).hasClass('favBtnSelected')) {
                $.ajax({
                    type: 'POST',
                    url: 'php/unsetFavorite.php',
                    datatype: 'text',
                    data: {productId:productId},
                    success: function (result) {
                        console.log('unFavorite');
                        $('#btnFavContainer').html(result);
                    }
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'php/setFavorite.php',
                    datatype: 'text',
                    data: {productId:productId},
                    success: function (result) {
                        console.log('Favorite');
                        $('#btnFavContainer').html(result);
                    }
                });
            }";
if ($productCategory === "99") {
    $modal.="location.href='http://plant2plast.dk/app/index.php?shfv=1';";
}
    $modal.="});</script>";
echo $modal;