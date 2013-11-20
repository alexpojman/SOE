$(document).ready(function() {

        $("#signin-form").submit(function(event){
          event.preventDefault();
          $.post("signin.php", {username: $("#signin_username").val(), password: $("#signin_password").val()}, function(data){
            if(data!=""){
              window.location = "projects.php";
            }else{
              alert("Wrong credentials!");
            }
          });

        });

        $("a#signout").click(function(event){
          event.preventDefault();
          dbcookie = $.cookie("auth").split("-"); //Split cookie
          $.post("deleteCookie.php", {userid: dbcookie[0], token: dbcookie[1]}); //Delete cookie from database
          $.cookie("auth", null, {path: '/' }); //Delete cookie from browser

          window.location = "index.php";
        });

        //COOKIE STUFF STARTS

        function checkCookie(){
          cookie = $.cookie("auth");

          if(cookie != undefined && cookie != "null"){ //if a cookie isset
            result = cookie.split("-"); //Split username and token
 
            $.post("checkCookie.php", {userid: result[0], token: result[1]}, function(userid){ //Check combination of username and token in DB
              if(userid != ""){            
                signedIn(userid);
              }else{
                notSignedIn();
              }
            });
          }else{

            notSignedIn();
          }   
        }

        function signedIn(userid){
          document.getElementById("signup-item").className = "hidden";
          document.getElementById("signin-form").className += " hidden";
          $.post("getUsername.php", {userid: userid}, function(username){
            document.getElementById("username-item").innerHTML = "Signed in as "+username;
          });
          
        }

        function notSignedIn(){
          document.getElementById("projects-item").className = "hidden";
          document.getElementById("signout-item").className = "hidden";
        }

        checkCookie();

        //COOKIE STUFF ENDS
});