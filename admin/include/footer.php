</div>
<!--container end-->

<footer class="text-center" id="footer">&copy; Copyright 2017 MyCodingLair</footer>

<script>
  jQuery('document').ready(function(){
    getChildOption('<?=$categories;?>');  //pass the posted $categories which is from product.php as the parameter for the function, so it will use that in the function definitionn in the footer.php
  });
  //funciton for the update sizez button in the modal for the sizes
  // function updateSizes(){
  //   var sizeString = "";
  //   for(var i=1; i<=12; i++){
  //     if(jQuery('#size'+i).val() != ''){
  //       sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';
  //     }
  //     jQuery('#sizesQtyPreview').val(sizeString);
  //   }
  // }
  //js style
  //  function getChildOption(){
  //    var parentID = document.getElementById('#parent');
  //     var btn = document.getElementById('#btn');
   //
  //     btn.addEventListender('click', function(){
  //       var pullData = new XMLHttpRequest();
  //       pullData.opent('GET', 'http://localhost/new/oldpcstuffshop/admin/json/json_object.json');
  //     });
  //   }

  //jQuery style
  function getChildOption(selected){
    if(typeof selected === 'undefined'){
      var selected = '';
    }
    var parentID = jQuery('#parent').val();  //get the parent id

    jQuery.ajax({   //make an ajax request to the child_categories.php page
      url: '/new/oldpcstuffshop/admin/parser/child_categories.php',
      type: 'POST',   //POST the data to the child_categories.php page
      data: {parentID : parentID, selected : selected},  //ceate a data object id = parentID, the parentID is from above and is from the parent id in the <select><option>
                                //new selected:selected, postKey:value, the value which is selected is being pass from the function parameter and is set in according to the if() statement if it is null
      success: function(data){
        jQuery('#child').html(data); //insert into html into the element with id of 'child, the data we get is from the ajax request which is from the child_categories.php buffer release
      },
      error: function(){alert("Something went wrong with the child option!")}
    });

  }
  jQuery('select[name="parent"]').change(function(){
    getChildOption();
  });  //this is to listen to the change made <select name="parent"> selector and then perform the function of getChildOption which is already specified above

</script>
</body>

</html>
