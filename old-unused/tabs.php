<!doctype html>
 
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>jQuery UI Tabs - Simple manipulation</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <!-- <link rel="stylesheet" href="/resources/demos/style.css" /> -->
  <style>
  #dialog label, #dialog input { display:block; }
  #dialog label { margin-top: 0.5em; }
  #dialog input, #dialog textarea { width: 95%; }
  #tabs { margin-top: 1em; }
  #tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
  #add_tab { cursor: pointer; }
  div#canvas{
      background-color: #eee;
      -moz-box-shadow: 3px 3px 4px #999; /* Firefox/Mozilla */  
      -webkit-box-shadow: 3px 3px 4px #999; /*Safari/Chrome */  
      box-shadow: 3px 3px 4px #999; /* Opera & hoe het zou moeten */
      border: 1px solid #ccc;
      width: 90%;
      height: 600px;
      margin-left: auto;
      margin-right: auto;
    }
   div.chunk{
      background-color: #fff;
      border: 1px solid #ddd;
      font-family: Arial;
      text-align: left;
      height: 100px;
      width: 180px;
      padding: 3px;
    }
    textarea{
      border: 1px solid #ccc;
      font-family: inherit;
      width: 170px;
      height: 60px;
    }
    h2{
      margin: 5px;
      font-size: 16px;
    }

    p{
      font-size: 12px;
    }
  </style>
  <script>
  $(function() {
    var tabTitle = $( "#tab_title" ),
      tabContent = $( "#tab_content" ),
      tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' role='presentation'>Remove Tab</span></li>",
      tabCounter = 2;
 
    var tabs = $( "#tabs" ).tabs();
 
    // modal dialog init: custom buttons and a "close" callback reseting the form inside
    var dialog = $( "#dialog" ).dialog({
      autoOpen: false,
      modal: true,
      buttons: {
        Add: function() {
          addTab();
          $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
      }
    });
 
    // addTab form: calls addTab function on submit and closes the dialog
    var form = dialog.find( "form" ).submit(function( event ) {
      addTab();
      dialog.dialog( "close" );
      event.preventDefault();
    });
 
    // actual addTab function: adds new tab using the input from the form above
    function addTab() {
      var label = tabTitle.val() || "Tab " + tabCounter,
        id = "tabs-" + tabCounter,
        li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
        tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";
 
      tabs.find( ".ui-tabs-nav" ).append( li );
      tabs.find("#canvas").append( "<div id='" + id + "'><p>" + tabContentHtml + "</p></div>" );
      tabs.tabs( "refresh" );
      tabCounter++;
    }
 
    // addTab button: just opens the dialog
    $( "#add_tab" )
      .button()
      .click(function() {
        dialog.dialog( "open" );
      });
 
    // close icon: removing the tab on click
    tabs.delegate( "span.ui-icon-close", "click", function() {
      var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
      $( "#" + panelId ).remove();
      tabs.tabs( "refresh" );
    });
 
    tabs.bind( "keyup", function( event ) {
      if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) {
        var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
        $( "#" + panelId ).remove();
        tabs.tabs( "refresh" );
      }
    });
  });
  
  //This is where the SaVa starts
  function placeChunk(e){
    var x = e.pageX-100;
    var y = e.pageY-80;
      

    //Chunk toevoegen
    var newDiv = '<div class="chunk active" style="position:absolute; display:block; top: '+y+'px; left:'+x+'px;"><input style="text" placeholder="Title" class="chunkTitle" /><div class="delete"></div><br /><textarea placeholder="Text" class="chunkText" ></textarea></div>';
    $("#canvas").append(newDiv);
    $(".title").focus();

    $(".chunk").draggable();
    $(".chunk").click(chunkClicked);
  };
  $(document).ready(function() {

    $("#canvas").click(function(e){
          console.log("lol");
          placeChunk(e);
     });
  });

  $(document).mouseup(function (e)
    {
        //Click outside active containers
        var container = $(".chunk.active");
        if (container.has(e.target).length === 0)
        {
            deactivateChunks();
            
        }
    });

    function deactivateChunks(){
      var container = $(".chunk.active");
      $(container).removeClass("active");

            var title = $(container).find(".chunkTitle");
            var text = $(container).find(".chunkText");
            title.focus(); //Werkt niet...
            $(container).children().remove();
            
            if(title.val()!="" || text.val()!="")
            {
              $(container).append('<h2 class="title">'+title.val()+'</h2><p class="text">'+text.val()+'</p>');
            }else{
              $(container).remove();//Remove empty container
            }
    }
    
    
    function chunkClicked(e){
      e.stopPropagation();

      if($(this).hasClass("active")==false){
        var title = $(this).find(".title").html();
          var text = $(this).find(".text").html();
          $(this).children().remove();
          $(this).append('<input style="text" placeholder="Title" class="chunkTitle" value="'+title+'" /><div class="delete"></div><br /><textarea placeholder="Text" class="chunkText" >'+text+'</textarea>');
          $(this).addClass("active");
      }

    }
  </script>
</head>
<body>
 
<div id="dialog" title="Chapter title">
  <form>
    <fieldset class="ui-helper-reset">
      <label for="tab_title">Title</label>
      <input type="text" name="tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all" />
      <label for="tab_content">Content</label>
      <textarea name="tab_content" id="tab_content" class="ui-widget-content ui-corner-all"></textarea>
    </fieldset>
  </form>
</div>
 
<button id="add_tab">Add chapter</button>
 
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Chapter 1</a> <span class="ui-icon ui-icon-close" role="presentation">Remove chapter</span></li>

  </ul>
  <div id="canvas">
    <div id="tabs-1">
      <p>Here you can add your chunks.</p>
    </div>
  </div>
</div>
 
 
</body>
</html>