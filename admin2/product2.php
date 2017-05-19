<?php

//this admin2 folder is using the parentmenu and child menu in the DB, the index.php page is not change yet
require_once $_SERVER['DOCUMENT_ROOT']. '/new/oldpcstuffshop/system_core/init.php';
include 'include/head2.php';
include 'include/nav2.php';

if (isset($_GET['add'])) { //if the addProduct button is clicked do the following, else, display the page as usual. The 'add' comes from the <a href="product2.php?add=1"> in the add product button
$brandSql = "SELECT * FROM brand ORDER BY brand";
$brandResult = $dbConnect->query($brandSql);

$parentSql = "SELECT * FROM parentmenu ORDER BY menuName";
$parentResult = $dbConnect->query($parentSql);

?>

<h2 class="text-center">Add New Product</h2> <hr>
<!--form for adding new product -->
<form action="product2.php?add=1" method="post" enctype="multipart/form-data">  <!--the enctype is to upload multiple data, it use to upload images -->
  <!--Below is bootstrap column -->
  <div class="form-group col-md-3" >
    <label for="title">Title*</label> <input type="text" name="title" class="form-control" id="title" value="<?=((isset($_POST['title']))?sanitize($_POST['title']):'');?>" >
  </div>
  <!--Brand -->
  <div class="form-group col-md-3">
    <label for="brand">Brand*</label> <select class="form-control" id="brand" name="brand">
      <option value="<?=((isset($_POST['brand']) && $_POST['brand'] == '')?' selected':'');?>"></option>   <!--This is to check if after POST if the brand oftion is not selected, then display nothing after POST -->
      <?php while($brand = mysqli_fetch_assoc($brandResult)): ?>
        <!--The tenary operator below is to check if after POST the value is set, then display what is set after POST, else if not, display nothing is selected after POST -->
      <option value="<?=$brand['id'];?>"<?=((isset($_POST['brand']) && $_POST['brand'] == $brand['id'])?' selected':'');?> > <?=$brand['brand'];?> </option>   <!--The ternary operator in here is to check if the brand is select during post, and if selected set it to selected so that it will be displayed in the option box after POST-->
      <?php endwhile; ?>
    </select>
  </div>
  <!--Parent -->
  <div class="form-group col-md-3">
    <label for="parent">Parent Category*</label>
    <select class="form-control" id="parent" name="parent">
      <option value=""></option>
      <?php while($parent = mysqli_fetch_assoc($parentResult)): ?>
      <option value="<?=$parent['id'];?>" <?=((isset($_POST['parent']) && ($_POST['parent'] == $parent['id']))?' seleceted':''); ?> > <?=$parent['menuName'];?> </option>
      <?php endwhile; ?>
    </select>
  </div>
  <!--Child -->
  <div class="form-group col-md-3">
    <label for="child">Child Category*</label>
    <select class="form-control" id="child" name="child"></select>
  </div>

  <!--Price -->
  <div class="form-group col-md-3">
    <label for="price">Price*</label> <input type="text" id="price" name="price" class="form-control" value="<?=((isset($_POST['price']))?sanitize($_POST['price']):'');?>" >
  </div>
  <div class="form-group col-md-3">
    <label for="list_price">List Price*</label> <input type="text" id="list_price" name="list_price" class="form-control" value="<?=((isset($_POST['list_price']))?sanitize($_POST['list_price']):'');?>" >
  </div>

  <!--Quanntity & Sizes Button -->
  <div class="form-group col-md-3">
    <label>Quantity & Sizes*</label>
    <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Quantity & Sizes</button>
  </div>
  <!--Sizes-->
  <div class="form-group col-md-3">
    <label>Sizes & Quantity Previewy</label>
    <input type="text" name="sizesQtyPreview" class="form-control" id="sizesQtyPreview" value="<?=((isset($_POST['sizes']))?sanitize($_POST['sizes']):'');?>" >
  </div>
  <!--Product Picture -->
  <div class="form-group col-md-6">
    <label for="picture">Product Picture: </label>
    <input type="file" name="picture" class="form-control" id="picture" class="form-control">
  </div>
  <!--Discription Textbox-->
  <div class="form-group col-md-6">
    <label for="discription">Discription: </label>
    <textarea name="discription" id="discription" class="form-control" rows="6"> <?=((isset($_POST['discription']))?sanitize($_POST['discription' ]):'');?> </textarea>
  </div>

  <!--Add Product Button-->
  <div class="form-group pull-right">
    <input type="submit" value="Add Product" class="btn btn-success form-control"></button>
  </div>

  <!-- Modal for the quantity and sizes. This is from getbootsrap.com -->
  <div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="sizesModalLabel">Sizes & Quantity</h4>
        </div>
        <div class="modal-body"> <!--This is the part that display in the modal pop up box -->
          <div class="container-fluid"> <!--this div is just to make it in the middle of the box, the container-fluid class is from bootsrap -->
            <?php for($i=1; $i<=12; $i++):?>
            <div class="form-group col-md-4">
              <label for="size<?=$i;?>">Size:</label>
              <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" class="form-control" value="" >
            </div>

            <div class="form-group col-md-2">
              <label for="qty<?=$i;?>">Quantity:</label>
              <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" class="form-control" value="" min="0" >
            </div>
            <?php endfor; ?>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
        </div>
      </div>
    </div>
  </div>



</form>


<?php
} else {



$sql = "SELECT * FROM product WHERE deleted = 0 ";  // 0 means NOT deleted, and 1 means deleted

$productResult = $dbConnect->query($sql);

if(isset($_GET['featured'])){
  $id = (int)$_GET['id'];
  $featured = (int)$_GET['featured'];

  $featuredSql = "UPDATE product SET featured = '$featured' WHERE id = '$id' ";
  $dbConnect->query($featuredSql);
  header('Location: product2.php');
}


?>

<h2 class="text-center">Products</h2>
<!--Add product button -->
<a href="product2.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed table-striped">
  <thead> <th></th> <th>Products</th> <th>Price</th> <th>Categories</th> <th>Featured</th> <th>Sold</th> </thead>
  <tbody>
    <?php while($product = mysqli_fetch_assoc($productResult)):
      $id_categories = $product['categories'];
      //get the category anme(sub menu name) from DB
      $sqlCategories = "SELECT * FROM categories WHERE id = '$id_categories' ";
      $categoriesResult = $dbConnect->query($sqlCategories);
      $categoryName = mysqli_fetch_assoc($categoriesResult);
      $parentID = $categoryName['parent'];
      //get the parent name from DB
      $sqlParent = "SELECT * FROM categories WHERE id = '$parentID' ";
      $parentResult = $dbConnect->query($sqlParent);
      $parent = mysqli_fetch_assoc($parentResult);
      ?>
    <tr>
      <td>
        <a href="product2.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
        <a href="product2.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
      </td>
      <td><?=$product['title'];?></td>
      <td><?=dollar($product['price']);?></td>
      <td><?=$parent['categoryName']." - ".$categoryName['categoryName'];?></td>
      <!--The featured below is the oposite of what in the DB because we want it to toggle when clicked, meaning if in the DB is 0, when clicked it will change to 1 and vice versa-->
      <td><a href="product2.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'];?>"  class="btn btn-xs btn-default">
            <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus');?>"></span>
          </a>&nbsp <?=(($product['featured'] == 1)?'Featured Product':''); //if the product is featured, echo featured product?>  <!--&nbsp stands for non-breaking-space -->
      </td>

      <td></td>
    </tr>
  <?php endwhile; ?>
  </tbody>

</table>


<?php } include 'include/footer2.php'?>
