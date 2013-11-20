<?php
	include("connect.php");

	$results = mysql_query("SELECT id FROM users WHERE email='".$_POST['email']."'") 
	or die (mysql_error());


    $rows = array();
	while($r = mysql_fetch_assoc($results)) 
	{
	    $rows[] = $r;
	}

	echo json_encode($rows);


?>