<?php

	include("connect.php");

	$results = mysql_query("SELECT id, name FROM chapters WHERE projectid='".$_POST['projectid']."'") or die(mysql_error());

	$rows = array();
	while($r = mysql_fetch_assoc($results)) 
	{
	    $rows[] = $r;
	}

	echo json_encode($rows);
?>