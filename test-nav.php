<!--
  This is to store the navigation of the page,
  the the navigation bar at the top of the page build using bootstrap will be placed here

  This use 2 table for the menu option navigation bar. the table name in the DB are "parentmenu" and "childmenu"
-->
<?php
  $sql = "SELECT * FROM parentmenu";  //this wll query the DB for all the row that has the "parent" value = 0, all parent for the menu category has the value of 0.
  $parentQuery = $dbConnect->query($sql);
?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <a href="index.php" class="navbar-brand">Old Pc Stuff Shopping</a>
    <ul class="nav navbar-nav">
      <?php while($var = mysqli_fetch_assoc($parentQuery)): ?>  <!--pass the query result as associative array in the $var -->
      <?php $parent_id = $var['id']; ?>
      <?php $sql2 = "SELECT * FROM childmenu WHERE parentID = '$parent_id'"; ?> <!--This will query the DB for the row which the "parent" column in the DB has the value of $parent_id -->
      <?php $childQuery = $dbConnect->query($sql2); ?> <!--Note the 3 lines of code above is still in a loop,  -->
      <!--Menu dropdown start -->
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $var['menuName'];  ?><span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
          <?php while($child = mysqli_fetch_assoc($childQuery)): ?>  <!--Do the same loop for the child category for the menu, only this time it fetch the "categoryName" from the DB where the "parent" value is = $parent_id, 1,2,4 and so fort -->
          <li><a href="#"><?php echo $child['subMenuName']; ?></a></li>
        <?php endwhile; ?>
        </ul>
      </li>
    <?php endwhile; ?>

      <!--Menu dropdown end -->
    </ul>
  </div>
</nav>
