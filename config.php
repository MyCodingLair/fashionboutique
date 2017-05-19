<?php
//create a constant to hold the URL of the root folder of the site
define('BASE_URL', $_SERVER['DOCUMENT_ROOT'].'/new/fashionboutique/');  //$_SERVER['DOCUMENT_ROOT'] will get the root of the site url

define('CART_COOKIE', 'RandomString12345');

define('CART_COOKIE_EXPIRE', time() + (86400 * 30) );  //set the cookie to expire after 30 days, the time() is in seconds, so in 1 day there is 86400 seconds, to make 30 days just times 30

define('TAXRATE', '0.06');

?>
