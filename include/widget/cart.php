<h3 class="text-center">Shopping Cart</h3>
<div class="">
  <?php if(empty($cart_id)): ?>

      <p id="cart_msg" class="text-center">Your Sopping Cart is empty</p>
      <hr>

  <?php else:

    $cartSql = $dbConnect->query("SELECT * FROM cart WHERE id = '{$cart_id}'");

    $result = mysqli_fetch_assoc($cartSql);

    $item = json_decode($result['item'], true);
    $subTotal = 0;

    ?>

    <table class="table table-condensed" id="cart_widget">
      <thead>
        <tr>
          <th>Qty</th> <th>Item</th> <th>Price</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($item as $tempItem):
          $productSql = $dbConnect->query("SELECT * FROM product WHERE id = '{$tempItem['id']}'");
          $product = mysqli_fetch_assoc($productSql);
        ?>
        <tr>
          <td><?=$tempItem['quantity'];?></td>
          <td><?=substr($product['title'], 0, 15);?></td> <!--substr() is a php funtion to display a string based on specified string lenght, 3 aprams, 1st param is the string $var, 2nd param is at what index of the string to start ie if 0 start from the begining if 5 start from the 6th element, 3 param is the max lenght of the string we want -->
          <td> <?=$tempItem['quantity'] * $product['price'];?> </td>
        </tr>
        <?php
          $subTotal += dollar(($tempItem['quantity'] * $product['price']));
          endforeach;
        ?>
        <tr>
          <td></td> <td>Sub Total:</td> <td><?=dollar($subTotal);?></td>
        </tr>
      </tbody>

    </table>
    <a href="cart.php" class="btn btn-xs btn-primary pull-right">View Cart</a>
    <div class="clearfix"></div>




  <?php endif; ?>

</div>
