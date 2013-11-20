<?php

	include("connect.php");

	$results = mysql_query("SELECT id, content FROM notes WHERE chapterid='".$_POST['chapterid']."'") or die(mysql_error());

	$rows = array();
	while($r = mysql_fetch_assoc($results)) 
	{
	    $rows[] = $r;
	}

	echo json_encode($rows);
?>