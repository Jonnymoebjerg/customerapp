<?php
spl_autoload_register(function ($class_name) {
    include 'php/classes/' . $class_name . '.php';
});

require 'php/globals/conn.php';

session_start();

if ($_SESSION['loggedin'] == "true") {
    
} else {
    $_SESSION['error_msg'] = "Not logged in";
    header("Location: login.php");
    die();
}

$products = new products($conn);
?>
<!DOCTYPE html>
<html>

<head>
    <?php include 'includes/head.php' ?>
</head>

<body>
    <?php include 'includes/nav.php' ?>
    <?php include 'includes/modals/modalLogout.php' ?>
    <?php include 'includes/modals/modalClearCart.php' ?>
    <?php include 'includes/modals/modalCheckOrder.php' ?>
    <div class="container" style="margin-top:70px;">
        <h2>Cart <button class="btn btn-success pull-right btnCartOrder" data-toggle="modal" data-target="#modalCheckOrder" type="button" style="margin-bottom: 25px;">Place Order</button><button class="btn btn-danger pull-right btnClearCart" type="button" data-toggle="modal" data-target="#modalClearCart">Clear Cart</button></h2>
        <hr>
        <div>
            <table class="table table-striped">
                <tbody id="cartBody"></tbody>
                <tr><th>Total</th><td id='cartTotalPrice'></td></tr>
            </table>
            <hr>
            <button class="btn btn-success pull-right btnCartOrder" data-toggle="modal" data-target="#modalCheckOrder" type="button" style="margin-bottom: 25px;">Place Order</button><button class="btn btn-danger pull-right btnClearCart" type="button" data-toggle="modal" data-target="#modalClearCart">Clear Cart</button>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/bootstrap-notify-3.1.3/bootstrap-notify.js"></script>
    <script>
        $(document).ready(function(){
            
            getCart();
                
            function getCart() {
                var cartItemIds = sessionStorage.getItem("cartItemIds");
                var cartItemQuan = sessionStorage.getItem("cartItemQuan");
                
                $( "#cartBody" ).empty();
                
                if (cartItemIds === "") {
                    $("#cartBody").append("<h2>Cart empty!</h2>");
                    $(".btnCartOrder").attr("disabled","disabled");
                    $(".btnClearCart").attr("disabled","disabled");
                    $( "<style>body { background-color: lightgray !important; }</style>" ).appendTo( "head" );
                } else {
                    $.ajax({
                        type: "POST",
                        url: "php/getCart.php",
                        datatype: "text",
                        data: {cartItemIds: cartItemIds, cartItemQuan:cartItemQuan},
                        success: function (result) {
                            $("#cartBody").append(result);
                            getCartTotal();
                        }
                    });
                }
            }
            
            function getCartTotal() {
                var cartItemIds = sessionStorage.getItem("cartItemIds");
                var cartItemQuan = sessionStorage.getItem("cartItemQuan");
                
                $.ajax({
                    type: "POST",
                    url: "php/getCartTotal.php",
                    datatype: "text",
                    data: {cartItemIds: cartItemIds, cartItemQuan:cartItemQuan},
                    success: function (result) {
                        console.log(result);
                        var totalPrice = result;
                        $('#cartTotalPrice').html(totalPrice);
                    }
                });
            };
        
            //Plus button click function
            $('#btnPlaceOrder').on('click', function() {                
                console.log("Initializing order..");
               
                var ordersCode = sessionStorage.getItem("cartItemIds");
                var ordersCount = sessionStorage.getItem("cartItemQuan");
                    
                var ordersCodeField = document.getElementById('ordersCode');
                var ordersCountField = document.getElementById('ordersCount');

                ordersCodeField.value = ordersCode;
                ordersCountField.value = ordersCount;
                
                $(this).addClass('disabled');
                $('#modalCheckOrder').modal('hide');
            });
        });
    </script>
</body>

</html>