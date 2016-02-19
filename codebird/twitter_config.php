<?php
	$consumer_key = "L0WIIDOu3VurjS2OeZGQohYLq";
	$consumer_secret = "g3MHWPhtR1VShnD1FsCf6gQ4pCSF1gNSVAwpTPX2rleyHx6uXa";
	$access_token = "2491763306-fwHK7Gc5X1l1m5G8JfCkVAfNuLJZAo7OmCxZfwT";
	$access_token_secret = "Eyg3aCYQoHLTcSPYIohlG31t4gNDS7WJsTP34caOgQpjE";

  // Set connection parameters and instantiate Codebird
  \Codebird\Codebird::setConsumerKey($consumer_key, $consumer_secret);
  $cb = \Codebird\Codebird::getInstance();
  $cb->setToken($access_token, $access_token_secret);

  $reply = $cb->oauth2_token();
  $bearer_token = $reply->access_token;

  // App authentication
  \Codebird\Codebird::setBearerToken($bearer_token);


  function get_user_tweets($username, $numTweets, $cb){
    $params = array(
      'screen_name' => $username,
      'count' => $numTweets // get 5 recent tweets
    );

    // Make the REST call to get the user's tweets
    $res = (array)$cb->statuses_userTimeline($params);

    // Convert results to an associative array
    $tweets = json_decode(json_encode($res) , true);
    return $tweets;
  }
?>