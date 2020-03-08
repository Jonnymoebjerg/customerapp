<?php

include '../php/globals/conn.php';
include '../php/classes/user.php';
include '../php/classes/orders.php';
include '../php/classes/products.php';
include '../php/classes/customers.php';
include '../php/classes/productSpecialPrices.php';

$customerHandler = new customers($conn);

session_start();

$ordersCode1 = $_POST['ordersCode'];
$ordersCount1 = $_POST['ordersCount'];

$ordersCode = explode(',', $ordersCode1);
$ordersCount = explode(',', $ordersCount1);

$orderProducts = array_combine($ordersCode, $ordersCount);

$customerNumber = $_SESSION['customer_num'];
$getCustomerInfo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM core_customers WHERE customer_num = '$customerNumber'"));
$customerName = $getCustomerInfo['name'];
$customerEmail = $getCustomerInfo['email'];
$customerPhone = $getCustomerInfo['phone'];
$customerAddress = $getCustomerInfo['address'];
$customerCity = $getCustomerInfo['city'];
$customerRegion = $getCustomerInfo['region'];
$customerCountry = $getCustomerInfo['country'];

$getCustomerContactInfo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM core_customers_contactpersons WHERE customer_num = '$customerNumber'"));
$customerContactName = $getCustomerContactInfo['name'];
$customerContactEmail = $getCustomerContactInfo['email'];
$customerContactPhone = $getCustomerContactInfo['phone'];
/*
//Clear for empty products
foreach( $orderProducts as $key => $value ) {
    if( $value === "0" || $value === "00" ){
        unset($orderProducts[$key]);
    }
}
 */

//Print Products
$products = new products($conn);
$datetime = date("Y-m-d h:i:s");
$productsPrint = "
        <html>
        <head>
        <title>APP</title> 
        <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
        <style>
            table {
                font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td, table th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            tr:nth-child(even){background-color: #f2f2f2;}

            th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #27ae60;
                color: white;
            }
        </style>
        </head>
        <body style='margin: 0; padding: 0;'>
            <table>
                <tr><td colspan='5'><img src='' height='50'>Webapp</td></tr>
                <tr><th colspan='5'>Ordreinformationer</th></tr>
                <tr>
                    <td style='width:82,5%'>
                        <p><b>Ordre ID:</b> ???</p>
                        <p><b>Oprettet den:</b> " . $datetime . "</p>
                        <p><b>Betalingsmåde:</b> Faktura</p>
                        <p><b>Forsendelsesmåde:</b> ???</p>
                    </td>
                    <td colspan='4' style='width:17.5%'>
                        <p><b>Email:</b> " . $customerContactEmail . "</p>
                        <p><b>Telefon:</b> " . $customerContactPhone . "</p>
                        <p><b>IP Adresse:</b> ???.???.???.???</p>
                        <p><b>Ordrestatus:</b> Pending</p>
                    </td>
                </tr>
                
                <tr>
                    <th style='width:50%;'>Fakturadresse</td>
                    <th colspan='4' style='width:50%;'>Forsendelsesadresse</td>
                </tr>
                <tr>
                    <td style='width:50%'>
                        <p> " . $customerContactName . "</p>
                        <p> " . $customerName . "</p>
                        <p>" . $customerAddress . "</p>
                        <p> " . $customerCity . "</p>
                        <p> " . $customerRegion . "</p>
                        <p> " . $customerCountry . "</p>
                    </td>
                    <td colspan='4' style='width:50%'>
                        <p> " . $customerContactName . "</p>
                        <p> " . $customerName . "</p>
                        <p>" . $customerAddress . "</p>
                        <p> " . $customerCity . "</p>
                        <p> " . $customerRegion . "</p>
                        <p> " . $customerCountry . "</p>
                    </td>
                </tr>
                
                <tr>
                    <th style='width:50%'>Produkt</th>
                    <th style='width:12.5%'>Produktkode</th>
                    <th style='width:12.5%'>Antal</th>
                    <th style='width:12.5%'>Pris</th>
                    <th style='width:12.5%'>Total</th>
                </tr>";
                $subtotal = array();
                $kolliCount = array();
                $orderToDb = "";
                
                foreach( $orderProducts as $key => $value ) {
                    $parameter = "WHERE id = '$key'";
                    foreach ($products->getProducts($parameter) as $product) {
                        $productsPrint.= "<tr>";
                        $productsPrint.= "<td style='width:30%'>" . $product->getName() . "</td>";
                        $productsPrint.= "<td style='width:17.5%'>" . $product->getCode() . "</td>";
                        $productsPrint.= "<td style='width:17.5%'>" . $value . "</td>";
                        $productsPrint.= "<td style='width:17.5%'>" . number_format($product->getPrice(), 2, '.', ',') . ",-</td>";
                        $productsPrint.= "<td style='width:17.5%'>" . number_format($product->getPrice() * $value, 2, '.', ',') . ",-</td>";
                        $productsPrint.= "</tr>";
                        array_push($subtotal, $product->getPrice() * $value);
                        array_push($kolliCount, $value);
                        $orderToDb .= $product->getCode() . ":" . $value . ",";
                    }
                }
                
                $orders = new orders($conn);
                $orders->setOrder($customerNumber, $orderToDb, $datetime, array_sum($subtotal));
                
                $productsPrint .= "<tr>
                    <td colspan='4'>Subtotal::</td>
                    <td> " . number_format(array_sum($subtotal), 2, '.', ',') . ",-</td>
                </tr>
                <tr>
                    <td colspan='4'>Levering:</td>";
                    if (array_sum($subtotal) > 1500) {
                        $deliveryPrice = 0;
                    } else {
                        $deliveryPrice = array_sum($kolliCount) * 48;
                    }
                    
                    $productsPrint .= "<td>" . number_format($deliveryPrice, 2, '.', ',') . ",-</td>
                </tr>
                <tr>
                    <td colspan='4'>MOMS (25%):</td>";
                    $moms = array_sum($subtotal) / 4;
                    $productsPrint .= "<td>" . number_format($moms, 2, '.', ',')  . ",-</td>
                </tr>
                <tr>
                    <td colspan='4'>Total DKK::</td>";
                    $total = $moms + array_sum($subtotal) + $deliveryPrice;
                    $productsPrint .= "<td>" . number_format($total, 2, '.', ',') . ",-</td>
                </tr>
            </table>
        </body>
        </html>";

        $productsPrint.= "<script>
        var cartItemIds = sessionStorage.setItem('cartItemIds','');
        var cartItemQuan = sessionStorage.setItem('cartItemQuan','');
        </script>";
                    
                
$recievermail = "post";
$customermail = $_SESSION['customer_email'];
$subject = "Fra App | Bestilling";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$headers .= "From: <APP>" . "\r\n";
//$headers .= 'Cc: myboss@example.com' . "\r\n";

mail($recievermail,$subject,$productsPrint,$headers);
//mail($customermail,"Plant2Plast |APP",$productsPrint,$headers);
$_SESSION['thanksforyourorder'] = "1";

header('Location: ../orders.php');