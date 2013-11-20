<?php 
$page = "index";
include("header.php") ?>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>The new summarizing</h1>
        <p>SumOurEyes is summarizing, revolutionized. Add your notes, create links between its components and share your summaries with fellow students!</p>
        <p <?php if (isset($_COOKIE["signedin"]) && $_COOKIE["signedin"] != "null") {echo 'class="hidden"';} ?>>
        <a class="btn btn-primary btn-lg signup" href="signup.php">Sign up &raquo;</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-lg-4">
          <h2>Summarize</h2>
          <p>Start a new project and fill it with your notes. Search through your notes, highlight keywords and add attachments.</p>
        </div>
        <div class="col-lg-4">
          <h2>Link</h2>
          <p>Connect the dots. Let SumOurEyes automatically link your notes using keywords and create manual connections.</p>
       </div>
        <div class="col-lg-4">
          <h2>Collaborate</h2>
          <p>Share your summaries with others. Comment, discuss and learn!</p>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; SumOurEyes 2013 | Contact</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>
