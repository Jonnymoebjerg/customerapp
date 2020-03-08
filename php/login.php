<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../php/classes/user.php';
include '../php/globals/conn.php';
include '../php/classes/logs.php';

session_start();

$logHandler = new logs($conn);
$user = new user($conn);
$username = mysqli_real_escape_string($conn, $_POST['loginUsername']);

$getHash = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM app_users WHERE username = '$username'"));
$hash = $getHash['password'];
$password = mysqli_real_escape_string($conn, $_POST['loginPassword']);

if (password_verify($password, $hash)) {
    echo $_SESSION['loggedin'] = "true";
    
    echo $_SESSION['username'] = $username;
    
    $getUserId = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM app_users WHERE username = '$username'"));
    $_SESSION['userid'] = $getUserId['id'];
    echo $id = $_SESSION['userid'];
    
    echo $_SESSION['username'] = $username;
    
    $getCustomerNum = mysqli_fetch_assoc(mysqli_query($conn, "SELECT customer_num FROM app_users WHERE username = '$username'"));
    echo $_SESSION['customer_num'] = $getCustomerNum['customer_num'];
    $customerNumber = $_SESSION['customer_num'];
    
    $getCustomerEmail = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM core_customers WHERE customer_num = '$customerNumber'"));
    echo $_SESSION['customer_email'] = $getCustomerEmail['email'];
    
    $getCustomerPhone = mysqli_fetch_assoc(mysqli_query($conn, "SELECT phone FROM core_customers WHERE customer_num = '$customerNumber'"));
    echo $_SESSION['customer_phone'] = $getCustomerPhone['phone'];
    
    $logHandler->setLog("3", "1", "Login", "ID:".$_SESSION['userid'].",");
    
    header("Location: ../index.php");
} else {
    echo 'Invalid password.';
    header("Location: ../login.php");
}