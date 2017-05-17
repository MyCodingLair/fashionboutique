<?php
  //this page is using the prentmenu table and the child menu table
  require_once $_SERVER['DOCUMENT_ROOT'].'/new/oldpcstuffshop/system_core/init.php';
  include 'include/head.php';
  include 'include/nav.php';

  $sql = "SELECT * FROM parentmenu";
  $result = $dbConnect->query($sql);

  $errors = array();

  //delete category
  if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $raw_id = $_GET['delete'];
    //$delete_id = sanitize($delete_id);
    if(strpos($raw_id, 'parent')!==False){
      $trimmed_delete_id_parent = (int)trim($raw_id, "parent");
      $sqlDeleteParent = "DELETE FROM parentmenu WHERE id = '$trimmed_delete_id_parent'";
      $dbConnect->query($sqlDeleteParent);
      $sqlDeleteChild = "DELETE FROM childmenu  WHERE parentID = '$trimmed_delete_id_parent'";
      $dbConnect->query($sqlDeleteChild);
      header('Location: categories3.php');
    }
    if(strpos($raw_id, 'child')!==False){
      $trimmed_delete_id_child = (int)(trim($raw_id, 'child'));
      $sqlDeleteChild2 = "DELETE FROM childmenu WHERE id = '$trimmed_delete_id_child'";
      $dbConnect->query($sqlDeleteChild2);
      header('Location: categories3.php');
    }

  }

  //edit Category
  if(isset($_GET['edit']) && !empty($_GET['edit'])){
    $edit_id = $_GET['edit'];
    $edit_id = sanitize($edit_id);

    if(strpos($edit_id, 'parent')!==False){
      $trimmed_edit_id_parent = (int)trim($edit_id, "parent");
      $sqlEditParent = "SELECT * FROM parentmenu WHERE id = '$trimmed_edit_id_parent'";
      $resultEditParent = $dbConnect->query($sqlEditParent);
      $categoryEdit = mysqli_fetch_assoc($resultEditParent);
      $categoryName = $categoryEdit['menuName'];
      //header('Location: categories3.php');
    }
    if(strpos($edit_id, 'child')!==False){
      $trimmed_edit_id_child = (int)(trim($edit_id, 'child'));
      $sqlEditChild = "SELECT * FROM childmenu WHERE id = '$trimmed_edit_id_child'";
      $resultEditChild = $dbConnect->query($sqlEditChild);
      $categoryEdit = mysqli_fetch_assoc($resultEditChild);
      $categoryName = $categoryEdit['subMenuName'];
      //header('Location: categories3.php');
    }
    //header('Location: categories3.php');
  }


  $category = '';  //declare/initialize the cariable first for the $categoryValue use later on.

  //process the form
  if(isset($_POST) && !empty($_POST)){
    //sanitize input
    $parent = sanitize($_POST['parent']);  //this 'parent' is from the <select name="parent"> tag, it post when the submit button is clicked
    $category = sanitize($_POST['category']);

    //check if category already exist in DB
    if($parent == 0){ //check if parent and child already exist if option parent is selected in the option selector. The value 0 is fromt the <select><option> tag
      $sqlCategoryParent = "SELECT * FROM parentmenu WHERE menuName = '$category' ";
      $parentResult = $dbConnect->query($sqlCategoryParent);
      $row = mysqli_num_rows($parentResult);

      if($row > 0){
        $errors[] .= "The parent category of (".$category.") already exist! Please enter a new category.";
        $parentAlreadyExist = True;
      }
    }
    if($parent == 0){
      $sqlCategoryChild = "SELECT * FROM childmenu WHERE subMenuName = '$category' ";
      $childResult = $dbConnect->query($sqlCategoryChild);
      $row2 = mysqli_num_rows($childResult);

      if($row2 > 0){
        $errors[] .= "The child category of (".$category.") already exist! Please enter a new category.";
        $childAlreadyExist = True;
      }
    }
    else{
      $parentAlreadyExist = False;
      $childAlreadyExist = False;
    }

    //if parent is selected check if child already exist
    if($parent > 0){
      $sqlCategoryChild = "SELECT * FROM childmenu WHERE subMenuName = '$category' AND parentID = '$parent' ";
      $childResult = $dbConnect->query($sqlCategoryChild);
      $row3 = mysqli_num_rows($childResult);

      if($row3 > 0){
        $errors[] .= "The child category of (".$category.") already exist! Please enter a new category.";
        $childAlreadyExist = True;
      }else {
        $childAlreadyExist = False;
      }
    }


    //check if category is blank
    if($category == ''){
      $errors[] .= 'You must fill in the category!';
    }
    //check if parent is not selected
    // if($parent == 0){
    //   $errors[] .= "Please select a parent category first.";
    // }
    //if already exist in DB
    // if($row > 0){
    //   $errors[] .= "The parent category of (".$category.") already exist! Please enter a new category.";
    // }

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
      //insert in parentmenu if parent is selected in the <select><option>
      if($parent == 0 && $parentAlreadyExist == False){
        $updateParentSql = "INSERT INTO parentmenu (menuName) VALUES('$category') ";
        $dbConnect->query($updateParentSql);
        header('Location: categories3.php');
      }else{
        if($parent > 0 && $childAlreadyExist == False){
          $updateChildSql = "INSERT INTO childmenu (subMenuName, parentID) VALUES('$category', '$parent') ";
          $dbConnect->query($updateChildSql);
          header('Location: categories3.php');
        }
      }
    }

  }

  //set a variable to hold the value to place in the text input field
  $categoryValue = '';
  if(isset($_GET['edit'])){  //if the edit button is clicked
    $categoryValue = $categoryName;  //set the $categoryValue equal to the $categoryName which is already set above  in the //edit category
  }else{
    if(isset($_POST)){  //if the form is post,, asign teh $categoryValue to $category which is already set above in the //process form. The reason to do this is to display what the user typed in the input text field after the submit button is clicked
      $categoryValue = $category;
    }
  }

?>

 <h2 class="text-center">Categories</h2><hr>
<!--The code below this line is to create table / divide the screen using table in bootstap
bootsrap provide 12 division of column on the screen -->
<div class="row">
  <!--Form start-->
  <div class="col-md-6">
    <form class="form" action="categories3.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
      <legend><?=((isset($_GET['edit']))?'Edit ':'Add ');?> Category</legend>
      <div id="display_errors"></div>
      <div class="form-group">
        <label for="parent">Parent</label>
        <select class="form-control" name="parent" id="parent">
          <option value="0">Parent</option>
          <?php while($parent=mysqli_fetch_assoc($result)): ?>
            <option value="<?=$parent['id']?>"><?=$parent['menuName'];?></option>
          <?php endwhile;?>
        </select>
      </div>
      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" class="form-control" id="category" name="category" value="<?=$categoryValue;?>">
      </div>
      <div class="form-group">
        <input type="submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add ');?>Category" class="btn btn-success">
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
          $sql = "SELECT * FROM parentmenu";
          $result = $dbConnect->query($sql);
          while($parent = mysqli_fetch_assoc($result)):
          $parent_id = (int)$parent['id'];
          $sql2 = "SELECT * FROM childmenu WHERE parentID = '$parent_id'";
          $result2 = $dbConnect->query($sql2);
        ?>
        <tr class="bg-primary">
          <td> <?=$parent['menuName']; ?></td>
          <td>Parent</td>
          <td>
            <a href="categories3.php?edit=<?="parent".$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="categories3.php?delete=<?="parent".$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
          </td>
        </tr>
        <?php while($child = mysqli_fetch_assoc($result2)): ?>
          <tr class="bg-info">
            <td> <?=$child['subMenuName']; ?></td>
            <td><?=$parent['menuName'];?></td>
            <td>
              <a href="categories3.php?edit=<?="child".$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
              <a href="categories3.php?delete=<?="child".$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
            </td>
        <?php endwhile; ?>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

 <?php include 'include/footer.php';?>
