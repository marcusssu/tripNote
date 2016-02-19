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
</head>

<body>
<!-- navigation bar -->
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="../index.html"><span class="glyphicon glyphicon-home"></span> Home</a>
      </div>
      <div>
        <ul class="nav nav-pills navbar-nav navbar-right navbar-collapse">
         <li>
          <p class="navbar-btn ">
            <a href="#" class="btn btn-info"><span class="glyphicon glyphicon-user"></span> Sign In</a>
          </p>
        </li>
        <li><a href="allMembers.php">See All Members  </a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  <?php
      // detect form submission
  if (isset($_POST['submit'])) {
        //get the username and make it as file name
    $name = $_POST["username"];
    $file = $name.'.txt';


    //get other data
    $email = $_POST["email"];
    $password = $_POST["password"];
    $gender = $_POST["gender"];
    $age = $_POST["age"];

    //create a file and write the attri 
    $handle = fopen("../data/".$file, 'w');
    fwrite($handle, $name."\n");
    fwrite($handle, $email."\n");
    fwrite($handle, $password."\n");
    fwrite($handle, $gender."\n");
    fwrite($handle, $age."\n");
    $outputMessage = "Your registration has been successfully confirmed.";
    fclose($handle);

    //write the file name in a index file for later use
    $handle2 = fopen("../data/fileindex.txt", 'a');
    fwrite($handle2, $name."\n");
    fclose($handle2);

    //create a php file shows the user details
    $userdetails = "../data/".$name.".php";
    $handle3 = fopen($userdetails, 'w');
    $content = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>tripNote</title>
    <!-- svgfonts -->
    <link rel='stylesheet' href='../css/svgfont.css'></head>
    <!-- Bootstrap core CSS -->
    <link href='../css/bootstrap.min.css' rel='stylesheet'>
    <!-- Custom styles -->
    <link href='../css/tripNote.css' rel='stylesheet'>
    </head>

    <body>

    <nav class='navbar navbar-inverse navbar-fixed-top'>
    <div class='container'>
    <div class='navbar-header'>

    <a class='navbar-brand' href='../index.html'><span class='glyphicon glyphicon-home'></span> Home</a>
    </div>
    <div>
    <ul class='nav nav-pills navbar-nav navbar-right navbar-collapse'>
    <li>
    <p class='navbar-btn '>
    <a href='#' class='btn btn-info'><span class='glyphicon glyphicon-user'></span> Sign In</a>
    </p>
    </li>

    <li><a href='../view/allMembers.php'>See All Members  </a></li>

    </ul>
    </div>
    </div>
    </nav>

    <div class='container'>
    "."<h2>".$name."</h2>".
    "<p>Email: ".$email."</p>".
    "<p>gender: ".$gender."</p>".
    "<p>Age: ".$age."</p>".
    
    "</br>
    <a href='../view/allMembers.php'>go Back</a>
    <hr>
    <footer>
    <p>&copy;Marcus SU IAT352</p>
    </footer>
    </div> <!-- /container -->
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>
    </body>
    </html>

    ";
    fwrite($handle3, $content);
    fclose($handle3);

  } else {

    $name = "";
    $email = "";
  }
  ?>
  <h2><?php echo $outputMessage;?></h2>
  <hr>

  <footer>
    <p>&copy;Marcus Su IAT352</p>
      </footer>
    </div> <!-- /container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  </body>
  </html>
