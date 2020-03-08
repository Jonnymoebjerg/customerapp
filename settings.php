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
        <?php include 'includes/modals/modalTutorial.php' ?>
        <div class="container" style="margin-top:70px;">
            <?php include 'includes/modals/modalFaq.php' ?>
            <?php include 'includes/modals/modalRele.php' ?>
            <h2>Settings</h2>
            <hr>
            <button type="button" class="btn btn-primary" style="width:100%;margin-bottom:25px;" data-toggle="modal" data-target="#modalTutorial">Show Tutorial</button>
            <hr>
            
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/bootstrap-notify-3.1.3/bootstrap-notify.js"></script>
    </body>

</html>