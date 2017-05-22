<?php
  $cat_id = (isset($_REQUEST['cat']))?sanitize($_REQUEST['cat']):'';
  $priceSort = ((isset($_REQUEST['priceSort']))?sanitize($_REQUEST['priceSort']):'');
  $minPrice = ((isset($_REQUEST['minPrice']))?sanitize($_REQUEST['minPrice']):'');
  $maxPrice = ((isset($_REQUEST['maxPrice']))?sanitize($_REQUEST['maxPrice']):'');

  $brand = ((isset($_REQUEST['brand']))?sanitize($_REQUEST['brand']):'');

  $brandSql = $dbConnect->query("SELECT * FROM brand ORDER BY brand");
?>

<h3 class="text-center">Search By:</h3>

<h4 class="text-center">Price</h4>
<form action="search.php" method="post">
  <input type="hidden" name="cat" value="<?=$cat_id;?>">
  <input type="hidden" name="priceSort" value="0">
  <input type="radio" name="priceSort" value="low" <?=(($priceSort == 'low')?' checked':'');?> >Low to High <br>
  <input type="radio" name="priceSort" value="low" <?=(($priceSort == 'high')?' checked':'');?> >High to Low <br><br>
  <input type="text" name="minPrice" class="priceRange" placeholder="Min $" value="<?=$minPrice;?>">To
  <input type="text" name="maxPrice" class="priceRange" placeholder="Max $" value="<?=$maxPrice;?>"> <br><br>

  <h4 class="text-center">Brand</h4>
  <input type="radio" name="brand" value="" <?=(($brand == '')?' checked':'');?> > All <br>
  <?php while($brandResult = mysqli_fetch_assoc($brandSql)): ?>
    <input type="radio" name="brand" value="<?=$brandResult['id'];?>" <?=(($brand == $brandResult['id'])?' checked':'');?> > <?=$brandResult['brand'];?> <br>
  <?php endwhile; ?>

  <input type="submit" value="search" class="btn btn-xs btn-primary">

</form>
