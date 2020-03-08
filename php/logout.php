<?php
include '../globals/conn.php';
include '../php/classes/logs.php';
include '../php/globals/conn.php';

session_start();

$logHandler = new logs($conn);
$logHandler->setLog("3", "1", "Logout", "ID:".$_SESSION['userid'].",");

session_unset();
session_destroy();

header("location: ../login.php");
exit();