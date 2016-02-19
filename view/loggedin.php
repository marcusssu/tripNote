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

$note_set = find_all_notes();

if (isset($_POST['search'])){
  $departure = $_POST["departure"];
    $arrive = $_POST["arrive"];
    $season = $_POST["season"];
$note_set = find_all_notes_by($departure,$arrive,$season);
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

          <a href="myprofile.php"><span class="glyphicon glyphicon-user"></span> My Profile </a>

        </li>
        <li><a href="signout.php"><span class="glyphicon glyphicon-log-out"></span> Sign Out  </a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  <div class="box">
  <h2>Hello, <?php echo $_SESSION["username"]?></h2>
  <?php echo check_member($_SESSION["member"])?>

<hr>
  <form action="loggedin.php" method="post">
  <p>Search notes:</p>
  <select name="departure" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <option value="all">Departure</option>
    <option value="landon">London</option>
    <option value="newyork">New York</option>
    <option value="paris">Paris</option>
    <option value="vancouver">Vancouver</option>
</select>
<select name="arrive" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <option value="all">Arrive</option>
    <option value="landon">London</option>
    <option value="newyork">New York</option>
    <option value="paris">Paris</option>
    <option value="vancouver">Vancouver</option>
</select>
<select name="season" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <option value="all">Season</option>
    <option value="spring">Spring</option>
    <option value="summer">Summer</option>
    <option value="fall">Fall</option>
    <option value="winter">Winter</option>
</select>
<button id="searchbtn" class="btn btn-primary btn-inline submitbutton" type="submit" name="search" >Search</button>
 
 </form>
</div>
  <?php 
  while($note = mysqli_fetch_assoc($note_set)) {
  ?>
  <div class="box">
    <h3><a href="membersprofile.php?id=<?php echo urlencode($note["username"]); ?>"><?php echo htmlentities($note["username"]);?></a></h3>
    <p><?php echo htmlentities($note["textarea"]);?></p>
    
      <p class='info' style="display:inline";>From: <?php echo htmlentities($note["departure"]);?></p>
      <p class='info' style="display:inline";>To: <?php echo htmlentities($note["arrive"]);?></p>
      <p class='info' style="display:inline";>In: <?php echo htmlentities($note["season"]);?></p>
      <p class='info' style="display:inline";>posted at: <?php echo htmlentities($note["date"]);?></p>
    </div>
  <?php
  }
  ?>
  
<hr id="bothr">
  <footer>
    <p>&copy;Marcus Su IAT352</p>
  </footer>
</div> <!-- /container -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</body>
</html>
