<!--  This is to store the navigation of the page,
  the the navigation bar at the top of the page build using bootstrap will be placed here
-->
<?php
  $sql = "SELECT * FROM categories WHERE parent=0";  //this wll query the DB for all the row that has the "parent" value = 0, all parent for the menu category has the value of 0.
  $parentQuery = $dbConnect->query($sql);
?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <a href="index.php" class="navbar-brand">Old Pc Stuff Shop Admin Site</a>
    <ul class="nav navbar-nav">
      <!-- Menu Item -->
      <li> <a href="brand.php">Brands</a></li>
      <li> <a href="categories.php">Categories</a></li>
      <li> <a href="product.php">Products</a></li>
      <li> <a href="archived.php">Archived Products</a></li>
      <li> <a href="users.php">Users</a></li>
      
      

      <!-- Menu dropdown start -->
      <!-- <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parentMenu['categoryName'];  ?><span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#"></a></li>
        </ul>
      </li> -->
      <!--Menu dropdown end -->
    </ul>
  </div>
</nav>
