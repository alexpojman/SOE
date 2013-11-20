<?php
	include("connect.php");

	mysql_query("DELETE FROM chapters WHERE id = ".$_POST['chapterid']." LIMIT 1") or die (mysql_error());
	mysql_query("DELETE FROM notes WHERE chapterid = ".$_POST['chapterid']) or die (mysql_error());
?>