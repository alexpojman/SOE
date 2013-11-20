<?php
	include('connect.php');
    
    
    $query = "INSERT INTO sharedprojects (userid, projectid, permissions) VALUES ('".$_POST['user']."', '".$_POST['project']."', '".$_POST['permissions']."')";
    mysql_query($query) or die (mysql_error());

    echo mysql_insert_id();
    
    $updateProject = "UPDATE projects SET projects.share_status = 'shared' WHERE projects.id = '".$_POST['project']."'"; 
        mysql_query($updateProject) or die (mysql_error());

    echo mysql_insert_id();
?>