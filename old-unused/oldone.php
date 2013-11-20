<!doctype html>
 
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>jQuery UI Tabs - Simple manipulation</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
  <style type="text/css">
  #dialog label, #dialog input { display:block; }
  #dialog label { margin-top: 0.5em; }
  #dialog input, #dialog textarea { width: 95%; }
  #tabs { margin-top: 1em; }
  #tabs li .ui-icon-close { float: left; margin: 0.4em 0.2em 0 0; cursor: pointer; }
  #add_tab { cursor: pointer; }

  .ui-state-default { font-size: 12px;}
  .ui-tabs-panel {font-size: 12px;}
  /* Vertical Tabs
----------------------------------*/
.ui-tabs-vertical { width: 55em; }
.ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 12em; }
.ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
.ui-tabs-vertical .ui-tabs-nav li a { display:block; }
.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-selected { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; border-right-width: 1px; }
.ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 40em;}
  </style>
  <script>
var noteTemplate = "<div id='note#{noteid}'><p class='notetext' id='notetext#{noteid}' contenteditable='true'>#{notecontent}</p><div id='notecontrols#{noteid}'><a href='#' onclick='deleteNote(#{noteid});return false;'>D</a></div></div>";
var tabTemplate = "<li><a href='#{href}'>#{label}</a> <a href='#' onclick='deleteChapter(#{chapid});return false;'>D</a> </li>";

$(document).ready(function() {
        $("#tabs").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
        $("#tabs li").removeClass('ui-corner-top').addClass('ui-corner-left');

        init(<?= $_GET['projectid']?>);
    });

  $(function() {
    var tabTitle = $( "#tab_title" ),
      tabContent = $( "#tab_subtitle" );
 
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
      var label = tabTitle.val(),
        tabContentHtml = tabContent.val();

      $.post("addchapter.php", 
        {name: tabTitle.val(), subtitle: tabContent.val(), projectid: <?= $_GET['projectid']?>}, 
        function(data)
        {
          id = data;
          li = $( tabTemplate.replace( /#\{href\}/g, "#tabs-" + id ).replace( /#\{label\}/g, label ).replace(/#\{chapid\}/g, id) );

          tabs.find( ".ui-tabs-nav" ).append( li );
          tabs.append( "<div id='tabs-" + id + "'><h2>" + tabContentHtml + "</h2><div id='notes"+id+"'> </div><p><textarea id='newNote"+id+"' ></textarea><input type='submit' id='submitNote"+id+"' name='submit' value='add' onclick='addNote("+id+")'/></p></div>" );
          tabs.tabs( "refresh" );
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
    
    function(data){
      var noteid = data;
      var newnote = $( noteTemplate.replace( /#\{noteid\}/g, noteid ).replace( /#\{notecontent\}/g, content ));
      $("#notes" + chapid).append(newnote);

      $(".notetext").blur(function() { //Update database when note is changed
        var newcontent = $(this).html();
        
        $.post("editNote.php",
        {noteid: noteid, content: newcontent},
        function(data){
          alert(data);
        });

      });
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
            console.log(data);
            var notes = jQuery.parseJSON(data);
            $.each(notes, function(i, note){
              appendNote(note.id, note.content, chapter.id); //Append notes
            });
          });
        });

      });
  }

  function appendChapter(id, name){
    console.log(id + " - " + name);
  }

  function appendNote(id, content, chapterid){
    console.log(id + " - " + content + " - " + chapterid);
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
 
<div id="dialog" title="Tab data">
  <form>
    <fieldset class="ui-helper-reset">
      <label for="tab_title">Title</label>
      <input type="text" name="tab_title" id="tab_title" value="" class="ui-widget-content ui-corner-all" />
      <label for="tab_subtitle">Subtitle (optional)</label>
      <input type="text" name="tab_subtitle" id="tab_subtitle" class="ui-widget-content ui-corner-all" />
    </fieldset>
  </form>
</div>
<h1><?= $projectname ?></h1>
<button id="add_tab">Add Tab</button>
 
<div id="tabs">
  <ul>
    <?php
      //all chapters
      $chapters = mysql_query("SELECT name, id FROM chapters WHERE projectid='".$_GET['projectid']."'") or die(mysql_error());
      if(mysql_num_rows($chapters)==0){
        echo '<li><a href="#tabs-0">First</a><span class="ui-icon ui-icon-close" role="presentation">Remove chapter</span></li>';
        $empty = true;
      }else{
        while($chaprow = mysql_fetch_array( $chapters )) 
        {
          echo '<li><a href="#tabs-'.$chaprow['id'].'">'.$chaprow['name'].'</a> <a href="#" onclick="editChapter('.$chaprow['id'].');return false;">E</a> - <a href="#" onclick="deleteChapter('.$chaprow['id'].');return false;">D</a></li>';
        }
      }
    ?>
  </ul>
    <?php
      //all notes
      if($empty){
        echo '<div id="tabs-0"><h2>First chapter</h2><div id="notes0"><p>This is your first chapter!</p></div> <p><textarea id="newNote0" ></textarea><input type="submit" id="submitNote0" name="submit" value="add" /></p></div>';
      }else{
        $chapters = mysql_query("SELECT name, id FROM chapters WHERE projectid='".$_GET['projectid']."'") or die(mysql_error());
        while($chaprow = mysql_fetch_array( $chapters )) 
        {
          $notes = mysql_query("SELECT id, content FROM notes WHERE chapterid='".$chaprow['id']."'") or die(mysql_error());
          echo '<div id="tabs-'.$chaprow['id'].'"><div id="notes'.$chaprow['id'].'">';
          
          while($noterow = mysql_fetch_array($notes))
          {
            echo '<script type="text/javascript">initNote('.$chaprow['id'].','.$noterow['id'].', "'.$noterow['content'].'")</script>';
          }
          
          echo '</div> <p><textarea id="newNote'.$chaprow['id'].'" ></textarea><input type="submit" id="submitNote'.$chaprow['id'].'" name="submit" value="add" onclick="addNote('.$chaprow['id'].')" /></p></div>';
        }
      }
    ?>
  
</div>
 
 
</body>
</html>