<?php

require '/system_core/init.php';
include 'include/head.php';
include 'include/nav.php';
include 'include/header_trimmed.php';

$clientToken = Braintree_ClientToken::generate();


if($cart_id != ''){   //if $cart_id is not empty meaning if the cookie is set because $cart_id is set by the cookie in init.php and config.php
  $cartSql = $dbConnect->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
  $result = mysqli_fetch_assoc($cartSql);
  $item = json_decode( $result['item'], true );   //json_decode() is a php funtion to decode a json string, 1st param is the json string, the 2nd param is true/false, true will force the it to return an assoc array/.

  // foreach ($item as $tempItem) {
  //   $itemID = $tempItem['id'];
  //   $itemQty = $tempItem['quantity'];
  //   $itemSize = $tempItem['size'];
  // }
  // var_dump($itemID);

  //$productID = $item[0]['id'];

  $i = 1;
  $subTotal = 0;
  $itemCount = 0;

  // $productSql = $dbConnect->query("SELECT * FROM product WHERE id = '{$item['id']}'");
  //
  // $productDetails = mysqli_fetch_assoc($productSql);


}

?>

<div class="col-md-12">
  <div class="row">
    <h2 class="text-center">My Shopping Cart</h2><hr>
    <?php if($cart_id == ''): ?>
      <div class="bg-danger">
        <p class="text-center text-danger">Your shopping cart is empty.</p>
      </div>
      <div class="text-center"> <a href="index.php" class="btn btn-primary">Continue Shopping</a> </div>
    <?php else: ?>
      <table class="table table-bordered table-condensed table-striped">
        <thead>
          <th>#</th> <th>Item</th> <th>Price</th> <th>Quantity</th>  <th>Sub-total</th>
        </thead>
        <tbody>
          <?php
          foreach ($item as $tempItem) {
            $itemID = $tempItem['id'];

            $productSql = $dbConnect->query("SELECT * FROM product WHERE id = '{$itemID}'");

            $productDetails = mysqli_fetch_assoc($productSql);

            $available = $productDetails['quantityAvailable'];

         
            ?>
            <!--Display table content (cart item)-->
            <tr>
              <td><?=$i;?></td>
              <td> <?=$productDetails['title'];?> </td>
              <td> <?=dollar($productDetails['price']);?> </td>
              <td> 
                <button class="btn btn-xs btn-default" onclick="updateCart('substract1', '<?=$productDetails['id'];?>', '<?= $tempItem['quantity'];?>');">-</button>
                 <?=$tempItem['quantity'];?>
                 <?php if($tempItem['quantity'] < $available):?>
                <button class="btn btn-xs btn-default" onclick="updateCart('add1', '<?=$productDetails['id'];?>', '<?= $tempItem['quantity'];?>');">+</button>
                 <?php else:?>
                <span class="text-danger">Max available reach.</span>
                 <?php endif;?>
              </td>
              <td> <?=dollar($tempItem['quantity'] * $productDetails['price']);?> </td>
            </tr>

          <?php
            $i++;
            $itemCount += $tempItem['quantity'];
            $subTotal += $tempItem['quantity'] * $productDetails['price'];
          }  // closing of foreach ($item as $tempItem)

          $tax = TAXRATE * $subTotal;
          $tax = number_format($tax, 2);  //number_format() is a php function, 1st param the var to format, 2nd param is the decimal place
          $grandTotal = $tax + $subTotal;

          ?>

        </tbody>
      </table>

    <table class="table table-bordered table-condensed text-right">
      <legend>Totals:</legend>
      <thead class="totals-table-header">
        <th>Total items</th> <th>Sub Total</th> <th>Tax</th> <th>Grand Total</th>
      </thead>
      <tbody>
        <tr>
          <td><?=$itemCount;?></td>
          <td> <?=dollar($subTotal);?> </td>
          <td> <?=dollar($tax);?> </td>
          <td><?=dollar($grandTotal);?></td>
        </tr>
      </tbody>

    </table>

    <!--Checout Button trigger modal from bootsrap-->
    <button type="button" class="btn btn-primary btn-md pull-right" data-toggle="modal" data-target="#checkoutModal">
      <span class="glyphicon glyphicon-shopping-cart"></span> Check out >>
    </button>

    <!-- Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
          </div>
          <div class="modal-body">

            <div class="row">

              <form action="thankyou.php" method="post" id="payment-form">


                <input type="hidden" name="tax" value="<?=$tax;?>">
                <input type="hidden" name="subTotal" value="<?=$subTotal;?>">
                <input type="hidden" name="grandTotal" value="<?=$grandTotal;?>">
                <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
                <input type="hidden" name="description" value="<?=$itemCount.' item'.(($itemCount>1)?'s':'').' from OldPcStuffShop.';?>">



                <!--Error Message -->
                <span class="bg-danger" id="payment-errors"></span>
                <!--Shipping Address -->
                <div id="step1">
                  <div class="form-group col-md-6">
                    <label for="firstName">First Name:</label>
                    <input type="text" class="form-control" name="firstName" id="firstName">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="lastName">Last Name:</label>
                    <input type="text" class="form-control" name="lastName" id="lastName">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" name="email" id="email">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="streetAdd1">Street Address :</label>
                    <input type="text" class="form-control" name="streetAdd1" id="streetAdd1">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="streetAdd2">Street Address 2:</label>
                    <input type="text" class="form-control" name="streetAdd2" id="streetAdd2">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="city">City:</label>
                    <input type="text" class="form-control" name="city" id="city">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="state">State:</label>
                    <input type="text" class="form-control" name="state" id="state">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="zipCode">Zip Code:</label>
                    <input type="text" class="form-control" name="zipCode" id="zipCode">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="country">Country:</label>
                    <input type="text" class="form-control" name="country" id="country">
                  </div>
                </div>

                <!--Card Info-->
                <div id="step2" >
                  <div class="form-group col-md-3">
                    <label for="nameOnCard">Name On Card:</label>
                    <input type="text" id="nameOnCard" class="form-control" data-stripe="name">
                  </div>
                  <div class="form-group col-md-3">
                    <label for="cardNum">Credit Card Number:</label>
                    <input type="text" id="cardNum" class="form-control" data-stripe="number">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="cvc">CVC</label>
                    <input type="text" id="cvc" class="form-control" data-stripe="cvc">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="cardExpireMonth">Expire Month</label>
                    <select class="form-control" id="cardExpireMonth" data-stripe="exp_month">
                      <option value=""></option>
                      <?php for($i=1; $i<13; $i++): ?>
                        <option value="<?=$i;?>"> <?=$i;?> </option>
                      <?php endfor; ?>
                    </select>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="cardExpireYear">Expire Year</label>
                    <select class="form-control" id="cardExpireYear" data-stripe="exp_year">
                      <option value=""></option>
                      <?php $year = date('Y'); ?>
                      <?php for($i=0; $i<11; $i++): ?>
                        <option value="<?=$year+$i;?>"> <?=$year+$i;?> </option>
                      <?php endfor; ?>
                    </select>
                  </div>
                </div>
            </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="checkAddress();" id="nextBtn">Next</button>
              <button type="button" class="btn btn-primary" onclick="backAddress();" id="backBtn">Back</button>
              <button type="submit" class="btn btn-primary" id="checkOutBtn">Check Out</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--Checout Button trigger modal from bootsrap-->

<form>
  <div id="dropin-container"></div>
</form>

<h3>Payment</h3>
<hr>
<div id="payment">

</div>


    <?php endif; ?>
  </div>
</div>



<script>

function backAddress(){
  jQuery('#payment-errors').html('');
  jQuery('#step1').css({'display':'block'});
  jQuery('#step2').css({'display':'none'});
  jQuery('#nextBtn').css({'display':'inline-block'});
  jQuery('#backBtn').css({'display':'none'});
  jQuery('#checkOutBtn').css({'display':'none'});
  jQuery('#checkoutModalLabel').html("Shipping Address");

}

function checkAddress(){

  var data = {
    'firstName' : jQuery('#firstName').val(),
    'lastName'  : jQuery('#lastName').val(),
    'email'     : jQuery('#email').val(),
    'streetAdd1': jQuery('#streetAdd1').val(),
    'streetAdd2': jQuery('#streetAdd2').val(),
    'city'      : jQuery('#city').val(),
    'state'     : jQuery('#state').val(),
    'zipCode'   : jQuery('#zipCode').val(),
    'country'   : jQuery('#country').val(),
  };

  jQuery.ajax({
    url     : '/new/oldpcstuffshop/admin/parser/check_address.php',
    method  : 'POST',
    data    : data,
    success : function(data){  //the data in this function is return form the check_address.php it is not the same as the data above
      if(data != 'passed'){
        jQuery('#payment-errors').html(data);  //grab the element by id , the <span id='payment-errors'> and then attach the html method and passed it the 'data' which is from the check_address.php to the <span id='payment-errors'>
      }
      if(data == 'passed'){
        jQuery('#payment-errors').html('');   //clear the <span id="payment-errors">
        //change the css style to display <div id="step2"> and not display <div id="step1"> and also display and not display certain button
        jQuery('#step1').css({'display':'none'});
        jQuery('#step2').css({'display':'block'});
        jQuery('#nextBtn').css({'display':'none'});
        jQuery('#backBtn').css({'display':'inline-block'});
        jQuery('#checkOutBtn').css({'display':'inline-block'});
        jQuery('#checkoutModalLabel').html("Credit Card Info");

      }
    },
    error   : function(){alert("Something went wrong with the ajax call to check_address.php!");},
  });
}






</script>






<?php include 'include/footer.php';?>
