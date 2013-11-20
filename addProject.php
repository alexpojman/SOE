<?php
	include('connect.php');

    $query = "INSERT INTO projects (name, userid) VALUES ('".$_POST['name']."', '".$_POST['user']."')";
    mysql_query($query) or die (mysql_error());

    echo mysql_insert_id();
    
?>