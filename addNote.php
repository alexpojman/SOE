<?php
	include("connect.php");

	mysql_query("INSERT INTO notes (content, chapterid, projectid) 
		VALUES ('".$_POST['content']."', '".$_POST['chapterid']."', '".$_POST['projectid']."')") 
	or die (mysql_error());

	echo mysql_insert_id();

?>