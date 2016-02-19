<?php
	function redirect_to($new_location) {
	  header("Location: " . $new_location);
	  exit;
	}
	function mysql_prep($string) {
		global $connection;
		
		$escaped_string = mysqli_real_escape_string($connection, $string);
		return $escaped_string;
	}
	
	function confirm_query($result_set) {
		if (!$result_set) {
			//die("Database query failed.");
		}
	}

	function form_errors($errors=array(),$index) {
		$output = "";
		if (!empty($errors)&&isset($errors[$index])) {
				$output .= "<div class=\"error\">";
				$output .= htmlentities($errors[$index]);
				$output .= "</div>";
		}
		return $output;
	}
	function find_all_notes() {
		global $connection;
		
		$query  = "SELECT * ";
		$query .= "FROM note ";
		$query .= "ORDER BY id DESC";
		$note_set = mysqli_query($connection, $query);
		confirm_query($note_set);
		return $note_set;
	}
	function find_all_tweets_by_username($username){
		global $connection;
		$query  = "SELECT * ";
		$query .= "FROM twitter ";
		$query .= "WHERE username = '{$username}'";
		$result_set = mysqli_query($connection, $query);
		confirm_query($result_set);
		return $result_set;
	}
function get_flickr($flickr_username){
   global $connection;
    $query  = "SELECT * ";
    $query .= "FROM flickr ";
    $query .= "WHERE username = '{$flickr_username}'";
    $result_set = mysqli_query($connection, $query);
   	confirm_query($result_set);
   	//print_r($result_set);
   	if($result = mysqli_fetch_assoc($result_set)){
        //echo "flickr not empty";
        return true;
        //echo "not empty";
    }else{
    	//echo "flickr empty";
        return false;
        //echo "empty";
    }
}

function get_twitters($twitter_username){
    global $connection;
    $query  = "SELECT * ";
    $query .= "FROM twitter ";
    $query .= "WHERE username = '{$twitter_username}'";
    $result_set = mysqli_query($connection, $query);
   	confirm_query($result_set);
   	if($result = mysqli_fetch_assoc($result_set)){
        //echo "twitter not empty";
        return true;
        //echo "not empty";
    }else{
    	//echo "twitter empty";
        return false;
        //echo "empty";
    }
}
function find_all_flickr_by_username($username){
		global $connection;
		$query  = "SELECT * ";
		$query .= "FROM flickr ";
		$query .= "WHERE username = '{$username}'";
		$result_set = mysqli_query($connection, $query);
		confirm_query($result_set);
		return $result_set;
	}


	
	function delete_all_tweets_by_username($username){
		global $connection;
		$query  = "DELETE FROM ";
		$query .= "twitter ";
		$query .= "WHERE username = '{$username}'";
		$result = mysqli_query($connection, $query);
    	if($result && mysqli_affected_rows($connection) >0){
        //echo "deleted";
    }else{
        echo "error";
    }
	}
	function delete_all_flickr_by_username($username){
		global $connection;
		$query  = "DELETE FROM ";
		$query .= "flickr ";
		$query .= "WHERE username = '{$username}'";
		$result = mysqli_query($connection, $query);
    	if($result && mysqli_affected_rows($connection) >0){
        //echo "deleted";
    }else{
        echo "error";
    }
	}
	function find_all_notes_by($departure,$arrive,$season) {
		global $connection;
		
		$query  = "SELECT * ";
		$query .= "FROM note ";
		$query .= "WHERE ";
			if($departure==="all"){
				$query .= "departure IN ('landon','newyork','paris','vancouver') AND ";
			}else {
				$query .= "departure = '$departure' AND ";
			}; 
			if($arrive==="all"){
				$query .= "arrive IN ('landon','newyork','paris','vancouver') AND ";
			}else {
				$query .= "arrive = '$arrive' AND ";
			}; 
			if($season==="all"){
				$query .= "season IN ('spring','summer','fall','winter') ";
			}else{
				$query .= "season = '$season' ";
			}; 
		
		$query .= "ORDER BY date DESC";
		$note_set = mysqli_query($connection, $query);
		confirm_query($note_set);
		return $note_set;
	}
	function find_notes_by_username($username) {
		global $connection;
		
		$query  = "SELECT * ";
		$query .= "FROM note ";
		$query .= "WHERE username = '$username'";
		$query .= "ORDER BY id DESC";
		$note_set = mysqli_query($connection, $query);
		confirm_query($note_set);
		return $note_set;
	}
	function find_members_profile($username){
		global $connection;
		$query  = "SELECT * ";
		$query .= "FROM users ";
		$query .= "WHERE username = '$username'";
		$query .= "ORDER BY id DESC";
		$profile_set = mysqli_query($connection, $query);
		confirm_query($profile_set);
		return $profile_set;
	}

	function find_user_by_username($username) {
		global $connection;
		
		$safe_username = mysqli_real_escape_string($connection, $username);
		
		$query  = "SELECT * ";
		$query .= "FROM users ";
		$query .= "WHERE username = '{$safe_username}' ";
		$query .= "LIMIT 1";
		$user_set = mysqli_query($connection, $query);
		confirm_query($user_set);
		if($user = mysqli_fetch_assoc($user_set)) {
			return $user;
		} else {
			return null;
		}
	}
	

	function password_encrypt($password) {
  	$hash_format = "$2y$10$";   // Tells PHP to use Blowfish with a "cost" of 10
	  $salt_length = 22; 					// Blowfish salts should be 22-characters or more
	  $salt = generate_salt($salt_length);
	  $format_and_salt = $hash_format . $salt;
	  $hash = crypt($password, $format_and_salt);
		return $hash;
	}
	
	function generate_salt($length) {
	  // Not 100% unique, not 100% random, but good enough for a salt
	  // MD5 returns 32 characters
	  $unique_random_string = md5(uniqid(mt_rand(), true));
	  
		// Valid characters for a salt are [a-zA-Z0-9./]
	  $base64_string = base64_encode($unique_random_string);
	  
		// But not '+' which is valid in base64 encoding
	  $modified_base64_string = str_replace('+', '.', $base64_string);
	  
		// Truncate string to the correct length
	  $salt = substr($modified_base64_string, 0, $length);
	  
		return $salt;
	}
	
	function password_check($password, $existing_hash) {
		// existing hash contains format and salt at start
	  $hash = crypt($password, $existing_hash);
	  if ($hash === $existing_hash) {
	    return true;
	  } else {
	    return false;
	  }
	}

	function attempt_login($username, $password) {
		$user = find_user_by_username($username);
		if ($user) {
			// found admin, now check password
			if (password_check($password, $user["hashed_password"])) {
				// password matches
				return $user;
			} else {
				// password does not match
				return false;
			}
		} else {
			// admin not found
			return false;
		}
	}

	function logged_in() {
		return isset($_SESSION['username']);
	}
	

function check_member($member){
  if ($member==="1"){
      return "<a href='writenote.php'><button type='button' class='btn btn-default btn-lg'>
  <span class='icon-quill'></span> Write Note</button></a>";
  }
  else {
    return "<p>Sorry visitor can't write note.</p>";
  }
}
function check_if_member_in_following_list($currentuser,$membername){
    global $connection;
    $query  = "SELECT * ";
    $query .= "FROM followinglist ";
    $query .= "WHERE username = '{$currentuser}' AND followingname = '{$membername}'";
    $query .= "LIMIT 1";
    $result_set = mysqli_query($connection, $query);
    confirm_query($result_set);
    if($result = mysqli_fetch_assoc($result_set)){
        return true;
    }else{
        return false;
    }
}

function add_to_member_into_following_list($currentuser,$membername){
    global $connection;
    $query  = "INSERT INTO followinglist (";
        $query .= "  username, followingname";
        $query .= ") VALUES (";
        $query .= "  '{$currentuser}', '{$membername}'";
        $query .= ")";
$result = mysqli_query($connection, $query);
if($result){
    echo "unfollow";
}else{
    echo "error";
}
}
function delete_member_form_following_list($currentuser,$membername){
    global $connection;
    $query  = "DELETE FROM followinglist ";
    $query .= "WHERE username = '{$currentuser}' AND followingname = '{$membername}'";
    $query .= "LIMIT 1";
    $result = mysqli_query($connection, $query);
    if($result && mysqli_affected_rows($connection) == 1){
        echo "follow";
    }else{
        echo "error";
    }
}

function find_my_notes($currentuser){
  global $connection;
  $query = "SELECT * ";
  $query.= "FROM note ";
  $query.= "WHERE username = '{$currentuser}' ";
  $query .= "ORDER BY id DESC";
  $result_set = mysqli_query($connection, $query);
  confirm_query($result_set);
  return $result_set;
}
?>