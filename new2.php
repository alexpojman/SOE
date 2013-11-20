<!doctype html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>jQuery UI Tabs - Simple manipulation</title>
        
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="/sava/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.js"></script>
        <link rel="stylesheet" href="/sava/jquery-ui-1.10.3.custom/css/custom-theme/jquery-ui-1.10.3.custom.css" />
        <style type="text/css">
            
            div#chapters {
                background-color: #eee;
                float: left;
                padding-top: 5px;
                width: 290px;
                font-size: 14px;
                padding-bottom: 20px;
                border-right: 1px solid #339;
                border-bottom: 1px solid #339;
            }
            
            h2 {
                text-align: center;
            }
            
            #tab_title, #tab_subtitle {
                border: 1px solid #339;
                font-size: 16px;
                padding: 5px;
            }
            
            p.notetext {
                pointer: text;
            }
            
            #add_tab {
                font-weight: bold;
                margin-top: 15px;
                margin-right: 25px;
                float: right;
            }
            
            .notecontrols {
                
            }
            
            .note {
                border-left: 1px solid #ddd;
                padding-left: 5px;
            }
            
            .note:hover{
                border-left: 1px solid #999;
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
                padding: 0px; 
                background: none; 
                border-width: 0px;
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
            
            img{
                border: 0px;
            }
            
            .chaptercontrols{
                float: right;
            }
            
            #dialog label, #dialog input { display:block; }
            #dialog label { margin-top: 0.5em; }
            #dialog input, #dialog textarea { width: 95%; }
            
            /* Vertical Tabs
            ----------------------------------*/
            .ui-tabs-vertical .ui-tabs-nav { float: right; }
            .ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important;}
            .ui-tabs-vertical .ui-tabs-nav li a { display:block; }
            .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-selected { padding-right: .1em; border-right-width: 1px; border-right-width: 1px; }
            
            .ui-state-default {
                margin-bottom: -2px;
            }
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
                
                // ALEX CODE BELOW///////////////////////////////
                var textVal = text.val();
                var matches = textVal.match(/\[(.*?)\]/g);
                
                // Get a list of all the currently existing Keywords, check the keyword that the user 
                // is trying to add. If the two match, let the user know and don't add the new note to
                // the database
                //
                // TODO: Add the note to the database, and instead make attempted keyword a referer
                $.post("getKeywords.php", function(data){
                    var keywords = jQuery.parseJSON(data);
                    var matchedKeywords = new Array();
                    $.each(keywords, function(i, keyword){
                        
                        //Compare if keyword exists already
                        for(j = 0; j <= (matches.length - 1); j++) {
                            var existingKeyword = keyword.word;
                            var potentialKeyword = matches[j].substring(1, matches[j].length - 1);
                            
                            if(existingKeyword.toLowerCase() == potentialKeyword.toLowerCase()) {
                                alert("Keyword '" + potentialKeyword + "' already exists, and will be turned into a reference instead.");
                                textVal = textVal.replace("[" + potentialKeyword + "]", "#" + potentialKeyword + "#");
                            }
                        }
                    });	
                });
                // ALEX CODE ABOVE///////////////////////////////
                
                $.post("addNote.php", {chapterid: chapid, content: /*text.val()*/ textVal, projectid: <?=$_GET['projectid']?>}, 
                         function(id){
                                    
                            appendNote(id, /*text.val()*/ textVal, chapid);
                                       
                            // TODO: add keyword if note is changed and contains keyword
                            for(i = 0; i <= (matches.length - 1); i++) {
                                var str = matches[i];
                                $.post("addKeyword.php",{
                                       word: str.substring(1, str.length - 1), noteid: id, chapterid: chapid, projectid: <?=$_GET['projectid']?>
                                });
                            }	
                });
            } //insert note into database & in current page
            
            
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
                deleteKeyword(id);  //Also remove any associated keywords
            }
            
            function deleteKeyword(noteid){
                // TODO remove keyword only for that chapter
                $.post("deleteKeyword.php", {noteid: noteid});
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
                li = "<li onMouseOver='showChapterControls("+id+")' onMouseOut='hideChapterControls("+id+")'><a href='#tabs-"+id+"'>"+name+"</a> <a href='#' class='chaptercontrols' id='chaptercontrols"+id+"' onclick='deleteChapter("+id+");return false;'><img src='https://cdn1.iconfinder.com/data/icons/aspneticons_v1.0_Nov2006/trash_(delete)_16x16.gif' /></a> </li>";
                
                $("#tabs").find( ".ui-tabs-nav" ).append( li );
                hideChapterControls(id);
                $("#tabs").append( "<div id='tabs-" + id + "'><h2>" + name + "</h2><div id='notes"+id+"'> </div><p><textarea id='newNote"+id+"' ></textarea><input type='submit' id='submitNote"+id+"' name='submit' value='add' onclick='addNote("+id+")'/></p></div>" );
                $("#tabs").tabs( "refresh" );
            }
            
            
            function appendNote(id, content, chapterid){
                // n = content.indexOf(":"); //get first :
                // if(n!=-1){
                //   //: is found
                //   m = content.indexOf(":", n+1); //get second:
                //   if(m!=-1){
                //     keyword = content.substring(n+1, m);
                //     alert(keyword);
                //   }
                // }
                
                //1. Get list of current keywords
                //2. Search document for any words that might be keywords
                //3. Change text of matching words to highlighted
                
                // Replace any tagged '**' words with highlight
                content = content.replace(/\#(.*?)\#/g,"<mark>$1</mark>");
                
                 // Replace any tagged '[]' words with bold
                content = content.replace(/\[(.*?)\]/g,"<strong>$1</strong>");
                
                var newnote = "<div class='note' id='note"+id+"' onMouseOver='showNoteControls("+id+")' onMouseOut='hideNoteControls("+id+")'><p class='notetext' id='notetext"+id+"' contenteditable='true'>"+content+"</p><div class='notecontrols' id='notecontrols"+id+"'><a href='#' onclick='deleteNote("+id+");return false;'><img src='https://cdn1.iconfinder.com/data/icons/aspneticons_v1.0_Nov2006/trash_(delete)_16x16.gif' /></a></div></div>";
                $("#notes" + chapterid).append(newnote);
                
                
                hideNoteControls(id);
                $("#notetext"+id).blur(function() { //Update database when note is changed
                    var newcontent = $(this).html();
                    
                    $.post("editNote.php",
                           {noteid: id, content: newcontent});
                    
                });
            }
            
            function showNoteControls(id){
                $('#notecontrols'+id).css('visibility','visible');
            }
            
            function hideNoteControls(id){
                $('#notecontrols'+id).css('visibility','hidden');
            }
            
            function showChapterControls(id){
                $('#chaptercontrols'+id).css('visibility','visible');
            }
            
            function hideChapterControls(id){
                $('#chaptercontrols'+id).css('visibility','hidden');
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