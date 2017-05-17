<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/new/oldpcstuffshop/system_core/init.php';
include 'include/head.php';
include 'include/nav.php';

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);

//$hashed = password_hash($password, PASSWORD_DEFAULT);
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
        if(empty($_POST['email']) || empty($_POST['password'])){
          $errors[] .= "You must provide email and password";
        }

        //validate email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){  //filter_var() is php function, the 1st paramter is the string we want to filter, the 2nd parameter is the type of filter we want
          $errors[] .= "You must enter a valid email!";
        }

        //password length check
        if(strlen($password)<6){
          $errors[] .= "Password must be at least 6 characters!";
        }

        //check if user exist in DB
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $dbConnect->query($sql);
        $user = mysqli_fetch_assoc($result);
        $numrow = mysqli_num_rows($result);

        if($numrow == 0){
          $errors[] .= "User doesn't exist!";
        }

        if(!password_verify($password, $user['password'])){   //password_verify() is a php function to verify the password mathces the hash, 1st param is the string that store the password, 2nd param is the hashed password from DB to verify it with
          $errors[] .= "Incorect password! Please try again.";
        } else {
          // log user in
          $user_id = $user['id'];
          login($user_id);

        }

        //check for errors
        if(!empty($errors)){
          echo display_errors($errors);
        }
      }

    ?>

  </div>
  <h2 class="text-center">Login</h2>
  <form action="login.php" method="post">
    <div class="form-group">
      <label for="email">Email: </label> <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
    </div>
    <div class="form-group">
      <label for="password">Password: </label> <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div>
    <div class="form-group">
      <input type="submit" value="login" class="btn btn-primary">
    </div>
  </form>
  <p class="text-right"><a href="/new/fashionboutique/index.php" alt="home">Visit Site</a></p>
</div>



<?php include 'include/footer.php'; ?>
