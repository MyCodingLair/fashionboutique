</div>
<!--container end-->

<footer class="text-center" id="footer">&copy; Copyright 2017 MyCodingLair</footer>


<script>
  //for scrolling and allowing the logotext to scroll and remain at center but going toward down of the page until the end of the background image end
  jQuery(window).scroll(function(){
    var vscroll = jQuery(this).scrollTop();
    jQuery('#logotext').css({
      "transform" : "translate(0px, "+vscroll/2+"px)"
    });
  });

  //this funtion will pull the data from /details_modal.php and display it on /index.php when the details button of the product i clicked
  //the detail_modal.php page itself is also dynamic
  function productDetailsFuncModal(id){
    var dataToPost = {"id":id};  //this is a JSON string, the id is pass down from the productDetailsFuncModal(id) function parameter
    jQuery.ajax({
      //url: <?=BASE_URL; ?>+'include/details_modal.php',    //also can be writen as:-> url: "http://localhost/new/oldpcstuffshop/include/details_modal.php",
      url: "/new/oldpcstuffshop/include/details_modal.php",
      method:"post",
      data: dataToPost, //the data to post to the page
      success: function(data){   //if success perform the following
        $('body').append(data);  //this will append what is return from the success function after the <body> in the index.php
        jQuery("#details-modal").modal('toggle');  //the modal() method is from boostrap, this will toggle the <div id="details-modal> in the details_modal.php page"
      },
      error: function(){  //if error perform the following
        alert("Error! Unable to retrieve AJAX!");
      }

    });

    //alert(dataToPost.id);
    //alert(id);
  }


//=====================================add_to_cart() function ===============================
  function add_to_cart(){
    jQuery('#modal_errors').html('');
    
    var qty = jQuery('#qty').val();
    var available = jQuery('#available').val();
    var error = '';
    var data = jQuery('#add_product_form').serialize();
    if(qty == '' || qty == 0){
      error += '<p class="text-danger text-center">You must chooose quantity</p>' ;
      jQuery('#modal_errors').html(error);
      return;
    } 
    else{
      jQuery.ajax({
        url : '/new/oldpcstuffshop/admin/parser/add_cart.php',
        method : 'post',
        data : data,
        success : function(){
          location.reload();    
        },
        error : function(){alert("Something went wrong!");}
      });
    }

  }


  //this function is use in cart.php for the + and = button to update the quantity in the cart
  function updateCart(mode, edit_id, edit_qty){
    var data = {'mode':mode, 'edit_id':edit_id, 'edit_qty':edit_qty};
    jQuery.ajax({
      url     : '/new/oldpcstuffshop/admin/parser/update_cart.php',
      method  : 'post',
      data    : data,
      success : function(){ location.reload(); },
      error   : function(){alert("Something went wrong with the update_cart ajax call!");},
    });

  }






</script>
</body>

</html>
