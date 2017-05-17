<?php

  require_once $_SERVER['DOCUMENT_ROOT'].'/new/oldpcstuffshop/system_core/init.php';

  $firstName = sanitize($_POST['firstName']);
  $lastName = sanitize($_POST['lastName']);
  $email = sanitize($_POST['email']);
  $streetAdd1 = sanitize($_POST['streetAdd1']);
  $streetAdd2 = sanitize($_POST['streetAdd2']);
  $city = sanitize($_POST['city']);
  $state = sanitize($_POST['state']);
  $zipCode = sanitize($_POST['zipCode']);
  $country = sanitize($_POST['country']);

  $errors = array();

  $required = array(
    'firstName' => 'Firt Name',
    'lastName'  =>'Last Name',
    'email'     => 'Email',
    'streetAdd1'=> 'Street Address',
    'city'      => 'City',
    'state'     => 'State',
    'zipCode'   => 'Zip Code',
    'country'   => 'Country',
  );

  //check if all required fields are filled out
  foreach ($required as $field => $display) {
    if(empty($_POST[$field]) || $_POST[$field] == ''){
      $errors[] = $display. ' is required.';
    }
  }

  //check if valid email address
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors[] = "Please enter a valid email address.";
  }

  if(!empty($errors)){
    echo display_errors($errors);
  } else {
    echo 'passed';
  }

 ?>
