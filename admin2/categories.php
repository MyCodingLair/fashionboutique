<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/new/oldpcstuffshop/system_core/init.php';
  include 'include/head.php';
  include 'include/nav.php';

  $sql = "SELECT * FROM categories WHERE parent = 0";
  $result = $dbConnect->query($sql);

  $errors = array();

  //delete category
  if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "SELECT * FROM categories WHERE id = '$delete_id'";
    $result = $dbConnect->query($sql);
    $category = mysqli_fetch_assoc($result);
    if($category['parent'] == 0){  //if parent is deleted, delete child as well
      $deleteParentNchild = "DELETE FROM categories WHERE id = '{$delete_id}' OR parent = '{$delete_id}' ";
      $dbConnect->query('$deleteParentNchild');
      header('Location: categories.php');
    }
     $deleteSql = "DELETE FROM categories WHERE id = '$delete_id'  OR parent = '$delete_id'";
     $dbConnect->query($deleteSql);
     header('Location: categories.php');
  }

  //edit Category
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $editSql = "SELECT * FROM categories WHERE id = '$edit_id' ";
    $resultEdit = $dbConnect->query($editSql);
    $categoryEdit = mysqli_fetch_assoc($resultEdit);   ///f
 //            -->>                                                                                                                                       *******

    //header('Location: categories.php');
  }

  $category = '';  //declare/initialize the variable first for the $categoryValue use later on.
  $parentPost = '';  //declare/initialize the variable first for the $categoryValue use later on.


  //process the form
  if(isset($_POST) && !empty($_POST)){
    //sanitize input
    $parentPost = sanitize($_POST['parent']);
    $category = sanitize($_POST['category']);

    //query DB to check if the categoryName already exist in DB
    $sqlCategory = "SELECT * FROM categories WHERE categoryName = '$category' AND parent = '$parentPost' ";
    //if edit button is clicked, redefine the sql statement
    if(isset($_GET['edit'])){
      $id_to_edit = $categoryEdit['id'] ;   //the $categoryEdit[] is from the //edit category// section this will get the id from the DB and later use in the code below to select the category and parent that does not have the id specified
      $sqlCategory = "SELECT * FROM categories WHERE categoryName = '$category' AND parent = '$parentPost' AND id != '$id_to_edit' ";  //check if id is not equal to the id get from the //edit category. So, this will return 0 in the num_rows
    }

    $catResult = $dbConnect->query($sqlCategory);
    $row = mysqli_num_rows($catResult);

    //check if category is blank
    if($category == ''){
      $errors[] .= 'You must fill in the category!';
    }

    if($row > 0){  //if the row return a value, meaning it already exist in the DB
      $errors[] .= "The category (".$category.") already exist! Please enter a new category.";
    }
    //display errors or update DB
    if(!empty($errors)){
      //dislay errors
      $display = display_errors($errors);
      ?>
      <script>
        jQuery('document').ready(function(){
          jQuery('#display_errors').html('<?=$display;?>');
        });
      </script>
<?php
    } else {
      //update DB
      $updateSql = "INSERT INTO categories (categoryName, parent) VALUES('$category', '$parentPost') ";  //$parentPost in from the form post parent value
      // if the edit button is cliecked, redefine the SQL statement
      if(isset($_GET['edit'])){
        $updateSql = "UPDATE categories SET categoryName = '$category', parent = '$parentPost' WHERE id = '$edit_id' ";
      }
      $dbConnect->query($updateSql);
      header('Location: categories.php');
    }

  }

//set a variable to hold the value to place in the text input field
$categoryValue = '';
$parentValue = 0;
if(isset($_GET['edit'])){  //if the edit button is clicked
  $categoryValue = $categoryEdit['categoryName'];  //set the $categoryValue equal to the $categoryEdit['categoryName'] array which is already set above  *******
  $parentValue = $categoryEdit['parent'];
}else{
  if(isset($_POST)){  //if the form is post,, asign the $categoryValue to $category which is already set above in the //process form. The reason to do this is to display what the user typed in the input text field after the submit button is clicked
    $categoryValue = $category;
    $parentValue = $parentPost;  //the parentPost is from the //process form $parentPost;
  }
}
?>

 <h2 class="text-center">Categories</h2><hr>
<!--The code below this line is to create table / divide the screen using table in bootstap
bootsrap provide 12 division of column on the screen -->
<div class="row">
  <!--Form start-->
  <div class="col-md-6">
    <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">  <!--The ternary operator specifies if edit button is clicked which will cause the $_GET['edit'], then append '?edit='.$edit_id to the url, else append empty string '', the reason to do this is to change the action url in the form when edit button is clicked -->
      <legend><?=((isset($_GET['edit']))?'Edit ':'Add ');?> Category</legend>
      <div id="display_errors"></div>
      <div class="form-group">
        <label for="parent">Parent</label>
        <select class="form-control" name="parent" id="parent">
          <option value="0"<?=(($parentValue == 0)?'selected="selected"':'');?> >Parent</option>
          <?php while($parent=mysqli_fetch_assoc($result)): ?>
            <option value="<?=$parent['id'];?>" <?=(($parentValue == $parent['id'])?'selected="selected"':'');?>> <?=$parent['categoryName'];?> </option>
          <?php endwhile;?>
        </select>
      </div>
      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" class="form-control" id="category" name="category" value="<?=$categoryValue;?>">
      </div>
      <div class="form-group">
        <input type="submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add ');?> Category" class="btn btn-success">
      </div>
    </form>
  </div>

  <!--Categories Tables start -->
  <div class="col-md-6">
    <table class="table table-bordered">
      <thead>
        <th>Category</th> <th>Parent</th> <th>Remove</th> <th>Delete</th>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * FROM categories WHERE parent = 0";
          $result = $dbConnect->query($sql);
          while($parent = mysqli_fetch_assoc($result)):
          $parent_id = (int)$parent['id'];
          $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
          $result2 = $dbConnect->query($sql2);
        ?>
        <tr class="bg-primary">
          <td> <?=$parent['categoryName']; ?></td>
          <td>Parent</td>
          <td>
            <a href="categories.php?edit=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="categories.php?delete=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
          </td>
        </tr>
        <?php while($child = mysqli_fetch_assoc($result2)): ?>
          <tr class="bg-info">
            <td> <?=$child['categoryName']; ?></td>
            <td><?=$parent['categoryName'];?></td>
            <td>
              <a href="categories.php?edit=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
              <a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
            </td>
        <?php endwhile; ?>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

 <?php include 'include/footer.php';?>
