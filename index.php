<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function ($class_name) {
    include 'php/classes/' . $class_name . '.php';
});

require 'php/globals/conn.php';

session_start();

if ($_SESSION['loggedin'] === "true") {
    
} else {
    $_SESSION['error_msg'] = "Not logged in";
    header("Location: login.php");
    die();
}

$productCategoryHandler = new productCategories($conn);
$orders = new orders($conn);

$userid = $_SESSION['customer_num'];
?>
<!DOCTYPE html>
<html>

    <head>
        <?php include 'includes/head.php' ?>
    </head>

    <body>
        <?php include 'includes/nav.php' ?>
        <?php include 'includes/modals/modalLogout.php' ?>
        <div class="modal fade" id="modalViewOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="modalContent"></div>
            </div>
        </div>
        <div class="container" style="margin-top:70px;">
            <h2>All products <span id="cartBtnDiv"></span></h2>
            <!--<form class="form-inline">
                <div class="form-group">
                    <input type="text" class="form-control" id="inputSearch" placeholder="Search..">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="button" id="searchBtn">Search</button>
                </div>
            </form>-->
            <div class="form-group">
                <select class="form-control" id="productCategory">
                    <?php
                    echo "<option value='0'>All (" . $productCategoryHandler->getCount() . ")</option>";
                    echo "<option value='99'>Favorites</option>";
                    foreach ($productCategoryHandler->getProductCategories() as $category) {
                        echo "<option value='" . $category->getId() . "'>" . $category->getName() . " (" . $category->getCount($category->getId()) . ")</option>";
                    }
                    ?>
                </select>
            </div>
            <?php 
                foreach($orders->getOrders("WHERE customer_num = $userid ORDER BY id DESC LIMIT 1") as $order) {
                    $latestOrderId = $order->getId();
                    if (!isset($latestOrderId)) {
                        return;
                    } else {
                        echo "<button class='btn btn-primary' id='viewOrderBtn' data-orderid='" . $latestOrderId . "' type='button' id='searchBtn'>Check your latest orders</button>";
                    }
                }
            ?>
            <hr>
            <div id="productBody"></div>
        </div>
        <div id="containerProductDetails"></div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/bootstrap-notify-3.1.3/bootstrap-notify.js"></script>
        <script src="js/jsNotify.js"></script>
        <script>
            $(document).ready(function(){
                var categoryId = "0";
                var searchPhrase = "";
                var currentCart = sessionStorage.getItem('cartItemIds');
                
                var shfv = getQueryVariable("shfv");
                console.log(shfv);
                if (shfv === "1") {
                    function setSelectedIndex(s, i) {
                        s.options[i].selected = true;
                        return;
                    }
                    
                    setSelectedIndex(document.getElementById("productCategory"),1);
                    
                    var categoryId = "99";
                    console.log(categoryId);
                }
                
                window.setInterval(function(){
                    var currentCart = sessionStorage.getItem('cartItemIds');
                    if (currentCart === "") {
                        $("#cartBtnDiv").html('');
                        $("#cartBtnDiv").append("<button class='btn btn-danger pull-right' type='button' disabled='disabled'>Cart <i class='fa fa-arrow-right' aria-hidden='true'></i></button>");
                    } else {
                        $("#cartBtnDiv").html('');
                        $("#cartBtnDiv").append("<a href='cart.php'><button class='btn btn-success pull-right' type='button'>Cart <i class='fa fa-arrow-right' aria-hidden='true'></i></button></a>");
                    }
                }, 100);
                
                $.ajax({
                    type: "POST",
                    url: "php/getProductList.php",
                    datatype: "text",
                    data: {categoryId: categoryId, searchPhrase:searchPhrase, currentCart:currentCart},
                    success: function (result) {
                        $("#productBody").append(result);
                    }
                });
                
                $('#productCategory').change(function(e) {
                    var categoryId = $(this).find('option:selected').val();
                    console.log(categoryId);
                    $("#productBody").empty();
                    $.ajax({
                        type: "POST",
                        url: "php/getProductList.php",
                        datatype: "text",
                        data: {categoryId: categoryId, searchPhrase:searchPhrase, currentCart:currentCart},
                        success: function (result) {
                            $("#productBody").append(result);
                        }
                    });
                });
                
                $('#searchBtn').click(function(e){
                    var searchPhrase = document.getElementById("inputSearch").value;
                    $("#productBody").empty();
                    $.ajax({
                        type: "POST",
                        url: "php/getProductList.php",
                        datatype: "text",
                        data: {categoryId: categoryId, searchPhrase:searchPhrase, currentCart:currentCart},
                        success: function (result) {
                            $("#productBody").append(result);
                        }
                    });
                });
                
                $("#viewOrderBtn").on('click', function () {
                    //Clear old data in modal
                    $("#modalContent").empty();

                    //Get Data-ID
                    var orderId = this.getAttribute("data-orderid");
                    console.log(orderId);
                    //Ajaxify
                    $.ajax({
                        type: "POST",
                        url: "php/viewOrder.php",
                        datatype: "text",
                        data: {orderId: orderId},
                        success: function (result) {
                            $("#modalContent").append(result);
                        }
                    });

                    //Show Modal
                    $("#modalViewOrder").modal();
                });
                
                function getQueryVariable(variable) {
                    var query = window.location.search.substring(1);
                    var vars = query.split("&");
                    for (var i=0;i<vars.length;i++) {
                            var pair = vars[i].split("=");
                            if(pair[0] === variable){return pair[1];}
                    }
                    return(false);
                }
            });
        </script>
    </body>
</html>