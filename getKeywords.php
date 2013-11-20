<?php
	include("connect.php");
	
	$results = mysql_query("SELECT id, word FROM keywords WHERE projectid=".$_POST['projectid']) or die(mysql_error());
	
	$rows = array();
	while($r = mysql_fetch_assoc($results)) 
	{
	    $rows[] = $r;
	}

	echo json_encode($rows);

?>