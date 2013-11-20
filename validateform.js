$(document).ready(function() {
    var validator = $("#signup-form").validate({
    rules: {
      username: {
        required: true,
        minlength: 5,
        remote: "checkUsername.php"
      },
      password: {
        required: true,
        minlength: 5
      },
      password_confirm: {
        required: true,
        minlength: 5,
        equalTo: "#password"
      },
      email: {
        required: true,
        email: true,
        remote: "checkEmail.php"
      }
    },
    messages: {
      username: {
        required: "Provide a username",
        rangelength: jQuery.format("Enter at least {0} characters"),
        remote: jQuery.format("{0} is already in use")
      },
      password: {
        required: "Provide a password",
        rangelength: jQuery.format("Enter at least {0} characters")
      },
      password_confirm: {
        required: "Repeat your password",
        minlength: jQuery.format("Enter at least {0} characters"),
        equalTo: "Enter the same password as above"
      },
      email: {
        required: "Please enter a valid email address",
        minlength: "Please enter a valid email address",
        remote: jQuery.format("{0} is already in use")
      }
    },
    // the errorPlacement has to take the table layout into account
    errorPlacement: function(error, element) {
      if ( element.is(":radio") )
        error.appendTo( element.parent().next().next() );
      else if ( element.is(":checkbox") )
        error.appendTo ( element.next() );
      else
        error.appendTo( element.parent());
    },
    // specifying a submitHandler prevents the default submit, good for the demo
    submitHandler: function(form) {
      $.post('signupscript.php', $('#signup-form').serialize(), 
        function(data){
      window.location = "projects.php";}
    );
      
    },
    // set this class to error-labels to indicate valid fields
    success: function(label) {
      label.remove();
    },
    highlight: function(element, errorClass) {
      $(element).parent().next().find("." + errorClass).removeClass("checked");
    }
  });
});