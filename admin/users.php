<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/new/fashionboutique/system_core/init.php';



if(!is_logged_in()){
  login_error_redirect();
}

include 'include/head.php';
include 'include/nav.php';

$errors = array();

if(isset($_GET['delete'])){
  $delete_id = sanitize($_GET['delete']);
  $deleteSql = "DELETE FROM users WHERE id = '$delete_id'";
  $dbConnect->query($deleteSql);
  $_SESSION['success_msg'] = "User has been deleted.";
  header('Location: users.php');
}
if(isset($_GET['add']) || isset($_GET['edit'])){
  $fname = (isset($_POST['fname']) && !empty($_POST['fname'])?sanitize($_POST['fname']):'');
  $fname = trim($fname);
  $lname = (isset($_POST['lname']) && !empty($_POST['lname'])?sanitize($_POST['lname']):'');
  $lname = trim($lname);
  $fullName = $fname.' '.$lname;
  $email = (isset($_POST['email']) && !empty($_POST['email'])?sanitize($_POST['email']):'');
  $email = trim($email);
  $permission = (isset($_POST['permission']) && !empty($_POST['permission'])?sanitize($_POST['permission']):'');

  $password = (isset($_POST['password']) && !empty($_POST['password'])?sanitize($_POST['password']):'');
  $password = trim($password);

  $hashedTempPass = password_hash($password, PASSWORD_DEFAULT);

  if(isset($_GET['add'])){
    //check if user already  exist
    $userCheckSql = "SELECT * FROM users WHERE email = '$email'";
    $existingUserCheck = $dbConnect->query($userCheckSql);
    $userExistCount = mysqli_num_rows($existingUserCheck);

    if($userExistCount != 0){
      $errors[] .= "User already exist!.";
    }

    if(isset($_GET['add']) && empty($_POST['password'])){
      $errors[] .= "All field marked with * is required!";
    }
  }


  if(isset($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];

    $userEditSql = "SELECT * FROM users WHERE id = '$edit_id'";

    $userEditResult = $dbConnect->query($userEditSql);

    $userToEdit = mysqli_fetch_assoc($userEditResult);

    $fname = ((isset($_POST['fname']) && $_POST['fname']!='')?sanitize($_POST['fname']):$userToEdit['firstName']);
    $fname = trim($fname);
    $lname = ((isset($_POST['lname']) && $_POST['lname']!='')?sanitize($_POST['lname']):$userToEdit['lastName']);
    $lname = trim($lname);
    $fullName = $fname.' '.$lname;
    $email = ((isset($_POST['email']) && $_POST['email']!='')?sanitize($_POST['email']):$userToEdit['email']);
    $email = trim($email);
    $permission = ((isset($_POST['permission']) && $_POST['permission']!='')?sanitize($_POST['permission']):$userToEdit['permission']);

    $password = ((isset($_POST['password']) && $_POST['password']!='')?sanitize($_POST['password']):'');
    $password = trim($password);




  }



  //validate input
  if($_POST){


      if(empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['email']) ){
        $errors[] .= "All field marked with * is required!";
      }

      //validate email
      if(!empty($_POST['email']) && !filter_var($email, FILTER_VALIDATE_EMAIL)){  //filter_var() is php function, the 1st paramter is the string we want to filter, the 2nd parameter is the type of filter we want
        $errors[] .= "You must enter a valid email!";
      }

      //password length check
      if(!empty($_POST['tempPass']) && strlen($tempPass)<6){
        $errors[] .= "Password must be at least 6 characters!";
      }


    //check for errors
    if(!empty($errors)){
      echo display_errors($errors);
    } else {
      //add user
      if(isset($_GET['add'])){
        $addUserSql = "INSERT INTO users (firstName, lastName, fullName, email, password, permission, joinDate) VALUES('$fname', '$lname', '$fullName', '$email', '$hashedTempPass', '$permission', NOW() )";
        if( !($dbConnect->query($addUserSql)) ){
          echo $dbConnect->error;    //for debug delete later
        }else{
          $_SESSION['success_msg'] = "User has been added!";
          header('Location: users.php');
        }
      }

      //edit or update user detail
      if(isset($_GET['edit'])){
        if($password == ''){
          $addUserSql = "UPDATE users SET firstName = '$fname', lastName = '$lname', fullName = '$fullName', email = '$email', permission = '$permission' WHERE id = '$edit_id'";
        }else{
          if($password != ''){
            $hashedEditPass = password_hash($password, PASSWORD_DEFAULT);
            $addUserSql = "UPDATE users SET firstName = '$fname', lastName = '$lname', fullName = '$fullName', email = '$email', password = '$hashedEditPass', passChangeByAdmin = passChangeByAdmin+1, permission = '$permission' WHERE id = '$edit_id'";
          }
        }

        if( !($dbConnect->query($addUserSql)) ){
          echo $dbConnect->error;    //for debug delete later
        }else{
          $_SESSION['success_msg'] = "User has been updated!";
          header('Location: users.php');
        }
      }

    }



  }



  ?>

  <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit User':'Add New User');?></h2> <hr>
  <!-- ========================FORM FOR ADDING NEW USER============================= -->
  <form action="users.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post">

    <div class="form-group col-md-6">
      <label for="fname">Firt Name*: </label> <input type="text" name="fname" class="form-control"value="<?=$fname;?>">
    </div>

    <div class="form-group col-md-6">
      <label for="lname">Last Name*:</label> <input type="text" name="lname" class="form-control"value="<?=$lname;?>">
    </div>

    <div class="form-group col-md-6">
      <label for="email">Email*: </label> <input type="email" name="email" class="form-control"value="<?=$email;?>">
    </div>

    <div class="form-group col-md-6">
      <label for="password"><?=((isset($_GET['edit']))?'Change Password*:':'Temporary Password*:');?> </label>
      <?=((isset($_GET['edit']))?'<button id="revealBtn" onclick="revealInput()">Change User Passworrd</button>':'');?>

      <input type="password" name="password" class="form-control" id="password" value="<?=$password;?>">

    </div>

    <div class="form-group col-md-6">
      <label for="permission">Permissions*: </label>
      <select class="form-control" name="permission">
        <option value="" <?=(($permission == '')?' selected': '');?>></option>
        <option value="editor" <?=(($permission == 'editor')?' selected': '');?> > Editor </option>
        <option value="admin" <?=(($permission == 'admin')?' selected': '');?> > Admin </option>

      </select>
     </div>
    <div class="form-group col-md-6 text-right">
      <a href="users.php" class="btn btn-default">Cancel</a>
      <input type="submit" value="<?=((isset($_GET['edit']))?'Edit User': 'Add User');?>" class="btn btn-success">
    </div>
  </form>


  <?php
} else {


$userSql = "SELECT * FROM users ORDER BY fullName";
$result = $dbConnect->query($userSql);

?>

<h2 class="text-center">Users</h2>
<a href="users.php?add=1" class="btn btn-success ">Add New Users</a>
<hr>

<table class="table table-bordered table-striped table-condensed table-hover">
  <thead> <th></th> <th>Name</th> <th>Email</th> <th>Join Date</th> <th>Last Login</th> <th>Permissions</th> </thead>
  <tbody>
    <?php while($userInfo = mysqli_fetch_assoc($result)):?>
    <tr>
      <td>
      <?php if($userInfo['id'] != $userData['id']):?>
        <a href="users.php?delete=<?=$userInfo['id'];?>" > <span class="btn btn-default btn-xs glyphicon glyphicon-remove"></span> </a>
        <a href="users.php?edit=<?=$userInfo['id'];?>" > <span class="btn btn-default btn-xs glyphicon glyphicon-pencil"></span> </a>
      <?php endif; ?>
      </td>
      <td><?=$userInfo['fullName'];?></td>
      <td><?=$userInfo['email'];?></td>
      <td><?=dateFormatter($userInfo['joinDate']);?></td>
      <td><?=(($userInfo['lastLogin'] == NULL)?'Never Login':dateFormatter($userInfo['lastLogin']));?></td>
      <td><?=$userInfo['permission'];?></td>
    </tr>
  <?php endwhile; ?>
  </tbody>

</table>



<?php } include 'include/footer.php'; ?>
