<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/new/fashionboutique/system_core/init.php';
include 'include/head.php';
include 'include/nav.php';

$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$hashedPass = password_hash($password, PASSWORD_DEFAULT);

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

        $errors[] .= "password: ".$hashedPass;
        
        //check if user exist in DB
        $sql = "INSERT INTO users (email, password) VALUES('$email', '$hashedPass')";
        $dbConnect->query($sql);



      }

    ?>

  </div>
  <h2 class="text-center">Register</h2>
  <form action="register.php" method="post">
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
