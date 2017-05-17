<?php
require_once '../system_core/init.php';

if(!is_logged_in()){
  login_error_redirect();
}

include 'include/head.php';
include 'include/nav.php';

//get brand from DB\
$sql = "SELECT * FROM brand ORDER BY brand";
$result = $dbConnect->query($sql);

$errors = array();  //create an variable to hold and array.

//edit brand
if(isset($_GET['edit']) && !empty($_GET['edit'])){
  $id_edit = (int)$_GET['edit'];
  $id_edit = sanitize($id_edit);

  $sql2 = "SELECT * FROM brand WHERE id = '$id_edit' ";
  $result = $dbConnect->query($sql2);
  $edit_brand = mysqli_fetch_assoc($result);
}



//delete brand
if(isset($_GET['delete']) && !empty($_GET['delete'])){
  $id_delete = (int)$_GET['delete'];
  $id_delete = sanitize($id_delete);

  $sql = "DELETE FROM brand WHERE id = '$id_delete' ";
  $dbConnect->query($sql);
  header('Location: brand.php');
}


//if the add-form is submitted do the following
if(isset($_POST['add_submit'])){
  $brand = sanitize($_POST['brand']);

  //check if brand input is blank
  if($_POST['brand'] == ""){
    $errors[] .= 'You must enter a brand!';
  }
  //check if brand exist in DB
  $sql = "SELECT * FROM brand WHERE brand = '$brand'";
  if(isset($_GET['edit'])){  //if the edit button is clicked change the $sql statement to the following:
    $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$id_edit' ";
  }
  $result = $dbConnect->query($sql);
  //$resultBrand = mysqli_fetch_assoc($result);
  $row = mysqli_num_rows($result);
  if($row > 0){
    //$errors[] .= "That brand (".$resultBrand['brand'].") already exist. Enter another brand!";  //if want to get and display the direct brand name from DB use this
    $errors[] .= "That brand (".$brand.") already exist. Enter another brand!";  //if want get and display the brand that user has entered use this intead
  }

  //display errors
  if(!empty($errors)){
    echo display_errors($errors);
  } else {
    //add brand to DB
    $sql = "INSERT INTO brand (brand) VALUES('$brand') ";  //"INSERT INTO tableName (columnName) VALUES('valuesToInsert')";
    if(isset($_GET['edit'])){  //if the edit button is clicked, change the $sql statement to the following:
      $sql = "UPDATE brand SET brand = '$brand' WHERE id = '$id_edit' ";  //update the column brand where the id = $id_edit
    }
    $dbConnect->query($sql);
    header('Location: brand.php');

  }

}

?>
<h2 class="text-center">Brand</h2> <hr>
<!-- Brand form here -->
<div class="text-center">
  <form class="form-inline" action="brand.php<?=((isset($_GET['edit']))?'?edit='.$id_edit:'');?>" method="post">
    <div class="form-group">
    <label for="brand"><?=((isset($_GET['edit'])) ? 'Edit' : 'Add a' );?> Brand</label>
    <?php  //this php part below is to set the value in the input tag below
      $brandValue = '';
      if(isset($_GET['edit'])){
        $brandValue = $edit_brand['brand'];
      } else {
        if(isset($_POST['brand'])){
          $brand = sanitize($_POST['brand']);
        }
      }
    ?>
    <input type="text" name="brand" id="brand" class="form-control" value="<?= $brandValue; ?>">
    <input type="submit" name="add_submit" value="<?=((isset($_GET['edit'])) ? 'Edit Brand' : 'Add Brand' );?>" class="btn btn-success"> <!--Change the button label depending on if it is edit or add -->
    <?php
      if(isset($_GET['edit'])): ?>
       <a href="brand.php" class="btn btn-danger">Cancel</a>
    <?php endif; ?>
    </div>
  </form>
</div>
<hr>

<!--The table and the class are from bootstrap //but the table-auto is custom added in the main.css-->
<table class="table table-bordered table-striped table-auto table-condensed" >
  <thead>
    <th></th> <th>Brand</th> <th></th>
  </thead>
  <tbody>
    <!--use pgp to loop all the brand in the db and display it  -->
    <?php while($brand = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td> <a href="brand.php?edit=<?=$brand['id'];?>" class="btn btn-xs btn-default"> <span class="glyphicon glyphicon-pencil"></span> </a> </td>  <!--The class="btn btn-xs btn-default" is from bootsrap -->
        <td> <?= $brand['brand']; ?> </td>
        <td> <a href="brand.php?delete=<?=$brand['id'];?>" class="btn btn-xs btn-default"> <span class="glyphicon glyphicon-remove-sign"></span> </a> </td>
      </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<?php include 'include/footer.php'; ?>
