
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>SumOurEyes</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/dist/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!-- <link href="jumbotron.css" rel="stylesheet"> -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="bootstrap/assets/js/html5shiv.js"></script>
      <script src="bootstrap/assets/js/respond.min.js"></script>
    <![endif]-->

    <link href="customstyle.css" rel="stylesheet">
    <link rel="stylesheet" href="summary.css" rel="stylesheet"/>
    <link rel="stylesheet" href="jquery/css/custom-theme/jquery-ui-1.10.3.custom.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="jquery/js/jquery-ui-1.10.3.custom.js"></script>
    <script src="jquery-cookie-master/jquery.cookie.js"></script>
    <script src="signin.js"></script>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header soe">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><img id="logo" src="img/logo.png" /></a>
        </div>
        <div class="navbar-collapse collapse">

          <ul class="nav navbar-nav">
            <li <?php if($page=="index"){echo 'class="current"';} ?>><a href="index.php">Home</a></li>
            <li <?php if($page=="about"){echo 'class="current"';}?>><a href="about.php">About</a></li>
      			<li id="projects-item" <?php if($page == "projects"){ echo 'class="current"';}?>><a href="projects.php">Projects</a></li>
      			<li id="signup-item" <?php if($page == "signup"){ echo 'class="current"';}?>><a href="signup.php">Sign Up</a></li>		
            <li id="username-item"></li>
            <li id="signout-item"><a href="#" id="signout">Sign out</a></li>
          </ul>
          
          <form class="navbar-form navbar-right" id="signin-form">
            <div class="form-group">
              <input type="text" placeholder="Username" class="form-control" id="signin_username" />
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control" id="signin_password" />
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form> 

        </div><!--/.navbar-collapse -->
      </div>
    </div>