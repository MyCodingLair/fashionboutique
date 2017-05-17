<?php

function display_errors($errors){
  $display = '<ul class="bg-danger">';
  foreach ($errors as $error) {
    $display .= '<li class="text-danger">'.$error.'</li>';
  }

  $display .= '</ul>';

  return $display;
}

function sanitize($unsanitizedInput){
  //this is a prebuild php function to turn the input tag into html entities and print it into the screen
  //it require 3 parameters, the first parameter is the thing that we want to manipulate using htmlentities,
  //the second and third is what we want to sanized it with, in other words what we want to get rid off.
  //ENT_QUOTES will get rid of single quote '' and double_quote "".
  //UTF-8 will set the input as UTF-8 standard only, other wierd character will be ignored.
  return htmlentities($unsanitizedInput, ENT_QUOTES, "UTF-8");
}

function dollar($value){ //function display the dollar sign and the price
  return '$'.number_format($value, 2);
}

function login($user_id){   //function to handle the user login
  $_SESSION['userID'] = $user_id; //$user_id is from login.php, set the $_SESSION['userID'] to this value and use it later in index.php
  global $dbConnect;
  $date = date("Y-m-d H:i:s");  //set the date into (year-month-day hour:minute:seconds) format, the date() is a php function
  $sql = "UPDATE users SET lastLogin = '$date' WHERE id = '$user_id'";
  $dbConnect->query($sql);

  $_SESSION['success_login'] = "You are now logged-in";  //create a new session for user successfull login and to hold a message of login success, this session will be used in the init.php which is loaded in every page that need init.php
  header('Location: index.php');
}

function is_logged_in(){  //function to check if user is logged in, will be used in the index.php later.
  if(isset($_SESSION['userID']) && $_SESSION['userID'] > 0){  //the $_SESSION['userID']>0 is just another check, because it is set to the id in the DB so it must be more than 0
    return true;
  } else {
    return false;
  }
}

function login_error_redirect($url = 'login.php'){  //funtion to redirect user if user is not logged in
  $_SESSION['error_login'] = "You must be login to access this page";
  header('Location: '.$url);
}

function permission_error_redirect($url = 'login.php'){  //funtion to redirect user if user is not logged in
  $_SESSION['error_permission'] = "You don't have permission to access this page";
  header('Location: '.$url);
}

function permission($perms = 'admin'){
  global $userData;
  $permission = explode(',', $userData['permission']);
  if(in_array($perms, $permission, true)){
    return true;
  } else {
    return false;
  }
}


function dateFormatter($date){   //function to format the date to display in this format month, day, year, time
  return date("M d, Y h:i A", strtotime($date));  //date() is php function, 1st param is format we want to display, 2nd param is the $var tha hold the date from DB, strtotime() is a php function to parse/convert the string of the date stored in the DB to Unix timestamp
  //M->month in short form (Jan, Feb, etc)
  //d-> days in number (02, 17, etc)
  //Y-> year in 4 digit (1999, 2017, etc)
  //h-> the hour in 12 hour format (09, 12, etc)
  //i-> minutes
  //A-> to display AM or PM
  //refer -> http://php.net/manual/en/function.date.php   for the format of date available
}

function getCategory($child_id){  //function to get the parent name and child name from DB by splitting the categoris table in the DB into two table and then join them into one table based on a condition
  global $dbConnect;
  $id = sanitize($child_id);  //$child_id is a paramenter passed when the function is in used (currently used in category.php)

  $sql = "SELECT parent.id AS 'parendID', parent.categoryName AS 'parentName', child.id AS 'childID', child.categoryName AS 'childName' FROM categories child INNER JOIN categories parent ON child.parent = parent.id WHERE child.id = '$id' ";
  $query = $dbConnect->query($sql);
  $category = mysqli_fetch_assoc($query);

  return $category;   //this will return $category as an array. check using var_dump($category) in category.php
}