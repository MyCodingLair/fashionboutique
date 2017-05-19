<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/new/fashionboutique/system_core/init.php';

if(!is_logged_in()){
  loggin_error_redirect();
}

include 'include/head.php';
include 'include/nav.php';

$userID = $_SESSION['userID'];

$hashedPassDB = $userData['password'];  //user hashed pass from DB


$oldPass = ((isset($_POST['oldPass']))?sanitize($_POST['oldPass']):'');
$oldPass = trim($oldPass);

$newPass = ((isset($_POST['newPass']))?sanitize($_POST['newPass']):'');
$newPass = trim($newPass);

$confirmPass = ((isset($_POST['confirmPass']))?sanitize($_POST['confirmPass']):'');
$confirmPass = trim($confirmPass);

//$newHashedPass = password_hash($confirmPass, PASSWORD_DEFAULT);


$errors = array();

?>
<style>
  body{
    background-image:url("/new/fashionboutique/images/header/background-image.jpg");
    background-size: 100vw 100vh;
    background-attachment: fixed;
  }
</style>
<div id="login-form">
  <div>
    <?php
    //form validation
      if($_POST){
        if(empty($_POST['oldPass']) || empty($_POST['newPass']) || empty($_POST['confirmPass'])){
          $errors[] .= "You must fill in all the required fille marked with *.";
        }

        //password length check
        if(strlen($newPass)<6){
          $errors[] .= "Password must be at least 6 characters!";
        }

        //check if new pass word match
        if($newPass != $confirmPass){
          $errors[] .= "Your new password did not match.";
        }

        //check if oldPass match with hashed pass in the DB
        if(!password_verify($oldPass, $hashedPassDB)){
          $errors[] .= "Your old password is in correct!";
        }

        $newHashedPass = password_hash($confirmPass, PASSWORD_DEFAULT);

        //check for errors
        if(!empty($errors)){
          echo display_errors($errors);
        } else {
          //change password
          $sql = "UPDATE users SET password = '$newHashedPass', passChangeByUser = passChangeByUser + 1 WHERE id = '$userID'";
          if( !($dbConnect->query($sql)) ){
            $errors[] .= "An error occured. Please try again.";
            header('Location: register.php');
          }else{
            $_SESSION['success_msg'] = "Your password has been updated. Please login to continue.";
            header('Location: login.php');
            unset($_SESSION['userID']);
          }

        }
      }

    ?>

  </div>
  <h2 class="text-center">Change Password</h2>
  <form action="change_password.php" method="post">
    <div class="form-group">
      <label for="oldPass">Old Password*: </label> <input type="oldPass" name="oldPass" id="oldPass" class="form-control" value="<?=$oldPass;?>">
    </div>
    <div class="form-group">
      <label for="newPass">New Password*: </label> <input type="newPass" name="newPass" id="newPass" class="form-control" value="<?=$newPass;?>">
    </div>
    <div class="form-group">
      <label for="confirmPass">Confirm New Password*: </label> <input type="confirmPass" name="confirmPass" id="confirmPass" class="form-control" value="<?=$confirmPass;?>">
    </div>
    <div class="form-group">
      <a href="index.php" class="btn btn-default">Cancel</a>
      <input type="submit" value="Change" class="btn btn-primary">
    </div>
  </form>
  <p class="text-right"><a href="/new/fashionboutique/index.php" alt="home">Visit Site</a></p>
</div>



<?php include 'include/footer.php'; ?>
