<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php require_once("../includes/flickrapi.php"); ?>
<?php require_once('../codebird/codebird.php');
      require_once('../codebird/twitter_config.php');
?>
<?php 
$query = "INSERT INTO twitter (username, name, profile_image_url, textarea, postedtime, time)";
$query .= " VALUES ('username', 'name', 'profileimageurl', 'textarea', 'postedtime','time');";

$result = mysqli_multi_query($connection, $query);

    if ($result) {
    echo "insert into twitter";
    } else {
      echo "Error: " . $query . "<br>" . mysqli_error($connection);
    }

echo "<br>";

$query = "INSERT INTO flickr (username, farm, server, photoid, secret, time)";
$query .= " VALUES ('username', 'farm', 'server', 'photoid', 'secret','time');";

$result = mysqli_multi_query($connection, $query);

    if ($result) {
    echo "insert into flickr";
    } else {
      echo "Error: " . $query . "<br>" . mysqli_error($connection);
    }

echo "<br>";
    get_flickr('test');
echo "<br>";
get_twitters('username1');
  ?>
