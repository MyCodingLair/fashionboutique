<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/new/oldpcstuffshop/system_core/init.php';

if(!is_logged_in()){
  login_error_redirect();
}

include 'include/head.php';
include 'include/nav.php';

if(isset($_GET['restore'])){
  $id_restore = sanitize($_GET['restore']);
  $sqlRestore = "UPDATE product SET archived = '0' WHERE id = '$id_restore'";  //archived = 1, the product is archived.
  $dbConnect->query($sqlRestore);
  header('Location: archived.php');
}


$sqlProduct = "SELECT * FROM product WHERE archived = '1'";
$productResult = $dbConnect->query($sqlProduct);


?>

<h2 class="text-center">Archived Products</h2>
<hr>
<table class="table table-bordered table-condensed table-striped">
  <thead> <th></th> <th>Products</th> <th>Price</th> <th>Categories</th> <th>Featured</th> <th>Sold</th> </thead>
  <tbody>
    <?php $numrow = mysqli_fetch_row($productResult);?>
    <?php if($numrow != 0){ ?>
    <?php while($product = mysqli_fetch_assoc($productResult)):
      $id_categories = $product['categories'];
      //get the category name(sub menu name) from DB
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
        <a href="archived.php?restore2=<?=$product['id'];?>" class="btn btn-xs btn-default"><span>Restore Product</span></a>
        <a href="archived.php?restore=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a>
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

<?php
  }
  else {
    ?>

    <tr>
      <td>
        <a href="index.php" class="btn btn-xs btn-default"><span>Return Home</span></a></td>
      <td>No Archived Product!</td>
      <td>No Archived Product!</td>
      <td>No Archived Product!</td>
      <!--The featured below is the oposite of what in the DB because we want it to toggle when clicked, meaning if in the DB is 0, when clicked it will change to 1 and vice versa-->
      <td>No Archived Product!</td>

      <td>No Archived Product!</td>
    </tr>

  </tbody>

</table>
<div align="center">
  <a href="index.php" class="btn btn-success" id="btn-home">Home</a>
</div>
    <?php
  }

?>


<?php  include 'include/footer.php';?>
