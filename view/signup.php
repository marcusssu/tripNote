<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php
if (isset($_POST['submit'])) {

  // validations
  $required_fields = array("username", "password");
  validate_presences($required_fields);

  $fields_with_max_lengths = array("username" => 30);
  validate_max_lengths($fields_with_max_lengths);
  
  if (check_i_in_table("username","users")){
    $errors['username'] = "the user name has been taken, please try another one";
  };

  if (empty($errors)) {
    // Perform Create

    $username = mysql_prep($_POST["username"]);
    $hashed_password = password_encrypt($_POST["password"]);
    $email = mysql_prep($_POST["email"]);
    $member = $_POST["member"];
    $flickr = mysql_prep($_POST["flickr"]);
    $twitter = mysql_prep($_POST["twitter"]);

    $query  = "INSERT INTO users (";
    $query .= "  username, hashed_password, email, ";
    if ($twitter!=NULL){
      $query .= "twitter,";
    }
    if ($flickr!=NULL){
      $query .= "flickr,";
    }
    $query .= "member";
    $query .= ") VALUES (";
    $query .= "  '{$username}', '{$hashed_password}','{$email}',";
    if ($twitter!=NULL){
      $query .= "'{$twitter}',";
    }
    if ($flickr!=NULL){
      $query .= "'{$flickr}',";
    }
    $query .= "'{$member}'";
    $query .= ")";
    $result = mysqli_query($connection, $query);

    
    if ($result) {
      // Success
     $_SESSION["username"] = $username;
    $_SESSION["member"] = $member;
   
      redirect_to("loggedin.php");
    } else {
      // Failure
      $_SESSION["message"] = "Admin creation failed.";
    }
  }
} else {
  // This is probably a GET request
  
} // end: if (isset($_POST['submit']))

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
    
    <body class='indexbody'>
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

      <form class="form-signin" action="signup.php" method="post">
        <h2 class="form-signin-heading">Sign Up</h2>
        
        <?php echo form_errors($errors,'username'); ?>
        <label for="inputUserName" class="sr-only">User Name</label>
        <input type='username' name='username' value='' id='inputUserName' class='form-control' placeholder='User Name' required autofocus>
        
        <?php echo form_errors($errors,'password'); ?>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" value="" id="inputPassword" class="form-control" placeholder="Password" required>

       <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="email" value="" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        
        <div class="col-md-6 leftpadding">
        <label for="inputEmail" class="sr-only">twitter</label>
        <input type="twitter" name="twitter" value="" id="inputTwitter" class="form-control" placeholder="Twitter (optional)">
        </div>
        
        <div class="col-md-6 rightpadding leftpadding">
        <label for="inputEmail" class="sr-only">flickr</label>
        <input type="flickr" name="flickr" value="" id="inputFlickr" class="form-control" placeholder="Flickr (optional)">
        </div>
        <div class="col-md-12 rightpadding leftpadding">
        <hr>
      </div>
        <label class="radio-inline">
          <input type="radio" name="member"  value="0" checked=""> Visitor
        </label>
        <label class="radio-inline">
          <input type="radio" name="member"  value="1"> Member
        </label>
        <button class="btn btn-lg btn-primary btn-block submitbutton" type="submit" name="submit" >Sign Un</button>
      </form>

      <hr>

      <footer>
        <p>&copy;Marcus Su IAT352</p>
      </footer>
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  </body>
  </html>
