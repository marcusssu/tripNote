<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php require_once("../includes/flickrapi.php"); ?>
<?php require_once('../codebird/codebird.php');
require_once('../codebird/twitter_config.php');
?>
<?php
//////////////////////////msqli////////////////////
if(!logged_in()){
  redirect_to("../index.php");
}
$currentuser=$_SESSION["username"];

function find_following_list($currentuser){
  global $connection;
  $query = "SELECT * ";
  $query.= "FROM followinglist ";
  $query.= "WHERE username = '{$currentuser}'";
  $result_set = mysqli_query($connection, $query);
  confirm_query($result_set);
  return $result_set;
}
function find_following_list_checknull($currentuser){
  global $connection;
  $query = "SELECT * ";
  $query.= "FROM followinglist ";
  $query.= "WHERE username = '{$currentuser}'";
  $result_set = mysqli_query($connection, $query);
  confirm_query($result_set);
  if($user = mysqli_fetch_assoc($result_set)) {
    return $user;
  } else {
    return null;
  }
}

$namelist=find_following_list($currentuser);
$namelist_checknull=find_following_list_checknull($currentuser);
$note_set=find_my_notes($currentuser);
$profile_set=find_user_by_username($currentuser);
if ($profile_set['twitter']==null){
  $profile_set['twitter']="none";
}

if ($profile_set['flickr']==null){
  $profile_set['flickr']="none";
}
if (isset($_POST['submit'])) {
  $required_fields = array("twitter", "flickr","email");
  validate_presences($required_fields);
  if (empty($errors)) {
    $flickr = mysql_prep($_POST["flickr"]);
    $twitter = mysql_prep($_POST["twitter"]);
    $email = mysql_prep($_POST["email"]);

    $query  = "UPDATE users SET ";
    $query .= "email = '{$email}', ";
    $query .= "twitter = '{$twitter}', ";
    $query .= "flickr = '{$flickr}' ";
    $query .= "WHERE username = '{$currentuser}' ";
    $query .= "LIMIT 1";
    $result = mysqli_query($connection, $query);
    if ($result && mysqli_affected_rows($connection) == 1) {
      // Success
      redirect_to("myprofile.php");
    } else {
      // Failure
      //echo "error!";
    }
  }
}

////////////////////twitter//////////////////


  // Set connection parameters and instantiate Codebird
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



$twitter_name=$profile_set['twitter'];
$read_from_db=false;
$user_does_not_exist=false;

//db doesn't have the name
if (get_twitters($twitter_name)==false){
  // Make the REST call
  $res = (array) $cb->statuses_userTimeline($params);
    // Convert results to an associative array
  $tweets = json_decode(json_encode($res), true);
  
  $length=count($tweets)-1;
  

  // check if the twitter name is valided 
  if (isset($tweets['0']['user']['name'])){
    $user_does_not_exist=false;
    
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

    

    $read_from_db=false;
    $user_does_not_exist=false;
  }else{
    $user_does_not_exist=true;
  }
}else{ //db has the name

  $user_does_not_exist=false;

  $tweets_set=find_all_tweets_by_username($twitter_name);
    //get posted time
  while($item = mysqli_fetch_assoc($tweets_set)){
    $time_stamp=$item['time'];
    break;
  }

  if (time()-$time_stamp>60*1){//if greater then 60sec, make a update
    // Make the REST call
    $res = (array) $cb->statuses_userTimeline($params);
    // Convert results to an associative array
    $tweets = json_decode(json_encode($res), true);

    $length=count($tweets)-1;
    

    // check if the twitter name is valided 
    if (isset($tweets['0']['user']['name'])){
      $user_does_not_exist=false;

    // delete the original 
      delete_all_tweets_by_username($twitter_name);

    //insert each items into db
      for($x = 0; $x < $length; $x++){
        $twitter_text=addslashes($tweets[$x]['text']);
        $postedtime=$tweets[$x]['created_at'];
        $profile_image_url=$tweets['0']['user']['profile_image_url'];
        $time=time();
        $query = "INSERT INTO twitter (username, name, profile_image_url, textarea, postedtime, time)";
        $query .= " VALUES ('{$twitter_name}', '{$tweets['0']['user']['name']}', '{$profile_image_url}', '{$twitter_text}', '{$postedtime}','{$time}');";
        $result = mysqli_multi_query($connection, $query);
      }

      

      

      $read_from_db=false;
      $user_does_not_exist=false;
    }else{
     $user_does_not_exist=true;
    }
}else{ //within 60 sec dont update

  $read_from_db=true;
  $user_does_not_exist=false;
}
}
///////////flickr/////////
$flickr_name=$profile_set['flickr'];

$read_from_db_flickr=false;
$user_does_not_exist_flickr=false;

$Flickr = new Flickr; 

if (get_flickr($flickr_name)==false){//db does not have the name
  //make a call
  $userid=$Flickr->get_id($flickr_name);
  $userphotos_xml=$Flickr->get_Photos($userid);

  
  //check if the name is valided
  if (isset($userphotos_xml->photos->photo)){

  //insert into db
    foreach($userphotos_xml->photos->photo as $photo){
      $time=time();
      $farm=$photo['farm'];
      $server=$photo['server'];
      $id=$photo['id'];
      $secret=$photo['secret'];

      $query = "INSERT INTO flickr (username, farm, server, photoid, secret, time)";
      $query .= " VALUES ('{$flickr_name}', '{$farm}', '{$server}', '{$id}', '{$secret}','{$time}')";
      $result = mysqli_multi_query($connection, $query);
    }
  //echo $query;
    

    
    $read_from_db_flickr=false;
    $user_does_not_exist_flickr=false;
  }else {
    $user_does_not_exist_flickr=true;
  }
  }else {//db has the name

    $user_does_not_exist_flickr=false;
    $flickr_set=find_all_flickr_by_username($flickr_name);
    
    //get posted time
    while($item = mysqli_fetch_assoc($flickr_set)){
      $time_stamp=$item['time'];
      break;
    }

    if (time()-$time_stamp>60*1){//if greater then 60sec, make a update
      // Make a call

      $userid=$Flickr->get_id($flickr_name);
      $userphotos_xml=$Flickr->get_Photos($userid);

      
      //check if the name is valided
      if (isset($userphotos_xml->photos->photo)){
      //delete the original
        delete_all_flickr_by_username($flickr_name);
      //insert into db
        foreach($userphotos_xml->photos->photo as $photo){
          $time=time();
          $farm=$photo['farm'];
          $server=$photo['server'];
          $id=$photo['id'];
          $secret=$photo['secret'];
          $query = "INSERT INTO flickr (username, farm, server, photoid, secret, time)";
          $query .= " VALUES ('{$flickr_name}', '{$farm}', '{$server}', '{$id}', '{$secret}','{$time}')";
          $result = mysqli_multi_query($connection, $query);
        }
        
        
       
        
        $read_from_db_flickr=false;
        $user_does_not_exist_flickr=false;
      }else {
        $user_does_not_exist_flickr=true;
      }

    }else { // if less then 60 sec
      $read_from_db_flickr=true;
      $user_does_not_exist_flickr=false;
    }
  }
  ?>



  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tripNote</title>
    <!-- svgfonts -->
    <link rel="stylesheet" href="../css/svgfont.css"></head>
    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="../css/tripNote.css" rel="stylesheet">
    <link href="../css/signup.css" rel="stylesheet">
  </head>
  <script src="../js/jquery.js"></script>
  <script src="../js/bootstrap.js"></script>
  <script src="../js/main.js"></script>
  <script src='../js/jquery.grid-a-licious.min.js'></script>
  <script src='../js/refresh_tweets.js'></script>
  <script src='../js/refresh_flickr.js'></script>
  <script src='../js/refresh_notes.js'></script>

  <body>
    <!-- navigation bar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="../index.php"><span class="glyphicon glyphicon-home"></span> Home</a>
        </div>
        <div>
          <ul class="nav nav-pills navbar-nav navbar-right navbar-collapse">
           <li>

            <a href="#"><span class="glyphicon glyphicon-user"></span> My Profile </a>

          </li>
          <li><a href="signout.php"><span class="glyphicon glyphicon-log-out"></span> Sign Out  </a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">

    <div class='box row marginleft marginright'>
      <h2>My profile</h2>
      <form action="myprofile.php" method="post">
        <div class="col-xs-3" id="editform">
          <label for="twitter">Twitter :</label>
          <input type="twitter" name="twitter" class="form-control" placeholder=<?php echo $profile_set['twitter']?> value=<?php echo $profile_set['twitter'];?>>
          <?php echo form_errors($errors,'twitter'); ?>
        </div>
        <div class="col-xs-3" id="editform">
          <label for="flickr">Flickr :</label>
          <input type="flickr" name="flickr" class="form-control" placeholder=<?php echo $profile_set['flickr']?> value=<?php echo $profile_set['flickr']; ?> >
          <?php echo form_errors($errors,'flickr'); ?>
        </div>
        <div class="col-xs-3" id="editform">
          <label for="email">Email :</label>
          <input type="email2" name="email" class="form-control marginbot" placeholder=<?php echo $profile_set['email']?> value=<?php echo $profile_set['email'];?>>
          <?php echo form_errors($errors,'email'); ?>
        </div>
        <div class="col-xs-1" id="editform">
          <label id='buttonlabel'for="email">Submit:</label>
          <button class="btn btn-sm btn-primary btn-block submitbutton margintop" type="submit" name="submit" >Submit</button>
        </div>
      </form>
    </div>
    <div class="box">
     <div role="tabpanel">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist" id="myTab">
        <li role="presentation"  class="active" ><a href="#notes" aria-controls="notes" role="tab" data-toggle="pill">Notes</a></li>
        <li role="presentation"><a href="#twitter" aria-controls="twitter" role="tab" data-toggle="tab">Twitter</a></li>
        <li role="presentation"><a href="#flickr" aria-controls="flickr" role="tab" data-toggle="tab">Flickr</a></li>
        <li role="presentation"><a href="#following" aria-controls="following" role="tab" data-toggle="tab">Following</a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="notes">
          <?php 
          while($note = mysqli_fetch_assoc($note_set)) {
            ?>
            <div class="box">
              <p style="display:inline"><?php echo htmlentities($note["textarea"]);?></p>
              <br>
              <br>
              <p  class='info' style="display:inline";>From: <?php echo htmlentities($note["departure"]);?></p>
              <p  class='info' style="display:inline";>To: <?php echo htmlentities($note["arrive"]);?></p>
              <p  class='info' style="display:inline";>In: <?php echo htmlentities($note["season"]);?></p>
              <p class='info' style="display:inline";>posted at: <?php echo htmlentities($note["date"]);?></p>
              <br>
              <a style="display:inline" href="deletenote.php?id=<?php echo urlencode($note["id"]); ?>" onclick="return confirm('Are you sure?');">Delete</a>
              <a style="display:inline" href="editnote.php?id=<?php echo urlencode($note["id"]); ?>">Edit</a>
            </div>
            <?php
          }
          ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="twitter">
          <?php
          if ($user_does_not_exist==true){
            echo "user does not exist.";
          }else {
            if ($read_from_db==true){ ?>
            <!-- //read from db -->
            <?php 

            $tweets_set=find_all_tweets_by_username($twitter_name);

            while($item = mysqli_fetch_assoc($tweets_set)){

              ?>
              <div class="box row marginleft marginright">
                <div class="col-xs-1" id="editform">
                  <img src=<?php echo $item['profile_image_url']?> alt="profile_image" class="img-thumbnail">
                </div>

                <p class='inline screenname'><?php echo $item['name'] ?></p>
                <?php
                echo "<a class='inline' href=\"http://www.twitter.com/"
                . $profile_set['twitter']
                . "\">@"
                . $profile_set['twitter']
                . "</a>";
                ?>
                <p><?php echo $item['textarea'];?></p> 
                <p><?php 
                for ($i = 0; $i < 11; $i++) {
                  echo $item['postedtime'][$i];
                }?>
              </p>

            </div>
            <?php
          }
          ?>
          <?php  
        }else{ ?>
        <!-- //read from tweets --> 
        <?php 
        for($x = 0; $x < $length; $x++){
          ?>
          <div class="box row marginleft marginright">
            <div class="col-xs-1" id="editform">
              <img src=<?php echo $tweets['0']['user']['profile_image_url']?> alt="profile_image" class="img-thumbnail">
            </div>
            
            <p class='inline screenname'><?php echo $tweets['0']['user']['name'] ?></p>
            <?php
            echo "<a class='inline' href=\"http://www.twitter.com/"
            . $profile_set['twitter']
            . "\">@"
            . $profile_set['twitter']
            . "</a>";
            ?>
            <p><?php echo $tweets[$x]['text'];?></p> 
            <p><?php 
            for ($i = 0; $i < 11; $i++) {
              echo $tweets[$x]['created_at'][$i];
            }?>
          </p>
          <?php
          if(!empty($item['entities']['media']['0']['media_url'])){
           ?>
           <img src=<?php $item['entities']['media']['0']['media_url'] ?>>
           <?php 
         }
         ?>
       </div>
       <?php
     }
     ?>
     <?php
   }
 }
 ?>
</div>


<div role="tabpanel" class="tab-pane" id="flickr">

  <?php 
  if ($user_does_not_exist_flickr==true){
    echo "user does not exist.";
  }else {
          if ($read_from_db_flickr==false){  //read form call
            foreach($userphotos_xml->photos->photo as $photo){ ?>
            <div class="item img-thumbnail2">
              <?php echo '<img src="http://farm' . $photo["farm"] . '.static.flickr.com/' . $photo["server"] . '/' . $photo["id"] . '_' . $photo["secret"] . '.jpg">'; ?>
            </div>
            <?php
          }
    }else{ //read form db
      while($item = mysqli_fetch_assoc($flickr_set)) { ?>
      <div class="item img-thumbnail2">
        <?php echo '<img src="http://farm' . $item["farm"] . '.static.flickr.com/' . $item["server"] . '/' . $item["photoid"] . '_' . $item["secret"] . '.jpg">'; ?>
      </div>
      <?php
    }
  }
}
?>

</div>
<div role="tabpanel" class="tab-pane" id="following">
  <?php 
  if ($namelist_checknull!==null){
    while($name = mysqli_fetch_assoc($namelist)) {
      ?>
      <p style="display:inline"><?php echo htmlentities($name["followingname"]);?></p>
      <a style="display:inline" href="deletelist.php?id=<?php echo urlencode($name["followingname"]); ?>" onclick="return confirm('Are you sure?');">Delete</a>
      <br>
      <?php
    }

  }else{
    echo "you dont have anyone in you list";

  } 
  ?>

</div>
</div>
</div>
</div>

<hr id='bothr'>
<footer>
  <p>&copy;Marcus Su IAT352</p>
</footer>
</div> <!-- /container -->
<script>
$("#flickr").gridalicious({
  gutter: 2,
  width: 350
  
});
</script>
</body>
</html>
