<?php
  $dbConnect = mysqli_connect("localhost", "root", "", "fb");

  if(mysqli_connect_errno()){
    echo'Error connecting to Database. ' .mysqli_connect_error();
    die();
  }

session_start();
//The BASE_URL is already define in the config.php
//since this page is already included in the idex.php it will have access to the following code:
require_once $_SERVER['DOCUMENT_ROOT'].'/new/fashionboutique/system_core/config.php';
//require_once 'config.php';
require_once BASE_URL.'helper/helpers.php';
//require_once $_SERVER['DOCUMENT_ROOT'].'/new/fashionboutique/helper/helpers.php';

require BASE_URL. 'vendor/autoload.php';




$cart_id = '';
//check if cookie exist, and asign it to $cart_id, $cart_id coorespond to the id in the DB in the (cart) table, when user log in to site, this will check if the cookies which hold the cart exist, if exist pull data from DB
if(isset($_COOKIE[CART_COOKIE])){
  $cart_id = sanitize($_COOKIE[CART_COOKIE]);
}


if(isset($_SESSION['userID'])){  //the value is set form helpers.php
  $userID = $_SESSION['userID'];

  $sql = "SELECT * FROM users WHERE id = '$userID' ";
  $result = $dbConnect->query($sql);

  $userData = mysqli_fetch_assoc($result);
  $firstName = $userData['firstName'];
  $lastName = $userData['lastName'];

}

if(isset($_SESSION['success_msg'])){  //this $_SESSION['success_msg'] is from helpers.php in the login() function
  echo '<div class="bg-success"> <p class="text-success text-center"> '.$_SESSION['success_msg'].' </p> </div>';
  unset($_SESSION['success_msg']);  //unset the seesion so that when user refresh the page, the message at the to is gone.
}

if(isset($_SESSION['error_msg'])){
  echo '<div class="bg-danger"> <p class="text-danger text-center"> '.$_SESSION['error_msg'].' </p> </div>';
  unset($_SESSION['error_msg']);
}

if(isset($_SESSION['passHavNotChange'])){
  echo '<div class="bg-danger"> <p class="text-danger text-center"> '.$_SESSION['passHavNotChange'].' </p> </div>';
  unset($_SESSION['passHavNotChange']);
}
