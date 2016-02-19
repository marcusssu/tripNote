<?php

$errors = array();

function fieldname_as_text($fieldname) {
  $fieldname = str_replace("_", " ", $fieldname);
  $fieldname = ucfirst($fieldname);
  return $fieldname;
}

// * presence
// use trim() so empty spaces don't count
// use === to avoid false positives
// empty() would consider "0" to be empty
function has_presence($value) {
	return isset($value) && $value !== "";
}

function validate_presences($required_fields) {
  global $errors;
  foreach($required_fields as $field) {
    $value = trim($_POST[$field]);
  	if (!has_presence($value)) {
  		$errors[$field] = fieldname_as_text($field) . " can't be blank";
  	}
  }
}


function check_i_in_table($i,$table) {
		global $connection;
		global $errors;
		$i_value = trim($_POST[$i]);
		$safe_username = mysqli_real_escape_string($connection, $i_value);
		$query  = "SELECT * ";
		$query .= "FROM ".$table." ";
		$query .= "WHERE username = '{$safe_username}' ";
		$query .= "LIMIT 1";
		$admin_set = mysqli_query($connection, $query);
		confirm_query($admin_set);
		if($admin = mysqli_fetch_assoc($admin_set)) {
			return true;
		}else{
			return false;
		} 
	}
// // * string length
// // max length
function has_max_length($value, $max) {
	return strlen($value) <= $max;
}

function validate_max_lengths($fields_with_max_lengths) {
	global $errors;
	// Expects an assoc. array
	foreach($fields_with_max_lengths as $field => $max) {
		$value = trim($_POST[$field]);
	  if (!has_max_length($value, $max)) {
	    $errors[$field] = fieldname_as_text($field) . " is too long";
	  }
	}
}

// * inclusion in a set
function has_inclusion_in($value, $set) {
	return in_array($value, $set);
}

?>