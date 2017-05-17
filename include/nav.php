<!--  This is to store the navigation of the page,
  the the navigation bar at the top of the page build using bootstrap will be placed here
-->
<?php
  $sql = "SELECT * FROM categories WHERE parent=0";  //this wll query the DB for all the row that has the "parent" value = 0, all parent for the menu category has the value of 0.
  $parentQuery = $dbConnect->query($sql);
?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <a href="index.php" class="navbar-brand">Old Pc Stuff Shop</a>
    <ul class="nav navbar-nav">
      <?php while($parentMenu = mysqli_fetch_assoc($parentQuery)): ?>  <!--pass the query result as associative array in the $var -->
      <?php $parent_id = $parentMenu['id']; ?> <!--This will pass all parent menu category "id" from the DB to the $parent_id var, so wherever there is a "0" value in the "parent" column in the DB, it will take the "id" of the row that has "0" in the parent column, all parent category will have value of 0 and child category will have "parent" value according to their parent "id" in the DB -->
      <?php $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'"; ?> <!--This will query the DB for the row which the "parent" column in the DB has the value of $parent_id -->
      <?php $childQuery = $dbConnect->query($sql2); ?> <!--Note the 3 lines of code above is still in a loop,  -->
      <!--Menu dropdown start -->
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parentMenu['categoryName'];  ?><span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
          <?php while($childMenu = mysqli_fetch_assoc($childQuery)): ?>  <!--Do the same loop for the child category for the menu, only this time it fetch the "categoryName" from the DB where the "parent" value is = $parent_id, 1,2,4 and so fort -->
          <li><a href="category.php?cat=<?=$childMenu['id'];?>"><?php echo $childMenu['categoryName']; ?></a></li>
        <?php endwhile;  ?>
        </ul>
      </li>
    <?php endwhile; ?>  <!--The above method is done in one table, it can be done using 2 table as well, did that in "test-nav.php" -->
    <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart </a></li>

      <!--Menu dropdown end -->
    </ul>
  </div>
</nav>
