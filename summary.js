$(document).ready(function() {
                $("#tabs").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
                $("#tabs li").removeClass('ui-corner-top').addClass('ui-corner-left');
                
                init(getUrlVars()["projectid"]);
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
                           {name: name, subtitle: subtitle, projectid: getUrlVars()["projectid"]}, 
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
            function getUrlVars() {
                var vars = {};
                var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    vars[key] = value;
                });
                return vars;
            }


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
                        if (matches!=null){
                          for(j = 0; j <= (matches.length - 1); j++) {
                              var existingKeyword = keyword.word;
                              var potentialKeyword = matches[j].substring(1, matches[j].length - 1);
                              
                              if(existingKeyword.toLowerCase() == potentialKeyword.toLowerCase()) {
                                  alert("Keyword '" + potentialKeyword + "' already exists, and will be turned into a reference instead.");
                                  textVal = textVal.replace("[" + potentialKeyword + "]", "#" + potentialKeyword + "#");
                              }
                          }
                        }
                    }); 
                });
                // ALEX CODE ABOVE///////////////////////////////
                
                $.post("addNote.php", {chapterid: chapid, content: /*text.val()*/ textVal, projectid: getUrlVars()["projectid"]}, 
                         function(id){
                                    
                            appendNote(id, /*text.val()*/ textVal, chapid);
                                       
                            // TODO: add keyword if note is changed and contains keyword
                            if (matches!=null){
                              for(i = 0; i <= (matches.length - 1); i++) {
                                var str = matches[i];
                                $.post("addKeyword.php",{
                                       word: str.substring(1, str.length - 1), noteid: id, chapterid: chapid, projectid: getUrlVars()["projectid"]
                                });
                              } 
                            }
                            
                });

              //Clear textarea
              document.getElementById("newNote" + chapid).value = "";

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
                $.post("getChapters.php", {projectid: projectid}, function(data){ //Get all initial notes and chapters
                    
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

                getKeywords();
                //How to select the first tab?
            }
            
            function getKeywords(){ //Get all keywords from the project
                $.post("getKeywords.php", {projectid: getUrlVars()["projectid"]}, function(data){
                    console.log(data);
                    // var keywords = jQuery.parseJSON(data);
                    // $.each(keywords, function(i, keyword){
                    //     console.log(keyword);
                    // });
                });
            }

            function appendChapter(id, name){
                li = "<li onMouseOver='showChapterControls("+id+")' onMouseOut='hideChapterControls("+id+")'><a href='#tabs-"+id+"'>"+name+"</a> <a href='#' class='chaptercontrols' id='chaptercontrols"+id+"' onclick='deleteChapter("+id+");return false;'><img src='https://cdn1.iconfinder.com/data/icons/aspneticons_v1.0_Nov2006/trash_(delete)_16x16.gif' /></a> </li>";
                
                $("#tabs").find( ".ui-tabs-nav" ).append( li );
                hideChapterControls(id);
                $("#tabs").append( "<div id='tabs-" + id + "'><h2 id='chaptername'>" + name + "</h2><div id='notes"+id+"' class='notes'> </div><p><textarea id='newNote"+id+"' class='newnote' ></textarea><input type='submit' id='submitNote"+id+"' name='submit' value='Add' onclick='addNote("+id+")' class='btn btn-success'/></p></div>" );
                $("#tabs").tabs( "refresh" );
            }
            
            
            function appendNote(id, content, chapterid){
       
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