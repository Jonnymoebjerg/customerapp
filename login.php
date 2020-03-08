<?php
//Require class User for login
include 'php/classes/user.php';

$userName = $_GET['u'];
$password = $_GET['p'];
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'includes/head.php' ?>
        <?php include 'includes/modals/modalForgotLogin.php' ?>
        <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    </head>
    <body>
        <div class="login-clean">
            <form method="post" action="php/login.php">
                <h2 class="sr-only">Login Form</h2>
                <div class="illustration"><img src="" class="img-responsive" style="display:initial;"></div>
                <div class="form-group">
                    <input class="form-control" type="text" name="loginUsername" placeholder="Username" value="<?php if($userName != ""){echo $userName;} ?>">
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="loginPassword" placeholder="Password" value="<?php if($password != ""){echo $password;} ?>">
                </div>
                <div class="form-group">
                    <button class="btn btn-success btn-block" type="submit">Log In</button>
                </div>
                <a href="#" class="forgot" data-toggle="modal" data-target="#modalForgotLogin">Forgot your username or password?</a>
            </form>
        </div>
    </body>
</html>