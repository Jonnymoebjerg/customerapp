<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include '../php/globals/conn.php';
include '../php/classes/favorite.php';

$favoriteHandler = new favorite($conn);

$customerId = $_SESSION['userid']; 
$productId = $_POST['productId'];

$favoriteHandler->setFavorite($customerId,$productId);

echo "<button type='button' id='favoriteBtn' class='btn favBtnSelected btn-primary' data-id='" . $productId . "'>Remove Favorite</button>"
        . "<button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>";