<?php 
$page="projects";
include('header.php'); ?>

            <script src="summary.js"></script>
                    
              <?php
                include("connect.php");

                if(isset($_GET['projectid'])){
                    $result = mysql_query("SELECT name FROM projects WHERE id='".$_GET['projectid']."'") or die(mysql_error());
                    $projectname = mysql_result($result, 0);        
                }

              ?>

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
            

            <div class="container content">
                <div id="tabs">
                    <div id="chapters">
                        <h1 id="projectname"><?= $projectname ?></h1>
                        <ul>
                        </ul>
                        <button id="add_tab" class="btn btn-success">Add chapter</button>
                    </div>
                    
                </div>
            </div>
            
        </body>
    </html>