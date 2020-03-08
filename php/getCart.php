<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../php/globals/conn.php';
include '../php/classes/products.php';
 
$productHandler = new products($conn);

$cartPrint = "";

$cartItemIds = $_POST['cartItemIds'];
$cartItemQuantity = $_POST['cartItemQuan'];

if ($cartItemIds === "") {
    $cartPrint.= "<h2>Cart empty!</h2>";
    $cartPrint.= "<script>
            $('.btnCartOrder').attr('disabled','disabled');
            $('.btnClearCart').attr('disabled','disabled');
            ";
} else {
    $cartItemQuantityArray = explode(',', $cartItemQuantity);

    $cartItems = explode(',', $cartItemIds);
    $maxI = count($cartItems);
    $i = 0;
    foreach ($cartItems as $cartItem) {
        if ($i === $maxI) {
            
        } else {
            $product = $productHandler->getProductFromId($cartItem);
            if ($product->getId() != "") {
                $cartPrint.= "<tr class='tr-" . $product->getId() . "'><th>Code</th><td>" . $product->getCode() . "</td></tr>";
                $cartPrint.= "<tr class='tr-" . $product->getId() . "'><th>Image</th><td><img class='pull-right' style='width:100%' src='gfx/productimg/" . $product->getCode() . ".png'></td></tr>";
                $cartPrint.= "<tr class='tr-" . $product->getId() . "'><th>Product</th><td>" . $product->getName() . "</td></tr>";
                $cartPrint.= "<tr class='tr-" . $product->getId() . "'><th>Price/kolli</th><td class='price-" . $product->getId() . "'>" . $product->getPrice() . "</td></tr>";
                $cartPrint.= "<tr class='tr-" . $product->getId() . "'>" . $_POST['cartItemQuan'];
                $cartPrint.= "<td><button class='btn btn-danger removeProduct' type='button' data-id='" . $product->getId() . "' data-productid='" . $product->getCode() . "'>Remove</button></td>";
                $cartPrint.= "<td><div class='btn-group pull-right' role='group' aria-label='...' style='width:106px'><button type='button' class='btn btn-info btnOrderMinus' data-productid='" . $product->getId() . "' data-id='1'>-</button><button type='button' class='btn' data-id='" . $product->getId() . "' data-class='itemCount' data-productid='" . $product->getId() . "'>" . $cartItemQuantityArray[$i] . "</button><button type='button' class='btn btn-info btnOrderPlus' data-productid='" . $product->getId() . "' data-getproductid='" . $product->getId() . "' data-id='1'>+</button></div></td>";
                $cartPrint.= "</tr>";
                $cartPrint.= "<tr style='background-color:transparent;'><td colspan='2'><hr></td></tr>";

                $_SESSION['cartTotalPrice'] = $_SESSION['cartTotalPrice'] + ($product->getPrice() * $cartItemQuantityArray[$i]);

                $i++;
            }
        }
    }

    $cartPrint.= "<script>

            var cartItemQuan = sessionStorage.getItem('cartItemQuan');
            var cartItemIds = sessionStorage.getItem('cartItemIds');  

            console.log(cartItemIds);
            console.log(cartItemQuan);

            function getCartTotal() {
                var cartItemIds = sessionStorage.getItem('cartItemIds');
                var cartItemQuan = sessionStorage.getItem('cartItemQuan');
                
                $.ajax({
                    type: 'POST', 
                    url: 'php/getCartTotal.php',
                    datatype: 'text',
                    data: {cartItemIds: cartItemIds, cartItemQuan:cartItemQuan},
                    success: function (result) {
                        console.log(result);
                        var totalPrice = result;
                        $('#cartTotalPrice').html(totalPrice);
                    }
                });
            };

            //Minus button click function
            $('.btnOrderMinus').on('click', function(event) {
                var itemId = $(this).data('productid');
                var countType = 'minus';

                $.ajax({
                    type: 'POST',
                    url: 'php/setQuantity.php',
                    datatype: 'text',
                    data: {itemId:itemId, countType:countType, cartItemIds: sessionStorage.getItem('cartItemIds'), cartItemQuan: sessionStorage.getItem('cartItemQuan')},
                    success: function (result) {
                        console.log(result);
                        sessionStorage.setItem('cartItemQuan','');
                        sessionStorage.setItem('cartItemQuan',result);

                        var oldBtnNum = parseInt($('button[data-class=itemCount][data-id=' + itemId + ']').html());

                        if (oldBtnNum === 1) {
                            return;
                        }  else {
                            var plusMinus = 1;
                            var newBtnNum = oldBtnNum - plusMinus;
                            $('button[data-class=itemCount][data-id=' + itemId + ']').html(newBtnNum);
                        }
                        getCartTotal();
                    }
                });
            });

            //Plus button click function
            $('.btnOrderPlus').on('click', function(event) {
                var itemId = $(this).data('productid');
                var countType = 'plus';

                $.ajax({
                    type: 'POST',
                    url: 'php/setQuantity.php',
                    datatype: 'text',
                    data: {itemId:itemId, countType:countType, cartItemIds: sessionStorage.getItem('cartItemIds'), cartItemQuan: sessionStorage.getItem('cartItemQuan')},
                    success: function (result) {
                        console.log(result);
                        sessionStorage.setItem('cartItemQuan','');
                        sessionStorage.setItem('cartItemQuan',result);

                        var oldBtnNum = parseInt($('button[data-class=itemCount][data-id=' + itemId + ']').html());
                        var plusMinus = 1;
                        var newBtnNum = oldBtnNum + plusMinus;
                        $('button[data-class=itemCount][data-id=' + itemId + ']').html(newBtnNum);
                        
                        getCartTotal();
                    }
                });
            });

            $( '.removeProduct' ).click(function(e){
                var productId = $(this).data('id');
                $('.tr-'+productId).remove();

                $.ajax({
                    type: 'POST',
                    url: 'php/removeItem.php',
                    datatype: 'text',
                    data: {cartItemIds:sessionStorage.getItem('cartItemIds'), cartItemQuan:sessionStorage.getItem('cartItemQuan'), productId: productId},
                    success: function (result) {
                        sessionStorage.setItem('cartItemQuan', result);
                    }
                });

                pushNotificationProductRemoved();

                var productIdFinal = productId + ',';

                var currentCart = sessionStorage.getItem('cartItemIds');
                var newCart = currentCart.replace(productIdFinal, '');

                sessionStorage.setItem('cartItemIds', '');
                sessionStorage.setItem('cartItemIds', newCart);

                var cartItemIds = sessionStorage.getItem('cartItemIds');
                if (cartItemIds === '') {
                    $('#cartBody').append('<h2>Cart empty!</h2>');
                    $('.btnCartOrder').attr('disabled','disabled');
                    $('.btnClearCart').attr('disabled','disabled');
                    $('<style>body { background-color: lightgray !important; }</style>').appendTo('head');
                }

                console.log('newCurrentItemIds: ' + sessionStorage.getItem('cartItemIds'));
                console.log('newCurrentItemQuan: ' + sessionStorage.getItem('cartItemQuan'));
            });
            </script>";
}

echo $cartPrint;