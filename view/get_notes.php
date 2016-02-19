<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php require_once("../includes/flickrapi.php"); ?>
<?php require_once('../codebird/codebird.php');
      require_once('../codebird/twitter_config.php');
?>
<?php

$currentuser=$_SESSION["username"];
$profile_set=find_user_by_username($currentuser);

$notes_set=find_my_notes($currentuser);

header ("Content-Type:text/xml");
     echo "<?xml version='1.0' encoding='UTF-8'?>";
     echo "<notes>";
while($note = mysqli_fetch_assoc($notes_set)) {
     echo "<note>
           <id>".htmlentities($note["id"])."</id>
           <textarea>".htmlentities($note["textarea"])."</textarea>
           <departure>".htmlentities($note["departure"])."</departure>
           <arrive>".htmlentities($note["arrive"])."</arrive>
           <season>".htmlentities($note["season"])."</season>
           <date>".htmlentities($note["date"])."</date>
           </note>";
}
     echo "</notes>";
?>