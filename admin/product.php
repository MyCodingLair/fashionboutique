<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/new/oldpcstuffshop/system_core/init.php';
include 'include/head.php';
include 'include/nav.php';

if(!is_logged_in()){
  login_error_redirect();
}



if(isset($_GET['delete'])){
  $id_delete = sanitize($_GET['delete']);
  $sqlDelete = "UPDATE product SET archived = '1' WHERE id = '$id_delete' ";
  $dbConnect->query($sqlDelete);
  header('Location: product.php');
}


$imagePath = '';  //initialize, just for check and debug, maybe can delete later if no further error

if (isset($_GET['add']) | isset($_GET['edit'])) { //if the addProduct button is clicked do the following, else, display the page as usual. The 'add' comes from the <a href="product.php?add=1"> in the add product button
  $brandSql = "SELECT * FROM brand ORDER BY brand";
  $brandResult = $dbConnect->query($brandSql);

  $parentSql = "SELECT * FROM categories WHERE parent = 0 ORDER BY categoryName";
  $parentResult = $dbConnect->query($parentSql);

  $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
  $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):'');
  $parent =((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):'');
  $categories = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):'');
  $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
  $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
  $qty = ((isset($_POST['qty']) && $_POST['qty'] != '')?sanitize($_POST['qty']):'');
  $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');



  $saved_image = '';  //initialize a var to store the location



  if(isset($_GET['edit'])){
    $edit_id = $_GET['edit'];

    $editSql = "SELECT * FROM product WHERE id = '$edit_id'";
    $resultEditSql = $dbConnect->query($editSql);
    $editProductDetails = mysqli_fetch_assoc($resultEditSql);

    //$image_url = $_SERVER['DOCUMENT_ROOT'].$editProductDetails['productImage']; echo $image_url;  //for debug delete later
    if(isset($_GET['delete_image'])){   //if delete image link/button is cliked
      $image_url = $_SERVER['DOCUMENT_ROOT'].$editProductDetails['productImage'];
      unlink($image_url);  //this is a php function to delete the image;
      $dbConnect->query("UPDATE product SET productImage = '' WHERE id = $edit_id");
      header('Location: product.php?edit='.$edit_id);
    }

    $categories = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$editProductDetails['categories']);
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$editProductDetails['title']);
    $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$editProductDetails['brand']);
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$editProductDetails['price']);
    $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):$editProductDetails['listPrice']);
    $qty = ((isset($_POST['qty']) && $_POST['qty'] != '')?sanitize($_POST['qty']):$editProductDetails['quantityAvailable']);
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$editProductDetails['description']);

    //query to get parent for the product from categories
    $parentSqlForEdit = $dbConnect->query("SELECT * FROM categories WHERE id = '$categories'");
    $parentResultForEdit = mysqli_fetch_assoc($parentSqlForEdit);
    $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentResultForEdit['parent']);  //if isset POST 'parent', set parent to that value, else set it this value -> $parentResultForEdit['parent']) which is from the DB
    //var to store the image path of the product image to display later
    $saved_image = (($editProductDetails['productImage']!='')?$editProductDetails['productImage']:'');
    $imagePath = $saved_image;  //set the $imagePath var to this so that if the image is already deleted, an empty string will be set to this var which will be use in the reasign $addProductSql if the edit button is clicked, if the image is not deleted, this will assign the path that is already containt in the DB

  }

  if($_POST){  //if the Add Product form is submitted
    // $title = sanitize($_POST['title']);
    // $brand = sanitize($_POST['brand']);
    // $categories = sanitize($_POST['child']);
    // $price = sanitize($_POST['price']);
    // $list_price = sanitize($_POST['list_price']);
    // $qty = sanitize($_POST['qty']);
    // $description = sanitize($_POST['description']);
    // $parent = sanitize($_POST['parent']);


    $errors = array();  //initialize

    //form validation:
    $required = array('title', 'brand', 'price', 'parent', 'child', 'qty');
    foreach ($required as $field) {
      if($_POST[$field] == ''){
        $errors[] = 'All Fields with * is required!';
        break;
      }
    }
    if(!empty($_FILES)){
      //var_dump($_FILES);  //if we var_dump the picture files, we will get an array with 5 element,
      //the 1st element index[0] is the name of the picture file
      //the 2nd element index[1] is the type/format/extension of the files
      //the 3rd element index[2] is the current temp location of the files
      //the 4th element index[3] is the error if any and is in int. if 0 no errors, 1 has erros
      //the 5th element index[4] is the size of the file in bytes
      $picture = $_FILES['picture'];  //this will assign the array of the files to $picture, so now $picture is an array
      var_dump($picture);
      //file name FILE[0]
      $name = $picture['name']; //assign the name of the picture file to the var $name
      $nameArray = explode('.', $name); //this will explode the file name by the ".", and store it in the $nameArray, so the first element of this array is the name of the picture file, and the second element can be the extension fo the files

      //seperate the file name and extension
      $fileName = $nameArray[0];  //assign the first element of the exploded $nameArray to the var $fileName
      $fileExt = $nameArray[1];   //assign the second element of the exploded $nameArray to the var $fileExt

      //this is to get the file type and the file extension, check using var_dump
      //FILE[1]
      $fileType = explode('/',$picture['type']);   //explode the file with '/', using var_dump we can see the file type is set as 'image/jpeg' which is 'theFileType/theFileExtension'
      $fileTypeName = $fileType[0]; //assign the name of the file type to the var $fileTypeName e.g. image, video, txt, etc
      $fileTypeExt = $fileType[1];  //assign the extension of the file to the var $fileTypeExt e.g. .jpg, .png, .jpeg, .mp4, etc

      //the file temp location
      //FILE[2]
      $tempLoc = $picture['tmp_name'];

      //the size of the file
      //FILE[3]
      $sizeOfFile = $picture['size'];
      $allowedFormat = array('png', 'jpg', 'jpeg', 'gif');  //an array to hold the allowed file format for the picture

      //=======================Change file name, and Upload path============================//
      //$uploadName = md5(microtime()).'.'.$fileExt;
      //change the name of the file to be uploded
      $getBrandName = "SELECT * FROM brand WHERE id = $brand";
      $getCatName = "SELECT * FROM categories WHERE id = $categories";
      $getParent = "SELECT * FROM categories WHERE parent = 0 AND id = $parent";
      $resultBrandName = $dbConnect->query($getBrandName);
      $resultCatName = $dbConnect->query($getCatName);
      $resultParent = $dbConnect->query($getParent);
      if(!($dbConnect->query($getParent))){
        echo $dbConnect->error;
      }
      $uploadBrandName = mysqli_fetch_assoc($resultBrandName);
      $uploadCatName = mysqli_fetch_assoc($resultCatName);
      $uploadParentName = mysqli_fetch_assoc($resultParent);

      $titleUpload = str_replace(' ', '', $title);
      var_dump($titleUpload);

      //$uploadName = $uploadParentName['categoryName'].'-'.$uploadBrandName['brand'].'-'.$uploadCatName['categoryName'].'-'.$getCatName['title'].$fileExt;
      $uploadName = $uploadParentName['categoryName'].'-'.$uploadBrandName['brand'].'-'.$titleUpload.'.'.$fileExt;

      //=======================Change file name, and Upload path============================//
      //check if file is an image
      if($fileTypeName != 'image'){
        $errors[] .= "The files must be an image!";
      }
      //check if the format is an allowed format
      if(!in_array($fileExt, $allowedFormat)){  //search if the $fileExt is within the $allowedFormat array.
        $errors[] .= "The picture must be in the format of '.png', '.jpg', '.jpeg' or '.gif' only!";
      }
      //check for size
      if($sizeOfFile > 5000000){
        $errors[] .= "The file size must be under 5MB!";
      }
      //check for file extension in the file name if it is not the same as the actual file extension
      if($fileExt != $fileTypeExt && ($fileTypeExt == 'jpeg' && $fileExt != 'jpg')){
        $errors[] .= "The file extension does not match the file";
      }

    }
    if(!empty($errors)){
      echo display_errors($errors);
    } else {
      //upload file and insert into DB
      $imagePath = '/new/oldpcstuffshop/images/products/'.$uploadParentName['categoryName'].'/'.$uploadName;  //image path to be store in DB
      $uploadLoc = BASE_URL.'images/products/'.$uploadParentName['categoryName'].'/'.$uploadName; //the location to upload the file

      //check if file path already exist, if not exist create it
      $uploadDir = BASE_URL.'images/products/'.$uploadParentName['categoryName'].'/';
      if(!file_exists($uploadDir)){
        mkdir($uploadDir, 0777, true);
      }
      move_uploaded_file($tempLoc, $uploadLoc);  //upload the file to the specified file path

      $addProductSql = "INSERT INTO product (`title`, `price`, `listPrice`, `brand`, `categories`, `productImage`, `description`, `quantityAvailable`)
      VALUES ('$title', '$price', '$list_price', '$brand', '$categories', '$imagePath', '$description', '$qty')";

      if(isset($_GET['edit'])){ //if the edit button is clicked, update the above sql statement to the following
        $addProductSql = "UPDATE product SET title = '$title', price = '$price', brand = '$brand', categories = '$categories', productImage = '$imagePath', description = '$description', quantityAvailable = '$qty' WHERE id = $edit_id";
      }

      if($dbConnect->query($addProductSql)){
        header('Location: product.php');
      } else{
        echo $dbConnect->error;  //this if else structure is for debuging purpose, delete later.
      }

    }

  }

?>

<!-- ==================================Add Product Form======================================== -->
<!--This part only appear if the add product button is clicked, else, it will display what is below it. The logic for the button is above -->
<h2 class="text-center"> <?=((isset($_GET['add']))?'Add New Product':'Edit Product');?> </h2> <hr>
<!--form for adding new product -->
<form action="product.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post" enctype="multipart/form-data">  <!--the enctype is to upload multiple data, it use to upload images -->
  <!--Below is bootstrap column -->
  <!--Title-->
  <div class="form-group col-md-3" >
    <label for="title">Title*</label> <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>" >
  </div>

  <!--Brand -->
  <div class="form-group col-md-3">
    <label for="brand">Brand*</label> <select class="form-control" id="brand" name="brand">
      <option value="<?=((isset($_POST['brand']) && $_POST['brand'] == '')?' selected':'');?>"></option>   <!--This is to check if after POST if the brand oftion is not selected, then display nothing after POST -->
      <?php while($brandDB = mysqli_fetch_assoc($brandResult)): ?>
        <!--The tenary operator below is to check if after POST the value is set, then display what is set after POST, else if not, display nothing is selected after POST -->
      <option value="<?=$brandDB['id'];?>"<?=(($brand == $brandDB['id'])?' selected':'');?> > <?=$brandDB['brand'];?> </option>   <!--The ternary operator in here is to check if the brand is select during post, and if selected set it to selected so that it will be displayed in the option box after POST-->
      <?php endwhile; ?>
    </select>
  </div>

  <!--Parent -->
  <div class="form-group col-md-3">
    <label for="parent">Parent Category*</label>
    <select class="form-control" id="parent" name="parent">
      <option value=""<?=(($parent=='')?' selected':'');?>></option>
      <?php while($parentDB = mysqli_fetch_assoc($parentResult)): ?>
      <option value="<?=$parentDB['id'];?>" <?=(($parent == $parentDB['id'])?' selected':''); ?> > <?=$parentDB['categoryName'];?> </option>
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
    <label for="price">Price*</label> <input type="text" id="price" name="price" class="form-control" value="<?=$price;?>" >
  </div>
  <div class="form-group col-md-3">
    <label for="list_price">List Price*</label> <input type="text" id="list_price" name="list_price" class="form-control" value="<?=$list_price;?>" >
  </div>

  <!--Quanntity Selector -->
  <div class="form-group col-md-2">
    <label for="qty">Quantity:</label>
    <input type="number" name="qty" id="qty" class="form-control" value="<?=$qty;?>" min="0" >
  </div>

  <!--Product Picture -->
  <div class="form-group col-md-6">
    <?php if($saved_image != ''): ?>
      <label for="picture">Product Picture: </label>
      <div> <img src="<?=$saved_image?>" alt="saved image" width="200px" height="auto"/> </div> <br>
      <a href="product.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete Image</a>
    <?php else: ?>
      <label for="picture">Product Picture: </label>
      <input type="file" name="picture" class="form-control" id="picture" >
    <?php endif; ?>
  </div>


  <!--Discription Textbox-->
  <div class="form-group col-md-6">
    <label for="discription">Discription: </label>
    <textarea name="discription" id="discription" class="form-control" rows="6"> <?=((isset($_POST['discription']))?sanitize($_POST['discription' ]):'');?> </textarea>
  </div>

  <!--Add Product Button-->
  <div class="form-group pull-right">
    <a href="product.php" class="btn btn-default">Cancel</a>
    <input type="submit" value="<?=((isset($_GET['edit']))?'Edit Product':'Add Product');?>" class="btn btn-success"></button>
  </div>

</form>

<!-- ==================================Add Product From======================================== -->


<?php
} else {



$sql = "SELECT * FROM product WHERE archived = 0 ";  // 0 means NOT deleted, and 1 means deleted

$productResult = $dbConnect->query($sql);

if(isset($_GET['featured'])){
  $id = (int)$_GET['id'];
  $featured = (int)$_GET['featured'];

  $featuredSql = "UPDATE product SET featured = '$featured' WHERE id = '$id' ";
  $dbConnect->query($featuredSql);
  header('Location: product.php');
}


?>

<h2 class="text-center">Products</h2>
<a href="product.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a><div class="clearfix"></div>
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
        <a href="product.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
        <a href="product.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
      </td>
      <td><?=$product['title'];?></td>
      <td><?=dollar($product['price']);?></td>
      <td><?=$parent['categoryName']." - ".$categoryName['categoryName'];?></td>
      <!--The featured below is the oposite of what in the DB because we want it to toggle when clicked, meaning if in the DB is 0, when clicked it will change to 1 and vice versa-->
      <td><a href="product.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'];?>"  class="btn btn-xs btn-default">
            <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus');?>"></span>
          </a>&nbsp <?=(($product['featured'] == 1)?'Featured Product':''); //if the product is featured, echo featured product?>  <!--&nbsp stands for non-breaking-space -->
      </td>

      <td></td>
    </tr>
  <?php endwhile; ?>
  </tbody>

</table>


<?php } include 'include/footer.php'?>

<script>
  jQuery('document').ready(function(){
    getChildOption('<?=$categories;?>');  //pass the posted $categories as the parameter for the function, so it will use that in the function definitionn in the footer.php
  });
</script>
