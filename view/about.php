<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

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
      <br>
      <h2>About</h2>
      <p>Planning a trip may be painful for many people, buy fly tickets, book the hotels, figure out local traffic, and plan the route, etc. all the things have to be done ahead of schedule. The tripNote is design for solve this problem. Experienced traveller can post their trip journal on the website, the journal may include hotel, restaurant they have been, some goodplace worth to go, and other experiences. For the normal user, they can search the trip journal by setting up the key word like departure and destination. The idea is to let busy people spend less time on planning the trip, and let experienced traveller to share their experience to others.</p>
      <h2>Author</h2>
      <p>Hi, I'm Marcus, I love design and coding, this is the most valuable and tough course I have taken so far;)</p> 
      <h2>Technical details</h2>
      <p>This website includes basic HTML/CSS with PHP/Mysql as backend..some AJAX and extra js libraries,however I don't have much time to talke about them....</p>
      <hr>

      <footer>
        <p>&copy;Marcus Su IAT352</p>
      </footer>
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  </body>
  </html>
