<?php 
	$page = "projects";
	include("header.php");

	$cookie = explode("-",$_COOKIE["auth"]);
    $userid = $cookie[0];
?>
	<script>
	$(document).ready(function() {
        
        $("#projectsubmit").submit(function(event){
          event.preventDefault();
          if($('#projectname').val() !== "") {        
              $.post("addProject.php", {name: $('#projectname').val(), user: $("#userid").val()}, function(id){
                  window.location = "project.php?projectid="+id;
              });
          }
       	 else {
         alert("Please add a project name.");   
        	}
        });

        function deleteProject(id){
          $.post("deleteProject.php", {id: id}, function(){
            $("#project"+id).remove();
          });
        }

        $(".deleteproject").click(function(event){
          event.preventDefault();
          deleteProject(this.id);
          this.remove();

        });
        
        //ALEX CODE BELOW////////////////--------------------------------------
        function deleteSharedProject(projectid) {
            $.post('deleteSharedProject.php', {project: projectid, user:$("#userid").val()}, function() {
                $("#sharedproject"+projectid).remove();
            });
        }
        
        $(".deletesharedproject").click(function(event){
            event.preventDefault();
            deleteSharedProject(this.id);
            this.remove();
        });
        
        function shareProject(sharedUserId, projectId, permissions){
             $.post("shareProject.php", {user: sharedUserId, project: projectId, permissions: permissions}, function(){
             });
        }
        
        
        var sharedProjectId = "";               // used to store the projectid of the project to be shared
        var empty = "";
        // Used to grab project id when "Share" link is initially clicked
        $(".shareInitialize").click(function(event){
            event.preventDefault();
            sharedProjectId = this.id;
        });
        
        // Handles the "Share" modal button being clicked
        $(document.getElementById("shareProjectBtn")).click(function(event){
            event.preventDefault();
            
            var perm_ddl = document.getElementById("permissions_ddl"); // the permissions dropdown
            var selected_perm = perm_ddl.options[perm_ddl.selectedIndex].value;      // get selected option
            
            var sharedUserTextBox = document.getElementById("shareduser_box");
            var sharedUserEmail = sharedUserTextBox.value;
        
            // Convert Email to ID, then Insert into 'sharedprojects' table
            $.post('getSharedUserID.php', {email: sharedUserEmail}, function(data){
               try{ 
                   var sharedUserIDs = jQuery.parseJSON(data);
                    $.each(sharedUserIDs, function(i, sharedUser){
                        
                        shareProject(sharedUser.id, sharedProjectId, selected_perm);
                    });
                  }
                catch(e){alert(e);}
            });   
        });
        //ALEX Code Above
    });
	</script>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="container content">
		<h2>Projects</h2>
		<div class="col-lg-4">
          <h3>My projects</h3>
          <p><?php
          		
        include("connect.php");
        		
				$result = mysql_query("SELECT id, name FROM projects WHERE userid = '".$userid."' ORDER BY name ASC")
				or die(mysql_error());
				if  (mysql_num_rows($result)>0)
				{
					while($row = mysql_fetch_array( $result )) 
					{	
						echo '<a href="project.php?projectid='.$row['id'].'" id="project'.$row['id'].'">'.$row['name'].'</a> 
            <a href="#" class="deleteproject" id="'.$row['id'].'" ><img src="https://cdn1.iconfinder.com/data/icons/aspneticons_v1.0_Nov2006/trash_(delete)_16x16.gif" /></a>
            <a href="#shareproject" class="shareInitialize" data-toggle="modal" id="'.$row['id'].'">Share</a>
            <br />';
					}
				}else{
					echo "Ain't got no projects yet...";
				}
			?></p>
        </div>
        <div class="col-lg-4">
          <h3>Shared with me</h3>
            <!-- Alex Code Below ----------------------------------------------------------------!-->
                      <p><?php
          		
        include("connect.php");
        		
				$result = mysql_query("SELECT id, name FROM projects INNER JOIN sharedprojects ON projects.id = sharedprojects.projectid WHERE sharedprojects.userid = '".$userid."' ORDER BY name ASC")
				or die(mysql_error());
				if  (mysql_num_rows($result)>0)
				{
					while($row = mysql_fetch_array( $result )) 
					{	
						echo '<a href="project.php?projectid='.$row['id'].'" id="sharedproject'.$row['id'].'">'.$row['name'].'</a> 
            <a href="#" class="deletesharedproject" id="'.$row['id'].'" ><img src="https://cdn1.iconfinder.com/data/icons/aspneticons_v1.0_Nov2006/trash_(delete)_16x16.gif" /></a>
            <br />';
					}
				}else{
					echo "Ain't got no shared projects yet...";
				}
			?></p>
            
            <!-- Alex Code Above ----------------------------------------------------------------!-->
          <!--<p>No shared projects yet.</p>!-->
        </div>
        <div class="col-lg-4">
          <h3>Create new</h3>
          <p><form id="projectsubmit">
			<input type="text" name="projectname" id="projectname" placeholder="Project name"/>
			<input type="hidden" value="<?= $userid ?>" id="userid"/>
			<input type="submit" value="Create" name="projectsubmit" class="btn btn-success" />
			
			</form></p>
        </div>
		
    <!-- Alex code below!-->
        <div class="modal fade" id="shareproject" role="dialog">
            <div class = "modal-dialog">
                <div class= "modal-header">
                    <h3><strong>Share Project</strong></h3>
                </div>
                <div class = "modal-body">
                    <table>
                        <tr>
                            <td>
                                <p class="form-control-static">Email of User to Share with: </p>
                            </td>
                            <td>
                                <input type="text" id="shareduser_box" class="form-control"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                               <p class="form-control-static">User Permissions:</p>
                            </td>
                            <td>
                                <select id="permissions_ddl" class="form-control input-sm">
                                    <option value="edit">Edit</option>
                                    <option value="read">Read Only</option>
                                    <option value="comment">Comment</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class = "modal-footer">
                    <a href="#" id = "shareProjectBtn" class="btn btn-success" data-dismiss="modal" data-target="#shareproject" name="shareproject">
                        Share!
                    </a>
                </div>
            </div>
        </div>
    <!-- Alex code above!-->

    </div> <!-- /container -->
    <footer>
          <p>&copy; SumOurEyes 2013 | Contact</p>
        </footer>
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>

	</body>
</html>