  <?php
  require_once 'system_core/init.php';
  include 'include/head.php';
  include 'include/nav.php';
  include 'include/header.php';
  include 'include/left_side_bar.php';

  $sql = "SELECT * FROM product WHERE featured = 1";  //select the product that has the featured value=1 from the DB to be display in the home page
  $query = $dbConnect->query($sql);
?>

  <!--Main start-->
  <div class="col-md-8">
    <div class="row">
      <h2 class="text-center">Feature Product</h2>
      <!--the statement below is to use php to loop and fetch the data from the DB and stored it in the $featuredProduct variable. The column ":" is to tell it that the statement is not finisht and it will continue in the next php statement-->
      <?php while($featuredProduct = mysqli_fetch_assoc($query)): ?>
        <div class="col-md-3">
          <h4><?php echo $featuredProduct['title'] ?></h4>
          <img src="<?php echo $featuredProduct['productImage'] ?>" alt="<?php echo $featuredProduct['title'] ?>" class="img-thumb"/>
          <p class="list-price text-danger">List Price<s> $<?= $featuredProduct['listPrice']; ?> </s></p>
          <p class="price">Our Price: $<?= $featuredProduct['price']; ?> </p> <!-- the <?="sdfasdfas"?> is short form for ehco -->
          <button type="button" class="btn btn-sm btn-success" onclick="productDetailsFuncModal(<?= $featuredProduct['id']; ?>)">Details</button>
        </div>
      <?php endwhile; ?>

    </div>
  </div>
  <!--Main end-->

<?php
  include 'include/right_side_bar.php';
  include 'include/footer.php';
 ?>
