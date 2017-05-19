<?php

//require_once 'system_core/init.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/new/fashionboutique/system_core/init.php';

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Token is created using Stripe.js or Checkout!
// Get the payment token submitted by the form:
$token = $_POST['stripeToken'];


//get the rest of the post data

$firstName = sanitize($_POST['firstName']);
$lastName = sanitize($_POST['lastName']);
$fullName = $firstName .' '.$lastName;
$email = sanitize($_POST['email']);
$streetAdd1 = sanitize($_POST['streetAdd1']);
$streetAdd2 = sanitize($_POST['streetAdd2']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zipCode = sanitize($_POST['zipCode']);
$country = sanitize($_POST['country']);

$tax = sanitize($_POST['tax']);
$subTotal = sanitize($_POST['subTotal']);
$grandTotal = sanitize($_POST['grandTotal']);

$cart_id = sanitize($_POST['cart_id']);
$description = sanitize($_POST['description']);
$chargeAmount = number_format($grandTotal, 2) * 100;





//meta data to store to stripe
$metadata = array(
  'cart_id' => $cart_id,
  'tax'     => $tax,
  'subTotal'=> $subTotal
);


try{

  // Charge the user's card:
  $charge = \Stripe\Charge::create(array(
  "amount" => $chargeAmount,
  "currency" => CURRENCY,
  "description" => $description,
  "source" => $token,
  "receipt_email" => $email,
  "metadata" => $metadata,
  ));

     //update inventory in product table

     $cartSql = $dbConnect->query("SELECT * FROM cart WHERE id = '{$cart_id}'");

     $result = mysqli_fetch_assoc($cartSql);

     $item = json_decode($result['item'], true);

     foreach ($item as $tempItem) {
       $newSize = array();
       $itemID = $tempItem['id'];
       $productSql = $dbConnect->query("SELECT * FROM product WHERE id = '{$itemID}'");
       $product = mysqli_fetch_assoc($productSql);
       $sizes = sizeToArray($product['sizes']);
       foreach ($sizes as $tempSize) {
         if($tempSize['size'] == $tempItem['size']){
           $quantity = $tempSize['quantity'] - $tempItem['quantity'];
          $newSize[] = array('size'=> $tempSize['size'], 'quantity'=>$quantity);
        } else {
          $newSize[] = array('size'=> $tempSize['size'], 'quantity'=>$tempSize['quantity']);
        }
      }

      $sizeString = sizeToString($newSize);

      $dbConnect->query("UPDATE product SET sizes = '{$sizeString}' WHERE id = '{$itemID}'");

    }




  $dbConnect->query("UPDATE cart SET paid = 1 WHERE id = '{$cart_id}'");

  $txn_db_record_sql = "INSERT INTO transaction (chargeID, cart_id, fullName, email, streetAdd1, streetAdd2, city, state, zipCode, country, subTotal, tax, grandTotal, description, txnType)
  VALUES('$charge->id', '$cart_id', '$fullName', '$email', '$streetAdd1', '$streetAdd2', '$city', '$state', '$zipCode', '$country', '$subTotal', '$tax', '$grandTotal', '$description', '$charge->object')";
  $dbConnect->query($txn_db_record_sql);


  $domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST'] : false;
  //destroy/unset cookie
  setcookie(CART_COOKIE, '', 1, '/', $domain, false);

  include 'include/head.php';
  include 'include/nav.php';
  include 'include/header_trimmed.php';
?>


<h1 class="text-center text-success"> Thank You </h1>
<p> You cart has been successfully charge. <?=dollar($grandTotal);?> You have receive a receipt. Please check you spam folder if it is not in your inbox. Additionaly you can also print this page as receipt. </p>
<p> You receipt number is: <strong> <?=$cart_id;?> </strong> </p>
<p>You order will be ship to the address below: </p>
<address class="">
  <?=$fullName;?> <br>
  <?=$streetAdd1;?> <br>
  <?=(($streetAdd2!='')?$streetAdd2.'<br>':'');?>
  <?=$city.', '.$state;?> <br>
  <?=$zipCode;?> <br>
  <?=$country;?> <br>
</address>
<?php


  include 'include/footer.php';


} catch(\Stripe\Error\Card $e){
  //the card has beeb declined
  echo $e;
}




 ?>
