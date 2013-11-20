<?php 
$page = "signup";
include("header.php"); ?>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="container content">

      <form class="form-signin" id="signup-form">
        <h2 class="form-signin-heading">Start summarizing!</h2>
        <div class="fieldgroup"><label for="username">Username: </label><input id="username" name="username" type="text" class="form-control" autofocus /></div>        
        <div class="fieldgroup"><label for="email">Email: </label><input id="email" name="email" type="email" class="form-control" autofocus /></div>
        <div class="fieldgroup"><label for="password">Password: </label><input id="password" name="password" type="password" class="form-control" /></div>
        <div class="fieldgroup"><label for="password_confirm">Confirm password: </label><input id="password_confirm" name="password_confirm" type="password" class="form-control" /></div>
        <button class="btn btn-lg btn-primary btn-block signup" type="submit">Sign up</button>
      </form>

      <footer>
        <p>&copy; SumOurEyes 2013 | Contact</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="jquery/validation/dist/jquery.validate.js"></script>
    <script src="validateform.js"></script> 
  </body>
</html>
