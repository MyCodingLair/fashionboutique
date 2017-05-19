<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/new/fashionboutique/system_core/init.php';

$mode = sanitize($_POST['mode']);
$edit_id = sanitize($_POST['edit_id']);
$edit_size = sanitize($_POST['edit_size']);

$cartSql = $dbConnect->query("SELECT * FROM cart WHERE id = '{$cart_id}'");   //$cart_id is from the init.php which is set by the cookies
$result = mysqli_fetch_assoc($cartSql);

$item = json_decode($result['item'], true);

$updatedItem = array();

$domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);

if($mode == 'substract1'){
  foreach ($item as $tempItem) {
    if($tempItem['id']==$edit_id && $tempItem['size']==$edit_size){
      $tempItem['quantity'] -= 1;
    }
    if($tempItem['quantity'] > 0){
      $updatedItem[] = $tempItem;
    }
  }
}

if($mode == 'add1'){
  foreach ($item as $tempItem) {
    if($tempItem['id']==$edit_id && $tempItem['size']==$edit_size){
      $tempItem['quantity'] += 1;
    }
    $updatedItem[] = $tempItem;
  }
}

if(!empty($updatedItem)){
  $jsonUpdated = json_encode($updatedItem);
  $dbConnect->query("UPDATE cart SET item = '{$jsonUpdated}' WHERE id = '{$cart_id}'");
  $_SESSION['success_msg'] = "Shopping Cart Updated.";
}
if(empty($updatedItem)){
  $dbConnect->query("DELETE FROM cart WHERE id = '{$cart_id}'");
  setcookie(CART_COOKIE, '', 1, '/', $domain, false);
}


?>
