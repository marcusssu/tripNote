<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php
if(!logged_in()){
  redirect_to("../index.php");
}
?>


<?php
$id=$_GET["id"];
echo $id;
$query = "DELETE FROM note WHERE id = '$id' LIMIT 1";
$result = mysqli_query($connection, $query);
confirm_query($result);
if ($result && mysqli_affected_rows($connection) == 1) {
    // Success

    redirect_to("myprofile.php");
  } else {
    // Failure

    // redirect_to("myprofile.php");
  }
?>




