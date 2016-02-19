<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php
if (isset($_POST['submit'])) {
  // Process the form
  
  // validations
  $required_fields = array("username", "password");
  validate_presences($required_fields);
  
  if (empty($errors)) {
    // Attempt Login

    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $found_user = attempt_login($username, $password);

    if ($found_user) {
      // Success
      // Mark user as logged in
      $_SESSION["username"] = $found_user["username"];
      $_SESSION["member"] = $found_user["member"];
      $_SESSION["id"] = $found_user["id"];
      redirect_to("loggedin.php");
    } else {
      // Failure
    }
  }
} else {
  // This is probably a GET request
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
              <p class="navbar-btn ">
                <a href="signin.php" class="btn btn-info"><span class="glyphicon glyphicon-user"></span> Sign In</a>
              </p>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      <!-- Sign up form -->
      <form class="form-signin" action="signin.php" method="post">
        <h2 class="form-signin-heading">Sign In</h2>
        
        <?php echo message(); ?>
        <?php echo form_errors($errors,'username'); ?>
        <label for="inputUserName" class="sr-only">User Name</label>
        <input type='username' name='username' value='' id='inputUserName' class='form-control' placeholder='User Name' required autofocus>
        
        <?php echo form_errors($errors,'password'); ?>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" value="" id="inputPassword" class="form-control" placeholder="Password" required>

        <button class="btn btn-lg btn-primary btn-block submitbutton" type="submit" name="submit" >Sign In</button>
      </form>

      <hr>

      <footer>
        <p>&copy;Marcus Su IAT352</p>
      </footer>
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  </body>
  </html>
