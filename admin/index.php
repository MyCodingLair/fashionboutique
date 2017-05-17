<?php
require_once '../system_core/init.php';

if(!is_logged_in()){
  login_error_redirect();
}

include 'include/head.php';
include 'include/nav.php';
?>
Aministrator Page

<?php include 'include/footer.php'; ?>
