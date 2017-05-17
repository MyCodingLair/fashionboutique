<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/new/oldpcstuffshop/system_core/init.php';

echo $product_id = sanitize($_POST['product_id']);
echo $qty = sanitize($_POST['qty']);
echo $available = sanitize($_POST['available']);


$item = array();
$item[] = array(
  'id' => $product_id,
  'quantity' => $qty,
  'available' => $available,
);

//$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
$domain = false;

$query = $dbConnect->query("SELECT * FROM product WHERE id = '$product_id'");

$product = mysqli_fetch_assoc($query);

$_SESSION['success_msg'] = $product['title']. ' has been added to your cart.';

//check if the cart cookies exist
if($cart_id != ''){  //if the cart is not empty and there already exist item in the cart
  $cartSql = "SELECT * FROM cart WHERE id = '$cart_id'";
  $cartResult = $dbConnect->query($cartSql);
  $cart = mysqli_fetch_assoc($cartResult);
  $previousItem = json_decode($cart['item'], true);
  $itemMatch = 0;  //initialize
  $newItem = array();  //declare new array to hold the new items
  foreach ($previousItem as $pItem) {
    if($item[0]['id'] == $pItem['id'] && $item[0]['size'] == $pItem['size']){   //check if them item to be inserted to the cart is the same item/product
      $pItem['quantity'] = $pItem['quantity'] + $item[0]['quantity'];  //if the same product, just add the quantity of the product
      if($pItem['quantity'] > $available){  //double if the quantity added is more than the quantity available  ******************************======check back this logic later
        $pItem['quantity'] = $available;   //if the quantity addded is more than available just assign the available value to the quantity
      }
      $itemMatch = 1;  //set item match to 1, meaning the item is the same
    }
    $newItem[] = $pItem;  //set the new array $newItem to  $pItem
  }
  if($itemMatch != 1){   //if the new item/product added to the cart is not the same as the item/product already in the cart, 
    $newItem = array_merge($item, $previousItem);  //merge the new item/product with the previous one to the $newItem which will be insert into the DB in the query below
  }
  $items_json = json_encode($newItem);
  $cart_expire = date('Y-m-d H:i:s', strtotime('+30 days'));
  $dbConnect->query("UPDATE cart SET item = '{$items_json}', expireDate = '{$cart_expire}' WHERE id = '{$cart_id}'");
  setcookie(CART_COOKIE, '', 1, '/', $domain, false);  //unset the cookie
  setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);   //set the cookie again


}else {
  //add the cart to DB and set cookie
  $items_json = json_encode($item);
  $cart_expire = date('Y-m-d H:i:s', strtotime('+30 days'));
  //$sql = "INSERT INTO cart (item, expireDate) VALUES('{$items_json}', '{$cart_expire}') ";
  $dbConnect->query("INSERT INTO cart (item, expireDate) VALUES('{$items_json}', '{$cart_expire}') ");
  //$dbConnect->query("INSERT INTO cart (item, expireDate) VALUES('$items_json', '$cart_expire') ");
  $cart_id = $dbConnect->insert_id;  //insert_id is a prebuild function, it will return the last id that is inserted in the DB table
  setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/', $domain, false);    //setcookie() is a php function, it require 5 params,
  //1st param is the cookie name, which we already define as CART_COOKIE in config.php,
  //the 2nd param is the value of the cookie which is $cart_id
  //3rd param is the cookie expiration, which we already define in config.php as CART_COOKIE_EXPIRE
  //4th param is the root '/'
  //5th param is the domain which we already set as $domain in the code above
  //6th param is the security, set it to false so that can access in localhost
}



?>
