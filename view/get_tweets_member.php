<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php require_once("../includes/flickrapi.php"); ?>
<?php require_once('../codebird/codebird.php');
      require_once('../codebird/twitter_config.php');
?>
<?php
function get_latest_tweet($username,$postedtime){
    global $connection;
    $query  = "SELECT * ";
    $query .= "FROM twitter ";
    $query .= "WHERE postedtime = '{$postedtime}' AND username = '{$username}'";
    $result_set = mysqli_query($connection, $query);
    confirm_query($result_set);
    if($result = mysqli_fetch_assoc($result_set)){

        return true;

    }else{

        return false;
      
    }
}
$currentuser=$_SESSION["member"];
$profile_set=find_user_by_username($currentuser);

\Codebird\Codebird::setConsumerKey($consumer_key, $consumer_secret);
$cb = \Codebird\Codebird::getInstance();
$cb->setToken($access_token, $access_token_secret);

$reply = $cb->oauth2_token();
$bearer_token = $reply->access_token;

  // App authentication
\Codebird\Codebird::setBearerToken($bearer_token);
$count=10;
  // Create query
$params = array(
	'screen_name' => $profile_set['twitter'],
	'count' => $count
	);

$res = (array) $cb->statuses_userTimeline($params);

// Convert results to an associative array
$tweets = json_decode(json_encode($res), true);
$length=count($tweets)-1;
// check if the twitter name is valided
$twitter_name=$profile_set['twitter'];

if (isset($tweets['0']['user']['name'])){
  //if lastest tweet doesn't match with db make a update
if (get_latest_tweet($twitter_name,$tweets[0]['created_at'])==false){
    delete_all_tweets_by_username($twitter_name);
    //insert each items into db
    for($x = 0; $x < $length; $x++){
      $twitter_text=addslashes($tweets[$x]['text']);
      $postedtime=$tweets[$x]['created_at'];
      $profile_image_url=$tweets['0']['user']['profile_image_url'];
      $time=time();
      $query = "INSERT INTO twitter (username, name, profile_image_url, textarea, postedtime, time)";
      $query .= " VALUES ('{$twitter_name}', '{$tweets['0']['user']['name']}', '{$profile_image_url}', '{$twitter_text}', '{$postedtime}','{$time}');";
      $result = mysqli_query($connection, $query);
    }
}
//output the lastest tweets any way.
    
    $name=$tweets['0']['user']['name'];
    $profile_image_url=$tweets['0']['user']['profile_image_url'];
    $i = 0;
    header ("Content-Type:text/xml");
     echo "<?xml version='1.0' encoding='UTF-8'?>";
     echo "<tweets>";
    for($x = 0; $x < $length; $x++){
    $twitter_text=addslashes($tweets[$x]['text']);
    $postedtime=$tweets[$x]['created_at'];
    echo "<item>
          <username>".stripslashes($twitter_name)."</username>
          <name>".stripslashes($name)."</name>
          <profile_image_url>".$profile_image_url."</profile_image_url>
          <twitter_text>".stripslashes($twitter_text)."</twitter_text>
          <postedtime>".$postedtime[0].$postedtime[1].$postedtime[2].$postedtime[3].$postedtime[4].$postedtime[5].$postedtime[6].$postedtime[7].$postedtime[8].$postedtime[9].$postedtime[10]."</postedtime>
          </item>";
    }
    echo "</tweets>";
}
?>