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

$orders = new orders($conn);
?>
<!DOCTYPE html>
<html>

    <head>
        <?php include 'includes/head.php' ?>
    </head>

    <body>
        <?php include 'includes/nav.php' ?>
        <?php include 'includes/modals/modalLogout.php' ?>
        <?php include 'includes/modals/modalViewOrder.php' ?>
        <div class="modal fade" id="modalViewOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="modalContent"></div>
            </div>
        </div>
        <div class="container" style="margin-top:70px;">
            <?php
                if ($_SESSION['thanksforyourorder'] === "1") {
                    echo "<h2>Thanks for your order!</h2>";
                    $_SESSION['thanksforyourorder'] = "0";
                    echo "<script>
                    sessionStorage.setItem('cartItemIds','');
                    sessionStorage.setItem('cartItemQuan','');
                    </script>";
                }
            ?>
            <h2>Orders</h2>
            <hr>
            <div>
                <table class="table table-striped">
                    <tbody>
                        <?php
                        $customerNum = $_SESSION['customer_num'];
                        $parameter = "WHERE customer_num = '$customerNum' ORDER BY id DESC";
                        foreach ($orders->getOrders($parameter) as $order) {
                            if ($order->getPrice() === "") {
                                $price = "0.00";
                            } else {
                                $price = $order->getPrice();
                            }
                            echo "<tr><th>ID</th><td>" . $order->getId() . "</td></tr>";
                            echo "<tr><th>Datetime</th><td>" . $order->getDatetime() . "</td></tr>";
                            echo "<tr><th>Price</th><td>" . number_format($price, 2, '.', ',') . ",-</td></tr>";
                            echo "<tr>";
                            echo "<td></td>";
                            echo "<td><button class='btn btn-primary pull-right viewOrderBtn' type='button' data-orderid='" . $order->getId() . "'>See Order</button><button type='button' class='btn btn-primary pull-right copyOrderBtn' data-orderid='" . $order->getId() . "'>Copy to Order</button></td>";
                            echo "<tr><td></td><td></td></tr>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/bootstrap-notify-3.1.3/bootstrap-notify.js"></script>
        <script>
                
            var currentCart = sessionStorage.getItem('cartItemIds');
            var currentQuan = sessionStorage.getItem('cartItemQuan');
            console.log(currentCart);
            console.log(currentQuan);
            
            $(".viewOrderBtn").on('click', function () {
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
            
            $(".copyOrderBtn").on('click', function () {
                
                //Get Data-ID
                var orderId = this.getAttribute("data-orderid");
                var currentCart = sessionStorage.getItem('cartItemIds');
                console.log(currentCart);
                console.log(currentCart);
                var currentQuan = sessionStorage.getItem('cartItemQuan');
                console.log(currentQuan);

                //Ajaxify
                $.ajax({
                    type: "POST",
                    url: "php/copyOrder.php",
                    dataType: "json",
                    data: {orderId: orderId, currentCart:currentCart, currentQuan:currentQuan},
                    success: function (result) {
                        sessionStorage.setItem('cartItemIds',result.data1);
                        sessionStorage.setItem('cartItemQuan',result.data2);
                        //window.location.href = "cart.php";
                    }
                });
            });
        </script>
    </body>

</html>