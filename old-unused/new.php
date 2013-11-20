<!doctype html>
 
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>jQuery UI Tabs - Simple manipulation</title>
 
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
  <style type="text/css">
button#add_tab {
    border: 1px solid #fff;
    background-color: #3c3;
    color: #fff;
    font-size: 18px;
    padding: 5px;
    margin-left: 120px;
    cursor: pointer;
}

div#chapters {
    background-color: #eee;
    float: left;
    padding-top: 5px;
    width: 290px;
}

div#content {
    color: #444;
    font-family: "Helvetica";
}

div#header {
    background-color: #36c;
    border-bottom: 2px solid #339;
    height: 100px;
    margin: 0px;
}

div#tabs {
    cursor: pointer;
    width: 100%;
}

h1#projectname {
    font-size: 22px;
    margin-left: 20px;
}

html, body {
    margin: 0;
    padding: 0;
}

img#logo {
    
    width: 214px;
    height: 63px;
}

div.ui-tabs-panel {
    float: left;
    width: 500px;
    font-size: 14px;
    margin-left: 30px;
}

ul.ui-tabs-nav {
    list-style: none;
    width: 240px;
}

li.ui-state-default {
    border-bottom: 1px solid #333;
    padding: 5px;
    font-size: 18px;
    color: #333;
    margin: 0px;
}

li.ui-tabs-active {
    color: #333;
    background-color: #fff;
}

a.ui-tabs-anchor {
    text-decoration: none;
    color: inherit;
}
  #dialog label, #dialog input { display:block; }
  #dialog label { margin-top: 0.5em; }
  #dialog input, #dialog textarea { width: 95%; }

  /* Vertical Tabs
----------------------------------*/
.ui-tabs-vertical .ui-tabs-nav { float: left; }
.ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
.ui-tabs-vertical .ui-tabs-nav li a { display:block; }
.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-selected { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; border-right-width: 1px; }
  </style>
  <script>
$(document).ready(function() {
        $("#tabs").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
        $("#tabs li").removeClass('ui-corner-top').addClass('ui-corner-left');
        
        init(<?= $_GET['projectid']?>);
    });

  $(function() {
    var tabTitle = $( "#tab_title" ),
      tabContent = $( "#tab_subtitle" ),
      tabs = $( "#tabs" ).tabs();

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
      var name = tabTitle.val(),
        subtitle = tabContent.val();

      $.post("addchapter.php", 
        {name: name, subtitle: subtitle, projectid: <?= $_GET['projectid']?>}, 
        function(id)
        {
          appendChapter(id, name);
        }); 
    }
 
    // addTab button: just opens the dialog
    $( "#add_tab" )
      .button()
      .click(function() {
        dialog.dialog( "open" );
      });
  });

  //Sava starts here
  function addNote(chapid){
    var text = $("#tabs").find("#newNote" + chapid);
    $.post("addNote.php", 
      {chapterid: chapid, content: text.val(), projectid: <?=$_GET['projectid']?>}, 
    
    function(id){
      appendNote(id, text.val(), chapid);
    });
  }; //insert note into database & in current page

  function deleteChapter(id){
    $.post("deleteChapter.php", 
      {chapterid: id}, 
    function(data){
      $('a[href="#tabs-'+id+'"]').parent().remove();//remove list item from UI
      $("div#tabs-"+id).remove();//remove chapter content from UI
      $( "#tabs" ).tabs( "refresh" ); 
    }); 
    //Todo: delete notes div?
  }

  function deleteNote(id){
    $.post("deleteNote.php", 
      {noteid: id}, 
    function(data){
      $("div#note"+id).remove();
    });
  }

  function init(projectid){
    $.post("getChapters.php", {projectid: projectid}, function(data){
        
        var chapters = jQuery.parseJSON(data);
        $.each(chapters, function(i, chapter){
          appendChapter(chapter.id, chapter.name); //Append chapter titles
          
          $.post("getNotes.php", {chapterid: chapter.id}, function(data){
            var notes = jQuery.parseJSON(data);
            $.each(notes, function(i, note){
              appendNote(note.id, note.content, chapter.id); //Append notes
            });
          });
        });

      });
  }

  function appendChapter(id, name){
    li = "<li><a href='#tabs-"+id+"'>"+name+"</a> <a href='#' onclick='deleteChapter("+id+");return false;'>D</a> </li>";
    
    $("#tabs").find( ".ui-tabs-nav" ).append( li );
    $("#tabs").append( "<div id='tabs-" + id + "'><h2>" + name + "</h2><div id='notes"+id+"'> </div><p><textarea id='newNote"+id+"' ></textarea><input type='submit' id='submitNote"+id+"' name='submit' value='add' onclick='addNote("+id+")'/></p></div>" );
    $("#tabs").tabs( "refresh" );
  }

  function appendNote(id, content, chapterid){
    var newnote = "<div id='note"+id+"'><p class='notetext' id='notetext"+id+"' contenteditable='true'>"+content+"</p><div id='notecontrols"+id+"'><a href='#' onclick='deleteNote("+id+");return false;'>D</a></div></div>";
    $("#notes" + chapterid).append(newnote);

    $("#notetext"+id).blur(function() { //Update database when note is changed
      var newcontent = $(this).html();
        
      $.post("editNote.php",
      {noteid: id, content: newcontent});

    });
  }

  </script>

  <?php
    include("connect.php");

    if(isset($_POST['projectsubmit'])){
      $projectname = $_POST['projectname'];
      $query = "INSERT INTO projects (name) VALUES ('".$projectname."')";
      mysql_query($query) or die (mysql_error());
            
    }else{
      if(isset($_GET['projectid'])){
        $result = mysql_query("SELECT name FROM projects WHERE id='".$_GET['projectid']."'") or die(mysql_error());
        $projectname = mysql_result($result, 0);
        
      }
    }

  ?>

</head>
<body>
  <div id="page">
    <div id="header">
      <img id="logo" src="img/logo.png" />
    </div>
    <div id="dialog" title="New chapter">
      <form>
        <fieldset class="ui-helper-reset">
          <label for="tab_title">Title</label>
          <input type="text" name="tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all" />
          <label for="tab_subtitle">Subtitle (optional)</label>
          <input type="text" name="tab_subtitle" id="tab_subtitle" class="ui-widget-content ui-corner-all" />
        </fieldset>
      </form>
    </div>
    
    <div id="content">
      <div id="tabs">
        <div id="chapters">
          <h1 id="projectname"><?= $projectname ?></h1>
          <ul>
          </ul>
          <button id="add_tab">Add chapter</button>
        </div>
        
      </div>
    </div>
 
</body>
</html>