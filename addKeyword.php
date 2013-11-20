<?php
	include("connect.php");

	mysql_query("INSERT INTO keywords (word, noteid, chapterid, projectid) 
		VALUES ('".$_POST['word']."', '".$_POST['noteid']."', '".$_POST['chapterid']."', '".$_POST['projectid']."')") 
	or die (mysql_error());

	echo mysql_insert_id();

?>