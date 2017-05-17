<?php
//create a constant to hold the URL of the root folder of the site
define('BASE_URL', $_SERVER['DOCUMENT_ROOT'].'/new/oldpcstuffshop/');  //$_SERVER['DOCUMENT_ROOT'] will get the root of the site url

define('CART_COOKIE', 'RandomString12345oldPcStuffShop');

define('CART_COOKIE_EXPIRE', time() + (86400 * 30));  //set the cookie to expire after 30 days, the time() is in seconds, so in 1 day there is 86400 seconds, to make 30 days just times 30

define('TAXRATE', '0.06');

define('CURRENCY', 'USD');

define('CHECK_OUT_MODE','TEST');  //dev mode, change to live when want to go to production

if(CHECK_OUT_MODE == 'TEST'){
  define('STRIPE_PRIVATE', 'sk_test_RVyV20nkbIPf5ZDIhwCQX21E');
  define('STRIPE_PUBLIC', 'pk_test_vD1A3wyfXZe5Z9Qk5XVakaZy');
}

if(CHECK_OUT_MODE == 'LIVE'){
  define('STRIPE_PRIVATE', '');
  define('STRIPE_PUBLIC', '');
}


?>
