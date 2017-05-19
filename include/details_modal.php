<!-- Details modal start-->
<!--this is from bootstrap -->
<?php
  require_once '../system_core/init.php';
//since we use method:'post' in the ajax in the productDetailsFuncModal() function, we have access to the POST array
  $id = $_POST['id'];
  $id = (int)$id; //this is to parse the id into an interger, make sure the value that has been pass in an interger
  $sql = "SELECT * FROM product WHERE id = '$id' ";
  $query = $dbConnect->query($sql);
  $productDetails = mysqli_fetch_assoc($query);

  $brand = $productDetails['brand'];

  $sqlBrand = "SELECT * FROM brand WHERE id = '$brand' ";
  $brandQuery = $dbConnect->query($sqlBrand);
  $brandResult = mysqli_fetch_assoc($brandQuery);

  $sizeString = $productDetails['sizes'];
  $sizeArray = explode(',', $sizeString);


?>
<?php
  ob_start(); //open buffer start, this will start a buffer and will read all the line bellow
?>
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" onclick="closeModal()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title text-center"> <?= $productDetails['title']; ?> </h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <span id="modal_errors" class="bg-danger"></span>
            <div class="col-sm-6">
              <div class="center-block">
                <img src="<?= $productDetails['productImage'];?>" alt="<?= $productDetails['title']; ?>" class="details img-responsive"/>
              </div>
            </div>
            <div class="col-sm-6">
              <h4>Details</h4>
              <p> <?= $productDetails['description']; ?> </p>
              <hr>
              <p>Price: $<?= $productDetails['price'];?> </p>
              <p>Brand: <?= $brandResult['brand']; ?> </p>
              <form action="add_cart.php" method="Post" id="add_product_form">

                <input type="hidden" name="product_id" value="<?=$id;?>">


                <div class="form-group">
                  <div class="col-xs-5">
                    <label for="quantity">Quantity: </label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1">
                    <!--Quantity Selector Start-->
                    <!-- <div class="input-group">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>
                      </span>
                      <input type="text" name="quant[1]" class="form-control input-number" value="1" min="1" max="<?= $productDetails['sizes']; ?>">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="quant[1]">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                      </span>
                      <!-- <script src="http://localhost/new/fashionboutique/js/quantityButton.js"></script> -->
                    <!-- </div> -->
                    <!--Quantity Selector End -->
                  </div>
                  <p>Available: </p>

                </div>
                <br><br>
                <div class="form-group col-3">
                  <label for="size">Size: </label>
                  <select name="size" id="size" class="form-control">
                    <?php foreach ($sizeArray as $string) {
                      $stringArray = explode(':', $string);
                      $size = $stringArray[0];
                      $available = $stringArray[1];
                      echo '<option value="'.$size.'" data-available="'.$available.'">'.$size.'('.$available.' Available)</option>';
                    } ?>
                  </select>
                </div>
                <input type="hidden" name="available" id="available" value="<?=$available;?>">
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-default" onclick="closeModal()">Close</button>
        <button class="btn btn-sm btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span> Add To Cart</button>
      </div>
    </div>
  </div>
</div>

<?php


?>

<!--Script for the quantity selector button Start here -->

<!-- <script>
//plugin bootstrap minus and plus
//http://jsfiddle.net/laelitenetwork/puJ6G/
$('.btn-number').click(function(e){
    e.preventDefault();

    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {

            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {

    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());

    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }


});
$(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

</script> -->

<!--Script for the quantity selector button End here -->


<script>  //script to hold the function to close the modal when the close button is clicked

  // jQuery('#size').change(function(){  //listen to the <select id="sizes"> element and get the data-available attribute value from that element and assign it to 'var available'
  //   var available = jQuery('#size option:selected').data("available");
  //   jQuery('#available').val(available);  //use the assigned var available value and set it to be the value of the element <input id="available">
  // });




  function closeModal(){
    //jquery
    $('#details-modal').modal('hide'); // the modal('close') function is from bootsrap
    //setTimeout() is js function, it require 2 parameters, the first parameter is what to do - we could create a function, the second parameters is time in milisecond to wait
    //basically this is to create a time interval before the executing the next code
    setTimeout(function(){
      //jquery,
      $('#details-modal').remove();  //this will remove the entire <div details-modal> from the append
      $('.modal-backdrop').remove();  //this will remove the dark overlay of the modal, just for safety reason if it doesn't remove.
    } ,500);
  }
</script>

<?php
  echo ob_get_clean(); //this will echo and clear the buffer
?>
