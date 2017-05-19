<?php
require_once '../system_core/init.php';

if(!is_logged_in()){
  header('Location: login.php');
}
include 'include/head.php';
include 'include/nav.php';
echo $_SESSION['userID'];

?>
Aministrator Page

<?php include 'include/footer.php'; ?>
