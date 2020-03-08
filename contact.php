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
?>
<!DOCTYPE html>
<html>

    <head>
        <?php include 'includes/head.php' ?>
    </head>

    <body>
        <?php include 'includes/nav.php' ?>
        <?php include 'includes/modals/modalLogout.php' ?>
        <div class="container" style="margin-top:70px;">
            <?php include 'includes/modals/modalFaq.php' ?>
            <h2>Contact</h2>
            <hr>
            <div>
                <p>Phone:. (+45)</p>
                <p>Mail: post</p>
                <p>Webshop: https://.dk</p>
                <p>Address: </p>
                <a href="tel:+4564653277"><button type="button" class="btn btn-primary" style="width:100%;">CALL US</button></a>
                <hr>
                <p>If you have any problems using the app, please see if we have an answer for you <span id="">here</span>. Otherwise you are welcome to contact us using the form down below or calling us, you can see the informations at the top of the page.</p>
                <button type="button" class="btn btn-primary" style="width:100%;" data-toggle="modal" data-target="#modalFaq">FAQ.</button>
                <a href="http://salgs-og-leveringsbetingelser" target="_blank"><button type="button" class="btn btn-primary" style="width:100%;margin-top:10px;">Terms and conditions.</button></a>
                <hr>
                <?php
                    $customerNumber = $_SESSION['customer_num'];
                    $getCustomerInfo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM core_customers WHERE customer_num = '$customerNumber'"));
                    $customerName = $getCustomerInfo['name'];
                    $customerEmail = $getCustomerInfo['email'];
                    
                    if ($_SESSION['thanksforyourmail'] === "1") {
                        echo "<h2>Thanks for your message, we will reply as soon as possible.</h2>";
                        $_SESSION['thanksforyourmail'] = "0";
                    } else {
                        echo '<form action="php/sendMail.php" method="post">
                        <div class="form-group">
                            <label for="contactInputName">Name</label>
                            <input type="text" class="form-control" id="contactInputName" name="contactInputName" placeholder="Name" value="' . $customerName . '">
                        </div>
                        <div class="form-group">
                            <label for="contactInputEmail">Email</label>
                            <input type="email" class="form-control" id="contactInputEmail" name="contactInputEmail" placeholder="Email" value="' . $customerEmail . '">
                        </div>
                        <div class="form-group">
                            <label for="contactInputTextares">Message</label>
                            <textarea class="form-control" rows="3" id="contactInputTextares" name="contactInputTextares"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success" style="width:100%;margin-bottom:25px;">Send</button>
                    </form>';
                    }
                ?>
                    
            </div>
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/bootstrap-notify-3.1.3/bootstrap-notify.js"></script>
    </body>

</html>