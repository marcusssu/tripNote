<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php require_once("../includes/flickrapi.php"); ?>
<?php require_once('../codebird/codebird.php');
      require_once('../codebird/twitter_config.php');
?>
<?php
$currentuser=$_SESSION["member"];
$profile_set=find_user_by_username($currentuser);

$Flickr = new Flickr; 
$flickr_name=$profile_set['flickr'];
$userid=$Flickr->get_id($flickr_name);
$userphotos_xml=$Flickr->get_Photos($userid);

$flickr_name=$profile_set['flickr'];


// if the user exist ouput xml
  if (isset($userphotos_xml->photos->photo)){
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
     header ("Content-Type:text/xml");
     echo "<?xml version='1.0' encoding='UTF-8'?>";
     echo "<flickr>";
     foreach($userphotos_xml->photos->photo as $photo){
     echo "<photo>
              <id>".$photo['id']."</id>
              <secret>".$photo['secret']."</secret>
              <server>".$photo['server']."</server>
              <farm>".$photo['farm']."</farm>
           </photo>";
      }
      echo "</flickr>";
    }
?>