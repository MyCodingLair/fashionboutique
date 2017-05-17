<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/new/fashionboutique/system_core/init.php';

unset($_SESSION['userID']);
header('Location: login.php');

?>
