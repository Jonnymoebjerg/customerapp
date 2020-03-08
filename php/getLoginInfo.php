<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'globals/conn.php';
include 'classes/user.php';
include 'classes/customers.php';

$userHandler = new user($conn);
$customerHandler = new customers($conn);

$recievermail = $_POST['emailForgot'];

$subject = "Plant2Plast | App Login Information";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$headers .= "From: <P2P APP>" . "\r\n";

$customer = $customerHandler->forgotPwd($recievermail);
$user = $userHandler->getUserFromCustomernum($customer);
$userUsername = $user->getCustomerNumber();
$userPassword = $user->getPwdUnProtected();

$content = "<h2>Hello!</h2>"
. "<p>Here you have your login details for the app.</p>"
. "<p>Username:" . $userUsername . "</p>"
. "<p>Password:" . $userPassword . "</p>"
. "<hr>"
. "<p>Or just use this link..</p>"
. "<a target='_blank' href='http://plant2plast.dk/app/login.php?u=" . $userUsername . "&p=" . $userPassword . "'>Link.</a>"
. "<p>Have a nice day! :)</p>";

mail($recievermail,$subject,$content,$headers);
return;