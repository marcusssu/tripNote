<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php 
    if (!logged_in()) {
      redirect_to("../index.php");
    };
   ?>
<?php
if (isset($_POST['submit'])){
  
  //check if the textarea and other selections are blank.
  $required_fields = array("textarea", "departure","arrive","season");
  validate_presences($required_fields);
  if (empty($errors)) {
    
    //get value from form.
    $username = $_SESSION["username"];
    $textarea = addslashes($_POST["textarea"]);
    $departure = $_POST["departure"];
    $arrive = $_POST["arrive"];
    $season = $_POST["season"];

    //insert data into database.
    $query  = "INSERT INTO note (";
    $query .= "  username, textarea, departure, arrive, season";
    $query .= ") VALUES (";
    $query .= "  '{$username}', '{$textarea}','{$departure}','{$arrive}','{$season}'";
    $query .= ")";
    $result = mysqli_query($connection, $query);
    redirect_to("loggedin.php");
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
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles -->
  <link href="../css/tripNote.css" rel="stylesheet">
  <link href="../css/signup.css" rel="stylesheet">

</head>

<body class="indexbody">
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

  <form class="form-signin" action="writenote.php" method="post">
    <h2 class="form-signin-heading">Write your note</h2>
    <?php echo form_errors($errors,'textarea'); ?>
    <textarea class="form-control" rows="3" name="textarea"></textarea>
<?php echo form_errors($errors,'departure'); ?>
<?php echo form_errors($errors,'arrive'); ?>
<?php echo form_errors($errors,'season'); ?>
<select name="departure" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <option value="">Departure</option>
    <option value="landon">London</option>
    <option value="newyork">New York</option>
    <option value="paris">Paris</option>
    <option value="vancouver">Vancouver</option>
</select>
<select name="arrive" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <option value="">Arrive</option>
    <option value="landon">London</option>
    <option value="newyork">New York</option>
    <option value="paris">Paris</option>
    <option value="vancouver">Vancouver</option>
</select>
<select name="season" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <option value="">Season</option>
    <option value="spring">Spring</option>
    <option value="summer">Summer</option>
    <option value="fall">Fall</option>
    <option value="winter">Winter</option>
</select>
   <button class="btn btn-lg btn-primary btn-block submitbutton" type="submit" name="submit" >Submit</button>
 </form>


 <hr>

 <footer>
  <p>&copy;Marcus Su IAT352</p>
</footer>
</div> <!-- /container -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/tripnote.js"></script>

</body>
</html>
