<?php
//create a constant to hold the URL of the root folder of the site
define('BASE_URL', $_SERVER['DOCUMENT_ROOT'].'/new/oldpcstuffshop/');  //$_SERVER['DOCUMENT_ROOT'] will get the root of the site url

define('CART_COOKIE', 'RandomString12345oldPcStuffShop');

define('CART_COOKIE_EXPIRE', time() + (86400 * 30));  //set the cookie to expire after 30 days, the time() is in seconds, so in 1 day there is 86400 seconds, to make 30 days just times 30

define('TAXRATE', '0.06');

define('CURRENCY', 'USD');

define('CHECK_OUT_MODE','TEST');  //dev mode, change to live when want to go to production

// Braintree_Configuration::environment('sandbox');
// Braintree_Configuration::merchantId('sk6qc458fxcwkxmf');
// Braintree_Configuration::publicKey('thq2f6r4xr5kqq25');
// Braintree_Configuration::privateKey('127539e4c1831fac813332eb2ddf1135');



// if(CHECK_OUT_MODE == 'TEST'){
//   define('BRAINTREE_PRIVATE', '127539e4c1831fac813332eb2ddf1135');
//   define('BRAINTREE_PUBLIC', 'thq2f6r4xr5kqq25');
// }

// if(CHECK_OUT_MODE == 'LIVE'){
//   define('STRIPE_PRIVATE', '');
//   define('STRIPE_PUBLIC', '');
// }


?>
