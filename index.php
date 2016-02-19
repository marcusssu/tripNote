<?php require_once("includes/session.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php 
    if (logged_in()) {
      redirect_to("view/loggedin.php");
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
  <link rel="stylesheet" href="css/svgfont.css"></head>
  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles -->
  <link href="css/tripNote.css" rel="stylesheet">    
</head>

<body class="indexbody">
  <!-- navigation bar -->
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php"><span class="glyphicon glyphicon-home"></span> Home</a>
      </div>
      <div>
        <ul class="nav nav-pills navbar-nav navbar-right navbar-collapse">
         <li>
          <p class="navbar-btn ">
            <a href="view/signin.php" class="btn btn-info"><span class="glyphicon glyphicon-user"></span> Sign In</a>
          </p>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
  <div class="container">
    <h1 id="brandTitle" class="col-md-12 text-center spanning">trip Note</h1>
    <p class="col-md-12 text-center spanning">The tripNote helps travellers plan their trip or a place to share their experience</p>
    <p class="col-md-12 text-center spanning"><a class="btn btn-lg btn-info btn-signUp" href="view/signup.php" role="button">Sign Up &raquo;</a></p>
  </div>
</div>

<div class="container">
  <!-- Example row of columns -->
  <div class="row">
    <div class="col-md-4">
      <h2><span class="icon-eye"></span> VIEW</h2>
      <p><span>You can register and view other’s tripNoate, the powerful filter tool helps you find your favour tripNote easily.</p>
    </div>
    <div class="col-md-4">
      <h2><span class="icon-quill"></span> WRITE</h2>
      <p>You can apply as a pro member and share your tripNote to the world</p>
    </div>
    <div class="col-md-4">
      <h2><span class="icon-tags"></span> MARK</h2>
      <p>You may mark the travellers you like and get their lastest news</p>

    </div>
  </div>

  <hr>
  <footer>
    <p>&copy; MarcusSu IAT352<a href="view/about.php" > About</a></p>
  </footer>
</div> <!-- /container -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</body>
</html>
