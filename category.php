<?php
require_once 'system_core/init.php';
include 'include/head.php';
include 'include/nav.php';
include 'include/header_trimmed.php';
include 'include/left_side_bar.php';

if(isset($_GET['cat'])){
  $cat_id = sanitize($_GET['cat']);
}else{
  $cat_id = '';
}

$sql = "SELECT * FROM product WHERE categories = '$cat_id' ";  //select the cate
$query = $dbConnect->query($sql);

//sql for joining two seperate table (parentMenu) & (childMenu):
//"SELECT parent.id AS 'parendID', parent.menuName AS 'parentName', child.id AS 'childID', child.subMenuName AS 'childName' FROM childmenu child INNER JOIN parentmenu parent ON child.parentID = parent.id ORDER BY parentID "

//sql from curtis:
//"SELECT p.id AS 'pid', p.categoryName AS 'parent', c.id AD 'cid', c.categoryName AS 'child' FROM categories child INNER JOIN categories p ON c.parent = p.id WHERE c.id = '$cat_id'";

//my code in the helpers.php function getCategory();
// "SELECT parent.id AS 'parendID', parent.categoryName AS 'parentName', child.id AS 'childID', child.categoryName AS 'childName' FROM categories child INNER JOIN categories parent ON child.parent = parent.id WEHRE child.id = '$cat_id' ";

$category = getCategory($cat_id);  //getCategory() function is in the helpers.php

//var_dump($category);

?>


<!--Main start-->
<div class="col-md-8">
  <div class="row">
    <h2 class="text-center"> <?=$category['parentName']. ' - ' .$category['childName'];?> </h2>
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
